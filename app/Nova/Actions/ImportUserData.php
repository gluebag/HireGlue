<?php

namespace App\Nova\Actions;

use App\Models\Education;
use App\Models\Skill;
use App\Models\WorkExperience;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Nova\Fields\BooleanGroup;

class ImportUserData extends Action
{
    use InteractsWithQueue, Queueable;

    public $standalone = true;

    public $name = 'Import Resume/CV Data';

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            File::make('Resume File')
                ->disk('public')
                ->storeAs(function (Request $request, $model, string $attribute, string $requestAttribute) {
                    return $request->resume_file->getClientOriginalName();
                })
                ->rules('required_without:pasted_content')
                ->help('Upload your resume/CV file (PDF or Markdown)'),

            Textarea::make('Pasted Content')
                ->rules('required_without:resume_file')
                ->help('Or paste your resume content directly (HTML, Markdown, or plain text)'),

            Select::make('File Type', 'file_type')
                ->options([
                    'pdf' => 'PDF',
                    'markdown' => 'Markdown/Text',
                    'html' => 'HTML',
                    'any' => 'Any',
                ])
                ->rules('required')
                ->default('any'),
            
            BooleanGroup::make('Import Categories', 'import_categories')
                ->options([
                    'skills' => 'Skills',
                    'education' => 'Education',
                    'work_experience' => 'Work Experience',
                    'projects' => 'Projects',
                ])
                ->default([
                    'skills' => false,
                    'education' => false,
                    'work_experience' => false,
                    'projects' => false,
                ])
                ->help('Select which categories you want to import'),
        ];
    }

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        try {
            $fileContent = null;
            $selectedCategories = array_keys(array_filter($fields->import_categories ?? []));
            
            if (empty($selectedCategories)) {
                return Action::danger('Please select at least one category to import.');
            }
            
            if (!empty($fields->resume_file)) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $fields->resume_file;
                $fileType = $fields->file_type;
                $mimeType = $uploadedFile->getMimeType();

                // log the file and fields
                \Log::debug(sprintf('Importing potential skills, experience, and education from uploaded resume/cv file: [type: %s][mimeType: %s](' . $uploadedFile->getClientOriginalName() . ')', $fileType, $mimeType), [
                    'user_id' => Auth::id(),
                    'file_type' => $fileType,
                    'mime_type' => $mimeType,
                    'file_name' => $uploadedFile->getClientOriginalName(),
                    'file_size' => $uploadedFile->getSize(),
                    'file_path' => $uploadedFile->getRealPath(),
                ]);

                // Check if file exists before trying to read it
                if (!file_exists($uploadedFile->getRealPath())) {
                    Log::error('Resume file not found at expected location', [
                        'full_path' => $uploadedFile->getRealPath(),
                        'user_id' => Auth::id(),
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                        'file_type' => $fileType,
                        'mime_type' => $mimeType,
                    ]);
                    return Action::danger('Error importing data: File not found at expected location. Please try again. (' . $uploadedFile->getRealPath() . ')');
                }

                // Determine file type if mode is 'any'
                if ($fileType === 'any') {
                    $fileType = $uploadedFile->guessExtension();
                }

                // For PDFs, you'd need to extract text first
                if ($fields->file_type === 'pdf' || $mimeType === 'application/pdf') {
                    Log::debug('Extracting text from PDF file', [
                        'user_id' => Auth::id(),
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                        'file_type' => $fileType,
                        'mime_type' => $mimeType,
                    ]);
                    $fileContent = $this->extractTextFromPdf($uploadedFile->getRealPath());
                } else {
                    Log::debug('Reading file content as plaintext or markdown', [
                        'user_id' => Auth::id(),
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                        'file_type' => $fileType,
                        'mime_type' => $mimeType,
                    ]);
                    // For Markdown or text files, just read the content
                    $fileContent = file_get_contents($uploadedFile->getRealPath());
                }
            } elseif (!empty($fields->pasted_content)) {
                $fileType = $fields->file_type;
                $content = $fields->pasted_content;
                
                \Log::debug('Importing potential skills, experience, and education from pasted content', [
                    'user_id' => Auth::id(),
                    'file_type' => $fileType,
                    'content_length' => strlen($content),
                ]);
                
                // Check if content is HTML
                if ($fileType === 'html' || $fileType === 'any' && $this->looksLikeHtml($content)) {
                    \Log::debug('Converting HTML content to markdown', [
                        'user_id' => Auth::id(),
                        'content_length' => strlen($content),
                    ]);
                    $htmlToMarkdown = app(HtmlToMarkdownService::class);
                    $fileContent = $htmlToMarkdown->convert($content);
                } else {
                    $fileContent = $content;
                }
            } else {
                return Action::danger('Please provide either a file or pasted content.');
            }

            // Use OpenAI to parse the content with selected categories
            $parsedData = $this->parseResumeWithOpenAI($fileContent, $selectedCategories);
            Log::debug('Parsed data from resume/cv import', [
                'user_id' => Auth::id(),
                'file_type' => $fileType ?? 'unknown',
                'parsed_data' => $parsedData,
                'selected_categories' => $selectedCategories,
            ]);

            // Save the extracted data and get stats, only for selected categories
            $stats = $this->saveUserData($parsedData, $selectedCategories);

            // Build detailed message
            $message = 'Successfully imported user data from resume!<br><br>';
            $message .= "<strong>Summary:</strong><br>";
            
            if (in_array('skills', $selectedCategories)) {
                $message .= "• Skills: {$stats['skills']['added']} added" . 
                           ($stats['skills']['skipped'] > 0 ? ", {$stats['skills']['skipped']} duplicates skipped" : "") . "<br>";
            }
            
            if (in_array('education', $selectedCategories)) {
                $message .= "• Education: {$stats['education']['added']} added" . 
                           ($stats['education']['skipped'] > 0 ? ", {$stats['education']['skipped']} duplicates skipped" : "") . "<br>";
            }
            
            if (in_array('work_experience', $selectedCategories)) {
                $message .= "• Work Experience: {$stats['work_experience']['added']} added" . 
                           ($stats['work_experience']['skipped'] > 0 ? ", {$stats['work_experience']['skipped']} duplicates skipped" : "") . "<br>";
            }
            
            if (in_array('projects', $selectedCategories)) {
                $message .= "• Projects: {$stats['projects']['added']} added" . 
                           ($stats['projects']['skipped'] > 0 ? ", {$stats['projects']['skipped']} duplicates skipped" : "");
            }

            return Action::message($message);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
            ]);
            return Action::danger('Error importing data: ' . $e->getMessage());
        }
    }

    /**
     * Check if content looks like HTML
     */
    private function looksLikeHtml(string $content): bool
    {
        return 
            stripos($content, '<html') !== false || 
            stripos($content, '<!doctype') !== false ||
            (stripos($content, '<') !== false && 
             preg_match('/<(\w+)(?:\s+[\w\-]+=(?:"[^"]*"|\'[^\']*\'))*\s*>/i', $content));
    }

    /**
     * Extract text from PDF file
     */
    private function extractTextFromPdf(string $fullPath): string
    {
        // Check if file exists
        if (!file_exists($fullPath)) {
            throw new \Exception("PDF file not found at {$fullPath}");
        }

        try {
            // Option 1: Use pdftotext if available on server
//            if (shell_exec('which pdftotext')) {
//                $output = shell_exec('pdftotext ' . escapeshellarg($fullPath) . ' -');
//                Log::debug('Extracted text from PDF using [pdftotext]', [
//                    'user_id' => Auth::id(),
//                    'file_name' => basename($fullPath),
//                    'file_size' => filesize($fullPath),
//                    'output_length' => strlen($output),
//                    'output' => $output,
//                ]);
//                if ($output) return $output;
//            }

            // Option 2: Use a PHP library like Smalot\PdfParser
            // Make sure the library is installed
            if (!class_exists('\Smalot\PdfParser\Parser')) {
                throw new \Exception('PDF Parser library not installed. Run: composer require smalot/pdfparser');
            }

            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($fullPath);
            $text = $pdf->getText();
            Log::debug('Extracted text from PDF using [Smalot\PdfParser]', [
                'user_id' => Auth::id(),
                'file_name' => basename($fullPath),
                'file_size' => filesize($fullPath),
                'output_length' => strlen($text),
                'output' => $text,
            ]);
            return $text;
        } catch (\Exception $e) {
            throw new \Exception("Failed to extract text from PDF: " . $e->getMessage());
        }
    }

    /**
     * Parse resume content with OpenAI
     */
    private function parseResumeWithOpenAI(string $content, array $categories = []): array
    {
        // Build prompt based on selected categories
        $prompt = "Extract the following information from this resume in a structured JSON format:";
        
        if (empty($categories) || in_array('skills', $categories)) {
            $prompt .= "\n1. Skills (with name, type as technical/soft/language/other, estimated proficiency 1-10, years of experience if mentioned)";
        }
        
        if (empty($categories) || in_array('education', $categories)) {
            $prompt .= "\n2. Education (with institution, degree, field_of_study, start_date, end_date, achievements)";
        }
        
        if (empty($categories) || in_array('work_experience', $categories)) {
            $prompt .= "\n3. Work Experience (with company_name, position, start_date, end_date, description, skills_used, achievements)";
        }
        
        if (empty($categories) || in_array('projects', $categories)) {
            $prompt .= "\n4. Projects (with name, description, technologies_used, url if available)";
        }
        
        $prompt .= "\n\n- Make sure all 'start_date' and 'end_date' fields are in ISO 8601 format (YYYY-MM-DD) or null if determined to be present (still working there etc).
        - Ignore or reword/rename skills that are not popular or industry standard. Apply common sense rewording knowing that the parsed result will be used on a new resume to apply at Google (Very complicated to get into Google so make sure to use the best skills and experience possible).
        - Make sure to use the most common and popular skills and experience that are relevant to the job market.
        - For education, allow high school education to be included, and include clever rewording to make it sound more impressive.
        - For projects, include any personal projects or open source contributions and that may not be explicitly listed in the resume.

        Here's the resume content:

        {$content}";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'max_tokens' => 3000,
            'temperature' => 0.1,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a resume parsing expert. Extract structured information from resume in the format of text or markdown, to a JSON format.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ]);

        $jsonContent = $response->choices[0]->message->content;

        // Clean up potential markdown code blocks
        $jsonContent = preg_replace('/```json\s*|\s*```/', '', $jsonContent);

        return json_decode($jsonContent, true);
    }

    /**
     * Save parsed user data to database
     * 
     * @param array $parsedData
     * @param array $selectedCategories
     * @return array Statistics about added and skipped items
     */
    private function saveUserData(array $parsedData, array $selectedCategories = []): array
    {
        $userId = Auth::id();
        $stats = [
            'skills' => ['added' => 0, 'skipped' => 0],
            'education' => ['added' => 0, 'skipped' => 0],
            'work_experience' => ['added' => 0, 'skipped' => 0],
            'projects' => ['added' => 0, 'skipped' => 0],
        ];

        // Save skills
        if (!empty($parsedData['skills']) && (empty($selectedCategories) || in_array('skills', $selectedCategories))) {
            foreach ($parsedData['skills'] as $skillData) {
                // make sure unique to user by name
                $existingSkill = Skill::where('user_id', $userId)
                    ->whereRaw("UPPER('name') LIKE '%" . strtoupper($skillData['name']) . "%'")
                    ->first();
                if ($existingSkill) {
                    Log::debug('[Skill] (' . $skillData['name'] . ') already exists, skipping...', [
                        'user_id' => $userId,
                        'existing_skill' => $existingSkill->toArray(),
                        'new_skill_data' => $skillData,
                    ]);
                    $stats['skills']['skipped']++;
                    continue;
                }

                Skill::create([
                    'user_id' => $userId,
                    'name' => $skillData['name'],
                    'type' => $skillData['type'] ?? 'technical',
                    'proficiency' => $skillData['proficiency'] ?? 5,
                    'years_experience' => $skillData['years_experience'] ?? 0,
                ]);
                $stats['skills']['added']++;
            }
        }

        // Save education
        if (!empty($parsedData['education']) && (empty($selectedCategories) || in_array('education', $selectedCategories))) {
            foreach ($parsedData['education'] as $educationData) {
                // make sure unique to user by institution and degree
                $existingEducation = Education::where('user_id', $userId)
                    ->whereRaw("UPPER('institution') LIKE '%" . strtoupper($educationData['institution']) . "%'")
                    ->whereRaw("UPPER('degree') LIKE '%" . strtoupper($educationData['degree']) . "%'")
                    ->first();
                if ($existingEducation) {
                    Log::debug('[Education] (' . $educationData['institution'] . ') already exists, skipping...', [
                        'user_id' => $userId,
                        'existing_education' => $existingEducation->toArray(),
                        'new_education_data' => $educationData,
                    ]);
                    $stats['education']['skipped']++;
                    continue;
                }
                Education::create([
                    'user_id' => $userId,
                    'institution' => $educationData['institution'],
                    'degree' => $educationData['degree'],
                    'field_of_study' => $educationData['field_of_study'] ?? null,
                    'start_date' => $educationData['start_date'] ?? now(),
                    'end_date' => $educationData['end_date'] ?? null,
                    'current' => empty($educationData['end_date']),
                    'achievements' => $educationData['achievements'] ?? null,
                ]);
                $stats['education']['added']++;
            }
        }

        // Save work experience
        if (!empty($parsedData['work_experience']) && (empty($selectedCategories) || in_array('work_experience', $selectedCategories))) {
            foreach ($parsedData['work_experience'] as $experienceData) {
                // make sure unique to user by company name and maybe position
                $existingExperience = WorkExperience::where('user_id', $userId)
                    ->whereRaw("UPPER('company_name') LIKE '%" . strtoupper($experienceData['company_name']) . "%'")
                    ->first();
                if ($existingExperience) {
                    Log::debug('[WorkExperience] (' . $experienceData['company_name'] . ') already exists, skipping...', [
                        'user_id' => $userId,
                        'existing_experience' => $existingExperience->toArray(),
                        'new_experience_data' => $experienceData,
                    ]);
                    $stats['work_experience']['skipped']++;
                    continue;
                }

                WorkExperience::create([
                    'user_id' => $userId,
                    'company_name' => $experienceData['company_name'],
                    'position' => $experienceData['position'],
                    'start_date' => $experienceData['start_date'] ?? now(),
                    'end_date' => $experienceData['end_date'] ?? null,
                    'current_job' => empty($experienceData['end_date']),
                    'description' => $experienceData['description'] ?? '',
                    'skills_used' => $experienceData['skills_used'] ?? null,
                    'achievements' => $experienceData['achievements'] ?? null,
                ]);
                $stats['work_experience']['added']++;
            }
        }

        // Save projects
        if (!empty($parsedData['projects']) && (empty($selectedCategories) || in_array('projects', $selectedCategories))) {
            foreach ($parsedData['projects'] as $projectData) {
                // make sure unique to user by name OR url (Case insensitive)
                $existingProject = \App\Models\Project::where('user_id', $userId)
                    ->where(function ($query) use ($projectData) {
                        // make sure case insensitive
                        $query->whereRaw("UPPER('name') LIKE '%" . strtoupper($projectData['name']) . "%'")
                            ->orWhereRaw("UPPER('url') LIKE '%" . strtoupper($projectData['url'] ?? '') . "%'");
                    })
                    ->first();
                if ($existingProject) {
                    Log::debug('[Project] (' . $projectData['name'] . ') already exists, skipping...', [
                        'user_id' => $userId,
                        'existing_project' => $existingProject->toArray(),
                        'new_project_data' => $projectData,
                    ]);
                    $stats['projects']['skipped']++;
                    continue;
                }
                \App\Models\Project::create([
                    'user_id' => $userId,
                    'name' => $projectData['name'],
                    'description' => $projectData['description'] ?? '',
                    'technologies_used' => $projectData['technologies_used'] ?? null,
                    'url' => $projectData['url'] ?? null,
                ]);
                $stats['projects']['added']++;
            }
        }

        return $stats;
    }
}
