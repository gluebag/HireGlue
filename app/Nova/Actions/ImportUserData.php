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
use Laravel\Nova\Http\Requests\NovaRequest;
use OpenAI\Laravel\Facades\OpenAI;

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
                ->rules('required')
                ->help('Upload your resume/CV file (PDF or Markdown)'),

            Select::make('File Type', 'file_type')
                ->options([
                    'pdf' => 'PDF',
                    'markdown' => 'Markdown/Text',
                    'any' => 'Any',
                ])
                ->rules('required')
                ->default('any'),
        ];
    }

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        try {

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
                \Log::error('Resume file not found at expected location', [
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

            // Use OpenAI to parse the content
            $parsedData = $this->parseResumeWithOpenAI($fileContent);
            Log::debug('Parsed data from resume/cv import ('.$uploadedFile->getClientOriginalName().')', [
                'user_id' => Auth::id(),
                'file_type' => $fileType,
                'mime_type' => $mimeType,
                'file_name' => $uploadedFile->getClientOriginalName(),
                'file_size' => $uploadedFile->getSize(),
                'content' => $mimeType !== 'application/pdf' ? $fileContent : null,
                'parsed_data' => $parsedData,
            ]);

            // Save the extracted data
            $this->saveUserData($parsedData);

            return Action::message('Successfully imported user data from resume!');

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'file_name' => $uploadedFile->getClientOriginalName(),
                'file_size' => $uploadedFile->getSize(),
                'file_type' => $fileType,
                'mime_type' => $mimeType,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
            ]);
            return Action::danger('Error importing data: ' . $e->getMessage());
        }
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
    private function parseResumeWithOpenAI(string $content): array
    {
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
                    'content' => "Extract the following information from this resume in a structured JSON format:
                    1. Skills (with name, type as technical/soft/language/other, estimated proficiency 1-10, years of experience if mentioned)
                    2. Education (with institution, degree, field_of_study, start_date, end_date, achievements)
                    3. Work Experience (with company_name, position, start_date, end_date, description, skills_used, achievements)
                    4. Projects (with name, description, technologies_used, url if available)

                    - Make sure all 'start_date' and 'end_date' fields are in ISO 8601 format (YYYY-MM-DD) or null if determined to be present (still working there etc).
                    - Ignore or reword/rename skills that are not popular or industry standard. Apply common sense rewording knowing that the parsed result will be used on a new resume to apply at Google (Very complicated to get into Google so make sure to use the best skills and experience possible).
                    - Make sure to use the most common and popular skills and experience that are relevant to the job market.
                    - For education, allow high school education to be included, and include clever rewording to make it sound more impressive.
                    - For projects, include any personal projects or open source contributions and that may not be explicitly listed in the resume.

                    Here's the resume content:

                    {$content}"
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
     */
    private function saveUserData(array $parsedData): void
    {
        $userId = Auth::id();

        // Save skills
        if (!empty($parsedData['skills'])) {
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
                    continue;
//                    // Update existing skill
//                    $existingSkill->update([
//                        'type' => $skillData['type'] ?? 'technical',
//                        'proficiency' => $skillData['proficiency'] ?? 5,
//                        'years_experience' => $skillData['years_experience'] ?? 0,
//                    ]);
//                    continue;
                }

                Skill::create([
                    'user_id' => $userId,
                    'name' => $skillData['name'],
                    'type' => $skillData['type'] ?? 'technical',
                    'proficiency' => $skillData['proficiency'] ?? 5,
                    'years_experience' => $skillData['years_experience'] ?? 0,
                ]);
            }
        }

        // Save education
        if (!empty($parsedData['education'])) {
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
            }
        }

        // Save work experience
        if (!empty($parsedData['work_experience'])) {
            foreach ($parsedData['work_experience'] as $experienceData) {
                // make sure unique to user by company name and maybe position
                $existingExperience = WorkExperience::where('user_id', $userId)
                    ->whereRaw("UPPER('company_name') LIKE '%" . strtoupper($experienceData['company_name']) . "%'")
//                    ->where('company_name', 'ILIKE', $experienceData['company_name'])
//                    ->where('position', $experienceData['position'])
                    ->first();
                if ($existingExperience) {
                    Log::debug('[WorkExperience] (' . $experienceData['company_name'] . ') already exists, skipping...', [
                        'user_id' => $userId,
                        'existing_experience' => $existingExperience->toArray(),
                        'new_experience_data' => $experienceData,
                    ]);
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
            }
        }

        // Save projects
        if (!empty($parsedData['projects'])) {
            foreach ($parsedData['projects'] as $projectData) {
                // make sure unique to user by name OR url (Case insensitive)
                $existingProject = \App\Models\Project::where('user_id', $userId)
                    ->where(function ($query) use ($projectData) {

                        // make sure case insensitive
                        $query->whereRaw("UPPER('name') LIKE '%" . strtoupper($projectData['name']) . "%'")
                            ->orWhereRaw("UPPER('url') LIKE '%" . strtoupper($projectData['url']) . "%'");
                    })
                    ->first();
                if ($existingProject) {
                    Log::debug('[Project] (' . $projectData['name'] . ') already exists, skipping...', [
                        'user_id' => $userId,
                        'existing_project' => $existingProject->toArray(),
                        'new_project_data' => $projectData,
                    ]);
                    continue;
                }
                \App\Models\Project::create([
                    'user_id' => $userId,
                    'name' => $projectData['name'],
                    'description' => $projectData['description'] ?? '',
                    'technologies_used' => $projectData['technologies_used'] ?? null,
                    'url' => $projectData['url'] ?? null,
                ]);
            }
        }
    }
}
