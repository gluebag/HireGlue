<?php

namespace App\Nova\Actions;

use App\Models\Education;
use App\Models\Skill;
use App\Models\WorkExperience;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        try {
            \Log::debug('Importing resume data...', [
                    'user_id' => Auth::id(),
                    'file_type' => $fields->file_type,
                    'file_name' => $fields->resume_file->getClientOriginalName(),
                    'file_size' => $fields->resume_file->getSize(),
                ]);

//            $filePath = Storage::disk('public')->path($fields->resume_file);
            $fullPath = Storage::disk('public')->path(Auth::id().'-resume-imports/'.$fields->resume_file->getClientOriginalName());

            // Check if file exists before trying to read it
            if (!file_exists($fullPath)) {
                \Log::error('Resume file not found at expected location', [
                    'full_path' => $fullPath,
                    'user_id' => Auth::id(),
                    'file_name' => $fields->resume_file->getClientOriginalName(),
                    'field' => $fields->resume_file,
                ]);
                return Action::danger('Error importing data: File not found at expected location. Please try again.');
            }

            // Get file content
            $fileContent = file_get_contents($fullPath);

            // For PDFs, you'd need to extract text first
            if ($fields->file_type === 'pdf') {
                $fileContent = $this->extractTextFromPdf($fullPath);
            }

            // Use OpenAI to parse the content
            $parsedData = $this->parseResumeWithOpenAI($fileContent);

            // Save the extracted data
            $this->saveUserData($parsedData);

            return Action::message('Successfully imported user data from resume!');

        } catch (\Exception $e) {
            return Action::danger('Error importing data: ' . $e->getMessage());
        }
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            File::make('Resume File', 'resume_file')
                ->disk('public')
                ->path($request->user()->id . '-resume-imports')
                ->storeAs(function (Request $request, $model, string $attribute, string $requestAttribute) {
                    return $request->resume_file->getClientOriginalName();
                })
                ->rules('required', 'file')
                ->help('Upload your resume/CV file (PDF or Markdown)'),

            Select::make('File Type', 'file_type')
                ->options([
                    'pdf' => 'PDF',
                    'markdown' => 'Markdown/Text',
                ])
                ->rules('required')
                ->default('pdf'),
        ];
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
            if (shell_exec('which pdftotext')) {
                $output = shell_exec('pdftotext ' . escapeshellarg($fullPath) . ' -');
                if ($output) return $output;
            }

            // Option 2: Use a PHP library like Smalot\PdfParser
            // Make sure the library is installed
            if (!class_exists('\Smalot\PdfParser\Parser')) {
                throw new \Exception('PDF Parser library not installed. Run: composer require smalot/pdfparser');
            }

            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($fullPath);
            return $pdf->getText();
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
            'temperature' => 0.1,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a resume parsing expert. Extract structured information from resume text in JSON format.'
                ],
                [
                    'role' => 'user',
                    'content' => "Extract the following information from this resume in a structured JSON format:
                    1. Skills (with name, type as technical/soft/language/other, estimated proficiency 1-10, years of experience if mentioned)
                    2. Education (with institution, degree, field_of_study, start_date, end_date, achievements)
                    3. Work Experience (with company_name, position, start_date, end_date, description, skills_used, achievements)
                    4. Projects (with name, description, technologies_used, url if available)

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
