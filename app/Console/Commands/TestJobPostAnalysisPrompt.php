<?php

namespace App\Console\Commands;

use App\Models\OpenAIPrompt;
use App\Services\OpenAIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TestJobPostAnalysisPrompt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:job-post-analysis-prompt {file? : Path to the job post file to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the job post analysis prompt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating or updating Job Post Analysis prompt...');
        
        // Create or update the prompt
        $prompt = $this->createOrUpdatePrompt();
        
        $this->info('Job Post Analysis prompt created successfully!');
        
        // Test the prompt if a file path is provided
        $filePath = $this->argument('file');
        if ($filePath) {
            $this->testPrompt($prompt, $filePath);
        } else {
            $this->info('No test file provided. To test the prompt, run:');
            $this->line('  php artisan test:job-post-analysis-prompt path/to/job-post.md');
        }
        
        return 0;
    }
    
    /**
     * Create or update the Job Post Analysis prompt
     *
     * @return OpenAIPrompt
     */
    protected function createOrUpdatePrompt()
    {
        $prompt = OpenAIPrompt::updateOrCreate(
            [
                'name' => 'Job Post Analysis',
                'type' => 'analysis',
            ],
            [
                'model' => 'gpt-4o',
                'max_tokens' => 2500,
                'temperature' => 0.2,
                'active' => true,
                'prompt_template' => $this->getPromptTemplate(),
                'parameters' => json_encode([
                    'job_content' => [
                        'type' => 'string',
                        'description' => 'HTML or markdown content of the job posting',
                    ]
                ])
            ]
        );
        
        return $prompt;
    }
    
    /**
     * Test the prompt with a sample job post
     *
     * @param OpenAIPrompt $prompt
     * @param string $filePath
     * @return void
     */
    protected function testPrompt(OpenAIPrompt $prompt, string $filePath)
    {
        $this->info('Testing Job Post Analysis prompt with file: ' . $filePath);
        
        if (!File::exists($filePath)) {
            $this->error('File not found: ' . $filePath);
            return;
        }
        
        try {
            // Read job post content
            $content = File::get($filePath);
            $this->info('Job post content loaded (' . strlen($content) . ' characters)');
            
            // Call OpenAI service
            $openai = app(OpenAIService::class);
            $this->info('Analyzing job post with OpenAI...');
            
            $result = $openai->generateCompletion($prompt, [
                'job_content' => $content
            ]);
            
            // Parse the result
            $parsedResult = json_decode($result, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Error parsing JSON response: ' . json_last_error_msg());
                $this->line('Raw response:');
                $this->line($result);
                return;
            }
            
            $this->info('Job Post Analysis completed successfully!');
            $this->line('');
            
            // Display result summary
            $this->info('Job Title: ' . ($parsedResult['job_title'] ?? 'Not found'));
            $this->info('Company Name: ' . ($parsedResult['company_name'] ?? 'Not found'));
            $this->info('Job Location Type: ' . ($parsedResult['job_location_type'] ?? 'Not found'));
            $this->info('Position Level: ' . ($parsedResult['position_level'] ?? 'Not found'));
            $this->info('Job Type: ' . ($parsedResult['job_type'] ?? 'Not found'));
            
            $this->info('Required Skills: ' . count($parsedResult['required_skills'] ?? []));
            $this->info('Preferred Skills: ' . count($parsedResult['preferred_skills'] ?? []));
            $this->info('Required Experience: ' . count($parsedResult['required_experience'] ?? []));
            $this->info('Required Education: ' . count($parsedResult['required_education'] ?? []));
            
            if (isset($parsedResult['salary_range_min']) && isset($parsedResult['salary_range_max'])) {
                $this->info('Salary Range: $' . number_format($parsedResult['salary_range_min']) . ' - $' . number_format($parsedResult['salary_range_max']));
            }
            
            // Option to save full JSON
            if ($this->confirm('Do you want to save the full JSON output to a file?', false)) {
                $outputPath = 'job_post_analysis_' . date('Y-m-d_His') . '.json';
                File::put($outputPath, json_encode($parsedResult, JSON_PRETTY_PRINT));
                $this->info('Full JSON saved to: ' . $outputPath);
            }
            
        } catch (\Exception $e) {
            $this->error('Error testing prompt: ' . $e->getMessage());
        }
    }
    
    /**
     * Get the prompt template
     *
     * @return string
     */
    protected function getPromptTemplate()
    {
        return <<<EOT
You are an expert job application assistant that helps parse and structure job postings. 
Analyze the following job posting and extract the required information in the exact JSON structure provided below.

Job Posting:
{{job_content}}

Your task is to extract and format the following information from this job posting:

1. Basic information: job title, company name, job description
2. Job details: location type, position level, job type
3. Skills (both required and preferred)
4. Experience requirements
5. Education requirements
6. Salary information if available

Be thorough in your analysis. For skills, experiences, and education, use format compatible with our database:

For skills:
- Each skill needs: name, type (technical, soft, domain, tool, language, other), and level (1-5)
- Infer skill types from context
- Set appropriate skill levels (1=beginner, 5=expert) based on job requirements

For experience:
- Each experience needs: title, years (as a number), level (beginner, intermediate, advanced, expert), and description
- Extract years from text (e.g., "3 years experience" → 3)
- Infer experience level from years (0-2: beginner, 3-5: intermediate, 6-9: advanced, 10+: expert)

For education:
- Each education item needs: level (high_school, associate, bachelor, master, doctorate, certificate, other), field, is_required (boolean), min_gpa (if mentioned), description
- Determine if education is required or preferred

Use the exact JSON format below:

```json
{
  "job_title": "",
  "company_name": "",
  "job_description": "",
  "job_location_type": "", // "remote", "in-office", "hybrid", or "unknown"
  "position_level": "", // "entry-level", "mid-level", "senior", "lead", "manager", "director", "executive", or "unknown"
  "job_type": "", // "full-time", "part-time", "contract", "internship", "freelance", or "unknown"
  "required_skills": [
    {
      "name": "",
      "type": "", // "technical", "soft", "domain", "tool", "language", "other"
      "level": 0 // 1-5 scale
    }
  ],
  "preferred_skills": [
    {
      "name": "",
      "type": "", // "technical", "soft", "domain", "tool", "language", "other"
      "level": 0 // 1-5 scale
    }
  ],
  "required_experience": [
    {
      "title": "",
      "years": 0,
      "level": "", // "beginner", "intermediate", "advanced", "expert"
      "description": ""
    }
  ],
  "required_education": [
    {
      "level": "", // "high_school", "associate", "bachelor", "master", "doctorate", "certificate", "other"
      "field": "",
      "is_required": true,
      "min_gpa": null, // number or null
      "description": ""
    }
  ],
  "salary_range_min": null, // number or null
  "salary_range_max": null // number or null
}
```

Output ONLY valid JSON that matches the structure above. Nothing else.
EOT;
    }
}
