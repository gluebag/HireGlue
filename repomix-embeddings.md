This file is a merged representation of a subset of the codebase, containing specifically included files, combined into a single document by Repomix.
The content has been processed where security check has been disabled.

# Directory Structure
```
app/
  Models/
    CoverLetter.php
    Education.php
    JobPost.php
    JobRequirementEmbedding.php
    OpenAIPrompt.php
    Project.php
    Resume.php
    Rule.php
    Skill.php
    SkillEmbedding.php
    ThreadSession.php
    User.php
    WorkExperience.php
  Nova/
    Actions/
      ConvertGoogleJobPost.php
      GenerateApplicationMaterials.php
      GenerateCoverLetter.php
      GenerateResume.php
      ImportJobPostFromContent.php
      RegenerateWithFeedback.php
    Dashboards/
      Main.php
    Repeaters/
      EducationItem.php
      ExperienceItem.php
      SkillItem.php
    CoverLetter.php
    Education.php
    JobPost.php
    OpenAIPrompt.php
    Project.php
    Resource.php
    Resume.php
    Rule.php
    Skill.php
    ThreadSession.php
    User.php
    WorkExperience.php
  Providers/
    AppServiceProvider.php
    HorizonServiceProvider.php
    NovaServiceProvider.php
    TelescopeServiceProvider.php
  Services/
    AssistantsService.php
    EmbeddingsService.php
    GenerationService.php
    JobPostAIService.php
    OpenAIService.php
    PDFService.php
    PromptEngineeringService.php
    RulesService.php
    ThreadManagementService.php
database/
  factories/
    CoverLetterFactory.php
    JobPostFactory.php
    ResumeFactory.php
    UserFactory.php
  migrations/
    0001_01_01_000000_create_users_table.php
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2025_04_05_174213_create_telescope_entries_table.php
    2025_04_05_175254_add_two_factor_columns_to_users_table.php
    2025_04_05_185149_create_job_posts_table.php
    2025_04_05_185524_create_work_experiences_table.php
    2025_04_05_185606_create_education_table.php
    2025_04_05_185713_create_skills_table.php
    2025_04_05_190541_create_projects_table.php
    2025_04_05_190552_create_resumes_table.php
    2025_04_05_190603_create_cover_letters_table.php
    2025_04_05_190617_create_rules_table.php
    2025_04_05_190623_create_openai_prompts_table.php
    2025_04_06_040907_create_thread_sessions_table.php
    2025_04_06_041204_add_thread_session_id_to_resumes_and_cvs.php
    2025_04_06_044443_create_skill_embeddings_table.php
    2025_04_06_044453_create_job_requirement_embeddings_table.php
  seeders/
    DatabaseSeeder.php
    OpenAIPromptsSeeder.php
    RulesSeeder.php
    UserSeeder.php
resources/
  views/
    pdfs/
      cover_letter.blade.php
      layout.blade.php
      resume.blade.php
routes/
  console.php
  web.php
tests/
  Feature/
    ExampleTest.php
  Unit/
    ExampleTest.php
    GenerationServiceTest.php
    OpenAIServiceTest.php
    PDFServiceTest.php
    RulesServiceTest.php
  TestCase.php
composer.json
repomix.config.json
```

# Files

## File: repomix.config.json
````json
{
    "output": {
        "filePath": "repomix-embeddings.md",
        "style": "markdown",
        "parsableStyle": false,
        "fileSummary": false,
        "directoryStructure": true,
        "removeComments": false,
        "removeEmptyLines": false,
        "compress": false,
        "topFilesLength": 5,
        "showLineNumbers": false,
        "copyToClipboard": true,
        "git": {
            "sortByChanges": true,
            "sortByChangesMaxCommits": 100
        }
    },
    "include": [
        "**/app/Models/**",
        "**/app/Nova/**",
        "**/app/Providers/**",
        "**/app/Services/**",
        "**/database/factories/**",
        "**/database/migrations/**",
        "**/database/seeders/**",
        "**/resources/views/**",
        "**/routes/**",
        "**/tests/**",
        "**/composer.json",

        "**/repomix.config.json"

    ],
    "ignore": {
        "useGitignore": true,
        "useDefaultPatterns": true,
        "customPatterns": [
        ]
    },
    "security": {
        "enableSecurityCheck": false
    },
    "tokenCount": {
        "encoding": "o200k_base"
    }
}
````

## File: app/Models/Education.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Education extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'education';

    protected $fillable = [
        'user_id', 'institution', 'degree', 'field_of_study',
        'start_date', 'end_date', 'current', 'gpa', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current' => 'boolean',
        'gpa' => 'decimal:2',
        'achievements' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
````

## File: app/Models/JobPost.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'company_name', 'job_title', 'job_description', 'job_post_url',
        'job_post_date', 'job_location_type', 'required_skills', 'preferred_skills',
        'required_experience', 'required_education', 'resume_min_words', 'resume_max_words',
        'cover_letter_min_words', 'cover_letter_max_words', 'resume_min_pages',
        'resume_max_pages', 'cover_letter_min_pages', 'cover_letter_max_pages',
        'things_i_like', 'things_i_dislike', 'things_i_like_about_company',
        'things_i_dislike_about_company', 'open_to_travel', 'salary_range_min',
        'salary_range_max', 'min_acceptable_salary', 'position_level', 'job_type',
        'ideal_start_date', 'position_preference', 'first_time_applying'
    ];

    protected $casts = [
        'required_skills' => 'array',
        'preferred_skills' => 'array',
        'required_experience' => 'array',
        'required_education' => 'array',
        'job_post_date' => 'date',
        'ideal_start_date' => 'date',
        'open_to_travel' => 'boolean',
        'first_time_applying' => 'boolean',
        'salary_range_min' => 'decimal:2',
        'salary_range_max' => 'decimal:2',
        'min_acceptable_salary' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function coverLetters()
    {
        return $this->hasMany(CoverLetter::class);
    }
}
````

## File: app/Models/JobRequirementEmbedding.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequirementEmbedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'requirement_type',
        'embedding',
        'requirement_text',
    ];

    /**
     * Get the job post that the embedding is for.
     */
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
````

## File: app/Models/OpenAIPrompt.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenAIPrompt extends Model
{
    use SoftDeletes;

    protected $table = 'openai_prompts';

    protected $fillable = [
        'name', 'type', 'prompt_template', 'parameters',
        'model', 'max_tokens', 'temperature', 'active'
    ];

    protected $casts = [
        'parameters' => 'array',
        'max_tokens' => 'integer',
        'temperature' => 'decimal:1',
        'active' => 'boolean'
    ];
}
````

## File: app/Models/Project.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'description', 'start_date', 'end_date',
        'url', 'technologies_used', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'technologies_used' => 'array',
        'achievements' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
````

## File: app/Models/Rule.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'type', 'source', 'importance', 'validation_logic'
    ];

    protected $casts = [
        'validation_logic' => 'array',
        'importance' => 'integer'
    ];
}
````

## File: app/Models/Skill.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'type', 'proficiency', 'years_experience'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
````

## File: app/Models/SkillEmbedding.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillEmbedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_id',
        'embedding',
        'skill_description',
    ];

    /**
     * Get the user that owns the skill embedding.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skill that the embedding is for.
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
````

## File: app/Models/ThreadSession.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThreadSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_post_id',
        'assistant_id',
        'thread_id',
        'type',
        'status',
        'content',
        'error',
        'completed_at',
        'metrics',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'metrics' => 'array',
    ];

    /**
     * Get the user that owns the session
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job post this session is for
     */
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
    
    /**
     * Get the resume associated with this session
     */
    public function resume()
    {
        return $this->hasOne(Resume::class);
    }
    
    /**
     * Get the cover letter associated with this session
     */
    public function coverLetter()
    {
        return $this->hasOne(CoverLetter::class);
    }
    
    /**
     * Check if the session is for a resume
     */
    public function isResumeSession(): bool
    {
        return $this->type === 'resume';
    }
    
    /**
     * Check if the session is for a cover letter
     */
    public function isCoverLetterSession(): bool
    {
        return $this->type === 'cover_letter';
    }
    
    /**
     * Check if the session is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
    
    /**
     * Check if the session failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
    
    /**
     * Check if the session is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === 'processing';
    }
}
````

## File: app/Models/WorkExperience.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkExperience extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'company_name', 'position', 'start_date', 'end_date',
        'current_job', 'description', 'skills_used', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_job' => 'boolean',
        'skills_used' => 'array',
        'achievements' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
````

## File: app/Nova/Actions/ConvertGoogleJobPost.php
````php
<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;


class ConvertGoogleJobPost extends Action //  implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Convert Job Post';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Enter the URL of the Google job post you want to import.';

    /**
     * Indicates if this action is only available on the resource detail view.
     *
     * @var bool
     */
    public $onlyOnDetail = false;

/**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Job Post URL', 'job_post_url')
                ->rules('required', 'url')
                ->help('Enter the URL of the Google job post you want to import'),

            Text::make('API Key', 'api_key')
                ->rules('required')
                ->help('Your html-to-markdown API key')
                ->default(config('services.html_to_markdown.api_key', '')),
        ];
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        try {
            // Step 1: Fetch HTML content from Google job post
            $jobPostUrl = $fields->job_post_url;
            $htmlContent = $this->fetchJobPostHtml($jobPostUrl);

            if (!$htmlContent) {
                Log::error('Failed to fetch job post HTML content', [
                    'url' => $jobPostUrl,
                ]);
                return Action::danger('Failed to fetch job post HTML content.');
            }

            // Step 2: Convert HTML to Markdown using html-to-markdown API
            Log::debug('Converting HTML to Markdown', [
                'url' => $jobPostUrl,
                'api_key' => $fields->api_key,
                'html_length' => strlen($htmlContent),
                'html_content' => $htmlContent,
            ]);
            $markdown = $this->convertHtmlToMarkdown($jobPostUrl, $htmlContent, $fields->api_key);

            if (!$markdown) {
                return Action::danger('Failed to convert HTML to Markdown.');
            }

            Log::debug('Markdown content', [
                'url' => $jobPostUrl,
                'markdown' => $markdown,
            ]);

            // Step 3: Parse Markdown content and extract relevant info
            $jobData = $this->parseJobPostMarkdown($markdown);

            // Step 4: Create or update JobPost model
            $this->createOrUpdateJobPost($models->first(), $jobData, $jobPostUrl);

            return Action::message('Job post data has been successfully imported!');

        } catch (\Exception $e) {
            return Action::danger('Error: ' . $e->getMessage());
        }
    }

    /**
     * Fetch the HTML content from the Google job post URL.
     *
     * @param string $url
     * @return string|null
     */
    protected function fetchJobPostHtml(string $url): ?string
    {
        try {
            $response = Http::timeout(30)->get($url);

            Log::debug('Google Job Post HTML Response', [
                'url' => $url,
                'response_successful' => $response->successful(),
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                return $response->body();
            }

            // Handle non-successful response
            throw new \RuntimeException('Failed to fetch job post HTML: ' . $response->status());

        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch job post HTML: ' . $e->getMessage());
        }
    }

    /**
     * Convert HTML content to Markdown using html-to-markdown API.
     *
     * @param string $html
     * @param string $apiKey
     * @return string|null
     */
    protected function convertHtmlToMarkdown(string $url, string $html, string $apiKey): ?string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey
            ])->post('https://api.html-to-markdown.com/v1/convert', [
                'html' => $html,

                'plugins' => [
                    'strikethrough' => [],
                    'table' => [],
                ],

                // needs to be just scheme and domain, no path
                'domain' => parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST)
            ]);

            if ($response->successful()) {
                return $response->json('markdown');
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse Markdown content and extract relevant job information.
     *
     * @param string $markdown
     * @return array
     */
    protected function parseJobPostMarkdown(string $markdown): array
    {
        // Initialize job data with default values
        $jobData = [
            'job_title' => '',
            'company_name' => 'Google',
            'job_description' => '',
            'job_location_type' => 'hybrid',
            'required_skills' => [],
            'preferred_skills' => [],
            'required_experience' => [],
            'required_education' => [],
            'salary_range_min' => null,
            'salary_range_max' => null,
            'position_level' => 'senior',
            'job_type' => 'full-time',
        ];

        // Extract job title using regex
        preg_match('/## ([^\n]+)/', $markdown, $titleMatches);
        if (!empty($titleMatches[1])) {
            $jobData['job_title'] = trim($titleMatches[1]);
        }

        // Extract location information
        preg_match('/(?:Austin, TX, USA|Atlanta, GA, USA)(.+)/', $markdown, $locationMatches);
        if (!empty($locationMatches[0])) {
            // If location indicates multiple locations, mark as hybrid
            $jobData['job_location_type'] = 'hybrid';
        }

        // Extract minimum qualifications
        if (preg_match('/### Minimum qualifications:[^\n]*\n(.*?)(?=### |$)/s', $markdown, $minQualMatches)) {
            $minQualText = $minQualMatches[1];
            $qualifications = $this->extractListItems($minQualText);

            foreach ($qualifications as $qual) {
                // If qualification mentions education/degree
                if (preg_match('/degree|Bachelor|Master|PhD|education/i', $qual)) {
                    $jobData['required_education'][] = $this->parseEducationRequirement($qual);
                }
                // If qualification mentions experience
                elseif (preg_match('/(\d+)\s+years?\s+of\s+experience/i', $qual, $expMatch)) {
                    $jobData['required_experience'][] = $this->parseExperienceRequirement($qual, $expMatch[1]);
                }
                // If qualification mentions programming languages
                elseif (preg_match('/Python|Java|programming languages/i', $qual)) {
                    $this->extractSkills($qual, $jobData['required_skills']);
                }
            }
        }

        // Extract preferred qualifications
        if (preg_match('/### Preferred qualifications:[^\n]*\n(.*?)(?=### |$)/s', $markdown, $prefQualMatches)) {
            $prefQualText = $prefQualMatches[1];
            $preferredQuals = $this->extractListItems($prefQualText);

            foreach ($preferredQuals as $qual) {
                // Check if it's a skill
                if (preg_match('/Experience (in|with) ([^\.]+)/i', $qual, $skillMatch)) {
                    $this->extractSkills($skillMatch[2], $jobData['preferred_skills']);
                }
            }
        }

        // Extract about the job / job description
        if (preg_match('/### About the job\n(.*?)(?=### |$)/s', $markdown, $descMatches)) {
            $jobData['job_description'] = trim($descMatches[1]);
        }

        // Extract responsibilities
        if (preg_match('/### Responsibilities\n(.*?)(?=### |$)/s', $markdown, $respMatches)) {
            $responsibilities = $this->extractListItems($respMatches[1]);
            $jobData['job_description'] .= "\n\n### Responsibilities\n" . implode("\n", array_map(fn($r) => "- $r", $responsibilities));
        }

        // Extract salary range
        if (preg_match('/\$(\d+,\d+|\d+)-\$(\d+,\d+|\d+)/s', $markdown, $salaryMatches)) {
            $jobData['salary_range_min'] = (float) str_replace(',', '', $salaryMatches[1]);
            $jobData['salary_range_max'] = (float) str_replace(',', '', $salaryMatches[2]);
        }

        // Format job data for Nova repeaters
        return $this->formatJobDataForNova($jobData);
    }

    /**
     * Format job data for Nova repeaters
     *
     * @param array $jobData
     * @return array
     */
    protected function formatJobDataForNova(array $jobData): array
    {
        // Format required skills
        if (!empty($jobData['required_skills'])) {
            $jobData['required_skills'] = array_map(function($skill) {
                return [
                    'type' => 'skill-item',
                    'fields' => $skill
                ];
            }, $jobData['required_skills']);
        }
        
        // Format preferred skills
        if (!empty($jobData['preferred_skills'])) {
            $jobData['preferred_skills'] = array_map(function($skill) {
                return [
                    'type' => 'skill-item',
                    'fields' => $skill
                ];
            }, $jobData['preferred_skills']);
        }
        
        // Format required experience
        if (!empty($jobData['required_experience'])) {
            $jobData['required_experience'] = array_map(function($exp) {
                return [
                    'type' => 'experience-item',
                    'fields' => $exp
                ];
            }, $jobData['required_experience']);
        }
        
        // Format required education
        if (!empty($jobData['required_education'])) {
            $jobData['required_education'] = array_map(function($edu) {
                return [
                    'type' => 'education-item',
                    'fields' => $edu
                ];
            }, $jobData['required_education']);
        }
        
        return $jobData;
    }

    /**
     * Extract list items from markdown text.
     *
     * @param string $markdownText
     * @return array
     */
    protected function extractListItems(string $markdownText): array
    {
        $items = [];
        preg_match_all('/- ([^\n]+)/', $markdownText, $matches);

        if (!empty($matches[1])) {
            $items = array_map('trim', $matches[1]);
        }

        return $items;
    }

    /**
     * Parse education requirement from qualification text.
     *
     * @param string $text
     * @return array
     */
    protected function parseEducationRequirement(string $text): array
    {
        $education = [
            'level' => 'bachelor',
            'field' => 'Computer Science',
            'is_required' => true,
            'description' => $text
        ];

        if (preg_match('/Bachelor\'?s/i', $text)) {
            $education['level'] = 'bachelor';
        } elseif (preg_match('/Master\'?s/i', $text)) {
            $education['level'] = 'master';
        } elseif (preg_match('/PhD|Doctorate/i', $text)) {
            $education['level'] = 'doctorate';
        }

        if (preg_match('/in\s+([^,\.]+)/i', $text, $fieldMatch)) {
            $education['field'] = trim($fieldMatch[1]);
        }

        return $education;
    }

    /**
     * Parse experience requirement from qualification text.
     *
     * @param string $text
     * @param int $years
     * @return array
     */
    protected function parseExperienceRequirement(string $text, int $years): array
    {
        $title = 'General Experience';

        if (preg_match('/experience\s+in\s+([^\.]+)/i', $text, $titleMatch)) {
            $title = trim($titleMatch[1]);
        }

        return [
            'title' => $title,
            'years' => $years,
            'level' => $years >= 5 ? 'advanced' : ($years >= 3 ? 'intermediate' : 'beginner'),
            'description' => $text
        ];
    }

    /**
     * Extract skills from text.
     *
     * @param string $text
     * @param array &$skillsArray
     * @return void
     */
    protected function extractSkills(string $text, array &$skillsArray): void
    {
        $skillKeywords = [
            'Python' => 'technical',
            'Java' => 'technical',
            'Cloud' => 'technical',
            'Oracle' => 'technical',
            'database' => 'technical',
            'SQL' => 'technical',
            'analytics' => 'technical',
            'AI' => 'technical',
            'ML' => 'technical',
            'DevOps' => 'technical',
            'SaaS' => 'domain',
            'communication' => 'soft',
            'presentation' => 'soft',
            'writing' => 'soft'
        ];

        foreach ($skillKeywords as $skill => $type) {
            if (preg_match('/' . preg_quote($skill, '/') . '/i', $text)) {
                $skillsArray[] = [
                    'name' => $skill,
                    'type' => $type,
                    'level' => 4 // Assume advanced level for required skills
                ];
            }
        }
    }

    /**
     * Create or update job post model with the extracted data.
     *
     * @param \App\Models\JobPost|null $model
     * @param array $jobData
     * @param string $jobPostUrl
     * @return void
     */
    protected function createOrUpdateJobPost($model, array $jobData, string $jobPostUrl): void
    {
        $data = array_merge($jobData, [
            'job_post_url' => $jobPostUrl,
            'job_post_date' => now(),
        ]);

        if ($model) {
            $model->update($data);
        } else {
            $model = \App\Models\JobPost::create(array_merge([
                'user_id' => request()->user()->id,
            ], $data));
        }
    }
}
````

## File: app/Nova/Actions/GenerateApplicationMaterials.php
````php
<?php

namespace App\Nova\Actions;

use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateApplicationMaterials extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $generationService = app(GenerationService::class);

        foreach ($models as $jobPost) {
            try {
                $resume = $generationService->generateResume($jobPost);
                $coverLetter = $generationService->generateCoverLetter($jobPost);
                
                return Action::message("Resume (ID: {$resume->id}) and Cover Letter (ID: {$coverLetter->id}) generated successfully!");
            } catch (\Exception $e) {
                return Action::danger("Failed to generate application materials: {$e->getMessage()}");
            }
        }
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Generate Resume & Cover Letter';
    }
}
````

## File: app/Nova/Actions/GenerateCoverLetter.php
````php
<?php

namespace App\Nova\Actions;

use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateCoverLetter extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $generationService = app(GenerationService::class);

        foreach ($models as $jobPost) {
            try {
                $coverLetter = $generationService->generateCoverLetter($jobPost);
                
                return Action::message("Cover letter generated successfully! ID: {$coverLetter->id}");
            } catch (\Exception $e) {
                return Action::danger("Failed to generate cover letter: {$e->getMessage()}");
            }
        }
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Generate Cover Letter';
    }
}
````

## File: app/Nova/Actions/GenerateResume.php
````php
<?php

namespace App\Nova\Actions;

use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateResume extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $generationService = app(GenerationService::class);

        foreach ($models as $jobPost) {
            try {
                $resume = $generationService->generateResume($jobPost);
                
                return Action::message("Resume generated successfully! ID: {$resume->id}");
            } catch (\Exception $e) {
                return Action::danger("Failed to generate resume: {$e->getMessage()}");
            }
        }
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Generate Resume';
    }
}
````

## File: app/Nova/Actions/ImportJobPostFromContent.php
````php
<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\JobPost;
use App\Services\JobPostAIService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImportJobPostFromContent extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Import Job Post';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Import a job post from URL, HTML, or markdown content';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models): mixed
    {
        try {
            if (!empty($fields->url)) {
                // URL is now just for reference and populating the job_post_url field
                $url = $fields->url;
                
                // If we also have HTML or markdown content, use that
                if (!empty($fields->html_content)) {
                    return $this->importFromHtml($fields->html_content, $url, $fields->api_key);
                } elseif (!empty($fields->markdown_content)) {
                    return $this->importFromMarkdown($fields->markdown_content, $url);
                } else {
                    return $this->importFromUrl($url, $fields->import_type);
                }
            } elseif (!empty($fields->html_content)) {
                return $this->importFromHtml($fields->html_content, null, $fields->api_key);
            } elseif (!empty($fields->markdown_content)) {
                return $this->importFromMarkdown($fields->markdown_content);
            } else {
                return Action::danger('Please provide a URL, HTML content, or markdown content.');
            }
        } catch (Exception $e) {
            Log::error('Error importing job post: ' . $e->getMessage(), [
                'exception' => $e,
                'fields' => $fields->toArray()
            ]);
            return Action::danger('Error importing job post: ' . $e->getMessage());
        }
    }

    /**
     * Import from URL
     */
    protected function importFromUrl($url, $importType)
    {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return Action::danger('Invalid URL provided.');
        }

        // Fetch content from URL
        $response = Http::get($url);

        if (!$response->successful()) {
            return Action::danger('Failed to fetch content from URL: ' . $response->status());
        }

        $content = $response->body();

        // Parse content based on import type
        if ($importType === 'google_jobs') {
            return $this->analyzeJobContent($content, $url);
        } else {
            return Action::danger('Unsupported import type.');
        }
    }

    /**
     * Import from HTML content
     */
    protected function importFromHtml($htmlContent, $url = null, $apiKey = null)
    {
        try {
            // Convert HTML to markdown using the html-to-markdown API
            $markdown = $this->convertHtmlToMarkdown($htmlContent, $url, $apiKey);
            
            if (!$markdown) {
                return Action::danger('Failed to convert HTML to Markdown.');
            }
            
            // Process the markdown content
            return $this->importFromMarkdown($markdown, $url);
        } catch (Exception $e) {
            Log::error('Error converting HTML to Markdown: ' . $e->getMessage(), [
                'exception' => $e,
                'html_length' => strlen($htmlContent)
            ]);
            return Action::danger('Error converting HTML to Markdown: ' . $e->getMessage());
        }
    }

    /**
     * Import from Markdown content
     */
    protected function importFromMarkdown($markdownContent, $url = null)
    {
        return $this->analyzeJobContent($markdownContent, $url);
    }

    /**
     * Convert HTML content to Markdown using html-to-markdown API.
     *
     * @param string $html
     * @param string|null $url
     * @param string|null $apiKey
     * @return string|null
     */
    protected function convertHtmlToMarkdown(string $html, ?string $url = null, ?string $apiKey = null): ?string
    {
        try {
            // If no API key is provided, try to get it from config
            $apiKey = $apiKey ?? config('services.html_to_markdown.api_key', '');
            
            if (empty($apiKey)) {
                throw new Exception('HTML to Markdown API key is required');
            }
            
            $domain = null;
            if ($url) {
                $domain = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
            }
            
            $payload = [
                'html' => $html,
                'plugins' => [
                    'strikethrough' => [],
                    'table' => [],
                ],
            ];
            
            // Add domain if available
            if ($domain) {
                $payload['domain'] = $domain;
            }
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey
            ])->post('https://api.html-to-markdown.com/v1/convert', $payload);

            if ($response->successful()) {
                return $response->json('markdown');
            }
            
            Log::error('HTML to Markdown API error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return null;
        } catch (Exception $e) {
            Log::error('Exception in HTML to Markdown conversion', [
                'message' => $e->getMessage(),
                'exception' => $e
            ]);
            return null;
        }
    }

    /**
     * Analyze job post content using OpenAI
     */
    protected function analyzeJobContent($content, $url = null)
    {
        try {
            // Initialize JobPostAIService
            $aiService = app(JobPostAIService::class);
            
            // Analyze the job post content
            $jobData = $aiService->analyzeJobPost($content);
            
            // Add URL and user ID
            if ($url) {
                $jobData['job_post_url'] = $url;
            }
            
            $jobData['job_post_date'] = now();
            $jobData['user_id'] = Auth::id();
            
            // Create the job post
            return $this->createJobPost($jobData);
            
        } catch (Exception $e) {
            Log::error('Error analyzing job post: ' . $e->getMessage(), [
                'exception' => $e,
                'content_length' => strlen($content)
            ]);
            return Action::danger('Error analyzing job post: ' . $e->getMessage());
        }
    }
    
    /**
     * Create job post from parsed data
     */
    protected function createJobPost($data)
    {
        // Format repeater fields for Nova
        $data = $this->formatRepeaterFields($data);
        
        // Create the job post
        $jobPost = JobPost::create($data);

        return Action::message('Successfully imported job post: ' . $jobPost->job_title);
    }

    /**
     * Format repeater fields for Nova
     * 
     * @param array $data
     * @return array
     */
    protected function formatRepeaterFields(array $data): array
    {
        // Format required skills
        if (isset($data['required_skills']) && is_array($data['required_skills'])) {
            $data['required_skills'] = array_map(function($skill) {
                return [
                    'type' => 'skill-item',
                    'fields' => $skill
                ];
            }, $data['required_skills']);
        }
        
        // Format preferred skills
        if (isset($data['preferred_skills']) && is_array($data['preferred_skills'])) {
            $data['preferred_skills'] = array_map(function($skill) {
                return [
                    'type' => 'skill-item',
                    'fields' => $skill
                ];
            }, $data['preferred_skills']);
        }
        
        // Format required experience
        if (isset($data['required_experience']) && is_array($data['required_experience'])) {
            $data['required_experience'] = array_map(function($exp) {
                return [
                    'type' => 'experience-item',
                    'fields' => $exp
                ];
            }, $data['required_experience']);
        }
        
        // Format required education
        if (isset($data['required_education']) && is_array($data['required_education'])) {
            $data['required_education'] = array_map(function($edu) {
                return [
                    'type' => 'education-item',
                    'fields' => $edu
                ];
            }, $data['required_education']);
        }

        return $data;
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Import Type', 'import_type')
                ->options([
                    'google_jobs' => 'Google Jobs Listing',
                    'other' => 'Other (Generic Parsing)',
                ])
                ->default('google_jobs')
                ->rules('required')
                ->help('Select the type of job listing you are importing'),

            Text::make('URL', 'url')
                ->help('Optional: Job listing URL for reference')
                ->nullable(),

            Textarea::make('HTML Content', 'html_content')
                ->help('Paste the HTML content copied from browser devtools')
                ->rows(10)
                ->nullable(),

            Text::make('API Key', 'api_key')
                ->help('HTML-to-Markdown API Key (required for HTML conversion)')
                ->default(config('services.html_to_markdown.api_key', ''))
                ->nullable(),

            Textarea::make('Markdown Content', 'markdown_content')
                ->help('Or paste the markdown content directly')
                ->rows(10)
                ->nullable(),
        ];
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Import Job Post';
    }
}
````

## File: app/Nova/Actions/RegenerateWithFeedback.php
````php
<?php

namespace App\Nova\Actions;

use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class RegenerateWithFeedback extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $generationService = app(GenerationService::class);

        foreach ($models as $document) {
            try {
                $regenerated = $generationService->regenerateWithFeedback($document, [
                    'feedback' => $fields->feedback
                ]);
                
                return Action::message("Document regenerated successfully!");
            } catch (\Exception $e) {
                return Action::danger("Failed to regenerate document: {$e->getMessage()}");
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Textarea::make('Feedback', 'feedback')
                ->rules('required')
                ->help('Provide feedback on what should be improved in this document.'),
        ];
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Regenerate with Feedback';
    }
}
````

## File: app/Nova/Dashboards/Main.php
````php
<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(): array
    {
        return [
//            new Help,
        ];
    }
}
````

## File: app/Nova/Repeaters/EducationItem.php
````php
<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;

class EducationItem extends Repeatable
{
  /**
     * Get the displayable singular label for the repeatable.
     */
    public static function singularLabel(): string
    {
        return 'Education Requirement';
    }

    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Select::make('Degree Level', 'level')
                ->options([
                    'high_school' => 'High School Diploma',
                    'associate' => 'Associate\'s Degree',
                    'bachelor' => 'Bachelor\'s Degree',
                    'master' => 'Master\'s Degree',
                    'doctorate' => 'Doctorate/PhD',
                    'certificate' => 'Professional Certificate',
                    'other' => 'Other',
                ])
                ->rules('required')
                ->default('bachelor'),

            Text::make('Field of Study', 'field')
                ->help('e.g., Computer Science, Business, etc.')
                ->rules('required'),

            Boolean::make('Is Required', 'is_required')
                ->help('Must have this exact degree or is it flexible?')
                ->default(true),

            Number::make('Minimum GPA', 'min_gpa')
                ->min(0)
                ->max(4.0)
                ->step(0.1)
                ->nullable()
                ->help('Leave blank if not applicable'),

            Textarea::make('Additional Requirements', 'description')
                ->rows(2)
                ->nullable()
                ->help('Any specific requirements or notes about this education'),
        ];
    }
}
````

## File: app/Nova/Repeaters/ExperienceItem.php
````php
<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

class ExperienceItem extends Repeatable
{
    /**
     * Get the displayable singular label for the repeatable.
     */
    public static function singularLabel(): string
    {
        return 'Experience Requirement';
    }


    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Title', 'title')
                ->rules('required'),

            Number::make('Years Required', 'years')
                ->min(0)
                ->step(0.5)
                ->default(1),

            Select::make('Level', 'level')
                ->options([
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                    'expert' => 'Expert',
                ])
                ->default('intermediate'),

            Textarea::make('Description', 'description')
                ->rows(2)
                ->nullable(),
        ];
    }
}
````

## File: app/Nova/Repeaters/SkillItem.php
````php
<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;

class SkillItem extends Repeatable
{
      /**
     * Get the displayable singular label for the repeatable.
     */
    public static function singularLabel(): string
    {
        return 'Skill';
    }


    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
          return [
            Text::make('Name', 'name')
                ->rules('required'),
                
            Select::make('Type', 'type')
                ->options([
                    'technical' => 'Technical',
                    'soft' => 'Soft Skill',
                    'domain' => 'Domain Knowledge',
                    'tool' => 'Tool/Software',
                    'language' => 'Language',
                    'other' => 'Other',
                ])
                ->default('technical'),
                
            Number::make('Proficiency Level', 'level')
                ->min(1)
                ->max(5)
                ->default(3)
                ->help('1 = Beginner, 5 = Expert'),
        ];
    }
}
````

## File: app/Nova/OpenAIPrompt.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class OpenAIPrompt extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\OpenAIPrompt>
     */
    public static $model = \App\Models\OpenAIPrompt::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'OpenAI Prompts';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'OpenAI Prompt';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'type',
        'prompt_template'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),



            Boolean::make('Active')
            ->default(true),

            // Form field
            Select::make('Type')
                ->options([
                    'resume' => 'Resume',
                    'cover_letter' => 'Cover Letter',
                    'analysis' => 'Analysis',
                    'rule_check' => 'Rule Check'
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->onlyOnForms(),

            // Display field
            Badge::make('Type')
                ->map([
                    'resume' => 'info',
                    'cover_letter' => 'success',
                    'analysis' => 'analysis',
                    'rule_check' => 'rule_check',
                ])
                ->addTypes([
                    'rule_check' => 'font-medium text-gray-600 bg-gray-100',
                    'analysis' => 'font-medium text-yellow-600 bg-yellow-100',
                ])
                ->icons([
                    'info' => 'document',
                    'success' => 'mail',
                    'warning' => 'chart-bar',
                    'danger' => 'check-circle',
                    'rule_check' => 'magnifying-glass-circle',
                    'analysis' => 'chart-bar',
                ])
                ->exceptOnForms(),

            Textarea::make('Prompt Template')
                ->alwaysShow()
                ->rules('required'),

            Code::make('Parameters')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),

            // Form field
            Text::make('Model')
                ->default('gpt-4o')
                ->rules('required')
                ->onlyOnForms(),

            // Display field - different colors based on model pricing tiers
            Badge::make('Model')
                ->map([
                    'gpt-4' => 'generic',
                    'gpt-4-32k' => 'generic',
                    'gpt-4-turbo' => 'generic',
                    'gpt-3.5-turbo-16k' => 'generic',
                    'gpt-3.5-turbo' => 'generic',
                    'gpt-4o' => 'generic', // high intelligence
                    'gpt-4o-mini' => 'generic', // high intelligence
                    'chatgpt-4o-latest' => 'success', // high intelligence
                    'o1' => 'warning', // high intelligence
                    'o3-mini' => 'warning', // high intelligence
                    'o1-pro' => 'danger', // high intelligence
                    'gpt-4.5-preview' => 'danger', // high intelligence

                ])
                ->addTypes([
                    'generic' => 'font-medium text-gray-600',
                ])
                ->withIcons()
                ->icons([
                    //     'success' => 'check-circle',
                    //     'info' => 'information-circle',
                    //     'danger' => 'exclamation-circle',
                    //     'warning' => 'exclamation-circle',
                    'generic' => '',
                    'danger' => 'currency-dollar',
                    'warning' => 'currency-dollar',
                    'success' => 'currency-dollar',
                ])
                ->exceptOnForms(),

            // Form field
            Number::make('Temperature')
                ->step(0.1)
                ->min(0)
                ->max(1.0)
                ->default(0.7)
                ->rules('required')
                ->onlyOnForms(),

            // Display field
            Badge::make('Temperature')
                ->resolveUsing(function ($value) {
                    // Categorize temperature into ranges
                    if ($value >= 0.8) return 'very-high';
                    if ($value >= 0.6) return 'high';
                    if ($value >= 0.4) return 'medium';
                    if ($value >= 0.2) return 'low';
                    return 'very-low';
                })
                ->label(function ($value) {
                    // Format the label to show the temperature
                    return $value . ' (' . $this->temperature . ')';
                })
                ->addTypes([
                    'generic' => 'font-medium text-gray-600 bg-gray-100',
                ])
                ->map([
                    'very-high' => 'danger',
                    'high' => 'generic',
                    'medium' => 'generic',
                    'low' => 'warning',
                    'very-low' => 'danger',
                ])
                ->exceptOnForms(),

            // Form field
            Number::make('Max Tokens')
                ->min(100)
                ->default(2000)
                ->rules('required')
                ->onlyOnForms(),

            // Display field
            Badge::make('Max Tokens')
                ->resolveUsing(function ($value) {
                    // Categorize token usage into ranges
                    if ($value > 32000) return 'extreme';
                    if ($value > 16000) return 'very-high';
                    if ($value > 8000) return 'high';
                    if ($value > 4000) return 'medium';
                    return 'low';
                })
                ->addTypes([
                    'generic' => 'font-medium text-gray-600',

                ])
                ->label(function ($value) {
                    // Format the label to show the number of tokens
                    return $value . ' (' . $this->max_tokens . ')';
                })
                ->map([
                    'extreme' => 'danger',
                    'very-high' => 'danger',
                    'high' => 'warning',
                    'medium' => 'generic',
                    'low' => 'generic',
                ])
                ->icons([
                    'danger' => 'exclamation-circle',
                    'warning' => 'exclamation',
                    'success' => 'check-circle',
                    'info' => 'information-circle',
                    'generic' => '',
                ])
                ->exceptOnForms(),


        ];
    }
}
````

## File: app/Nova/Resource.php
````php
<?php

namespace App\Nova;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Scout\Builder as ScoutBuilder;

abstract class Resource extends NovaResource
{
    /**
     * Build an "index" query for the given resource.
     */
    public static function indexQuery(NovaRequest $request, Builder $query): Builder
    {
        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     */
    public static function scoutQuery(NovaRequest $request, ScoutBuilder $query): ScoutBuilder
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     */
    public static function detailQuery(NovaRequest $request, Builder $query): Builder
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     */
    public static function relatableQuery(NovaRequest $request, Builder $query): Builder
    {
        return parent::relatableQuery($request, $query);
    }
}
````

## File: app/Nova/Rule.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class Rule extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Rule>
     */
    public static $model = \App\Models\Rule::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'description'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->rules('required')
                ->hideFromIndex(),

            // Form field
            Select::make('Type')
                ->options([
                    'resume' => 'Resume',
                    'cover_letter' => 'Cover Letter',
                    'both' => 'Both'
                ])
                ->displayUsingLabels()
                ->default('both')
                ->rules('required')
                ->onlyOnForms(),

            // Display field
            Badge::make('Type')
                ->map([
                    'resume' => 'info',
                    'cover_letter' => 'success',
                    'both' => 'warning',
                ])
                ->icons([
                    'info' => 'document',
                    'success' => 'mail',
                    'warning' => 'duplicate',
                ])
                ->exceptOnForms(),

            Text::make('Source')
                ->nullable(),

            // Form field
            Number::make('Importance')
                ->min(1)
                ->max(10)
                ->default(5)
                ->help('Rate importance from 1-10')
                ->onlyOnForms(),

            // Display field
            Badge::make('Importance')
                ->map([
                    '1' => 'info',
                    '2' => 'info',
                    '3' => 'success',
                    '4' => 'success',
                    '5' => 'success',
                    '6' => 'warning',
                    '7' => 'warning',
                    '8' => 'warning',
                    '9' => 'danger',
                    '10' => 'danger',
                ])
                ->exceptOnForms(),

            Code::make('Validation Logic')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
}
````

## File: app/Nova/ThreadSession.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ThreadSession extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ThreadSession>
     */
    public static $model = \App\Models\ThreadSession::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'thread_id', 'assistant_id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            
            BelongsTo::make('User'),
            
            BelongsTo::make('Job Post', 'jobPost', JobPost::class),
            
            Badge::make('Type')
                ->map([
                    'resume' => 'info',
                    'cover_letter' => 'success',
                    'validation' => 'warning',
                ]),
                
            Badge::make('Status')
                ->map([
                    'created' => 'info',
                    'processing' => 'warning',
                    'completed' => 'success',
                    'failed' => 'danger',
                ]),
                
            Text::make('Assistant ID', 'assistant_id')
                ->hideFromIndex(),
                
            Text::make('Thread ID', 'thread_id')
                ->hideFromIndex(),
                
            DateTime::make('Completed At')
                ->hideFromIndex(),
                
            Textarea::make('Error')
                ->hideFromIndex()
                ->onlyOnDetail(),
                
            Code::make('Content')
                ->language('markdown')
                ->hideFromIndex()
                ->onlyOnDetail(),
                
            Code::make('Metrics')
                ->language('json')
                ->hideFromIndex()
                ->onlyOnDetail(),
        ];
    }
}
````

## File: app/Providers/HorizonServiceProvider.php
````php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user) {
            return in_array($user->email, [
                'nathaniel@attentiv.dev',
            ]);
        });
    }
}
````

## File: app/Providers/TelescopeServiceProvider.php
````php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        Telescope::filter(function (IncomingEntry $entry) use ($isLocal) {
            return $isLocal ||
                $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                'nathaniel@attentiv.dev'
            ]);
        });
    }
}
````

## File: app/Services/AssistantsService.php
````php
<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class AssistantsService
{
    /**
     * The available assistant types
     */
    const TYPE_RESUME = 'resume';
    const TYPE_COVER_LETTER = 'cover_letter';
    const TYPE_VALIDATOR = 'validator';
    
    /**
     * Cache keys for storing assistant IDs
     */
    const CACHE_KEY_RESUME = 'openai_assistant_resume';
    const CACHE_KEY_COVER_LETTER = 'openai_assistant_cover_letter';
    const CACHE_KEY_VALIDATOR = 'openai_assistant_validator';
    
    /**
     * Get or create an assistant by type
     *
     * @param string $type
     * @return string The assistant ID
     */
    public function getOrCreateAssistant(string $type): string
    {
        $cacheKey = $this->getCacheKeyForType($type);
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($type) {
            try {
                $assistantId = $this->createAssistant($type);
                Log::info("Created new OpenAI assistant", ['type' => $type, 'id' => $assistantId]);
                return $assistantId;
            } catch (Exception $e) {
                Log::error("Failed to create OpenAI assistant", [
                    'type' => $type,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }
    
    /**
     * Create a new assistant with the appropriate configuration
     *
     * @param string $type
     * @return string The assistant ID
     */
    private function createAssistant(string $type): string
    {
        $config = $this->getAssistantConfig($type);
        
        $response = OpenAI::assistants()->create([
            'name' => $config['name'],
            'instructions' => $config['instructions'],
            'tools' => $config['tools'],
            'model' => $config['model'] ?? 'gpt-4o',
        ]);
        
        return $response->id;
    }
    
    /**
     * Get the configuration for a specific assistant type
     *
     * @param string $type
     * @return array
     */
    private function getAssistantConfig(string $type): array
    {
        return match($type) {
            self::TYPE_RESUME => [
                'name' => 'Resume Generator',
                'instructions' => $this->getResumeInstructions(),
                'tools' => [
                    ['type' => 'retrieval'],
                    ['type' => 'code_interpreter'],
                ],
                'model' => 'gpt-4o',
            ],
            self::TYPE_COVER_LETTER => [
                'name' => 'Cover Letter Generator',
                'instructions' => $this->getCoverLetterInstructions(),
                'tools' => [
                    ['type' => 'retrieval'],
                    ['type' => 'code_interpreter'],
                ],
                'model' => 'gpt-4o',
            ],
            self::TYPE_VALIDATOR => [
                'name' => 'Document Validator',
                'instructions' => $this->getValidatorInstructions(),
                'tools' => [
                    ['type' => 'retrieval'],
                    ['type' => 'code_interpreter'],
                    ['type' => 'function'],
                ],
                'model' => 'gpt-4o',
            ],
            default => throw new Exception("Unknown assistant type: {$type}")
        };
    }
    
    /**
     * Get cache key for the assistant type
     */
    private function getCacheKeyForType(string $type): string
    {
        return match($type) {
            self::TYPE_RESUME => self::CACHE_KEY_RESUME,
            self::TYPE_COVER_LETTER => self::CACHE_KEY_COVER_LETTER,
            self::TYPE_VALIDATOR => self::CACHE_KEY_VALIDATOR,
            default => throw new Exception("Unknown assistant type: {$type}")
        };
    }
    
    /**
     * Get detailed instructions for the resume assistant
     */
    private function getResumeInstructions(): string
    {
        return <<<EOT
You are an expert resume writer specialized in creating tailored, ATS-optimized resumes that match specific job descriptions.

Your responsibilities:
1. Analyze job descriptions to identify key requirements, skills, and qualifications
2. Match the user's experience and skills to the job requirements
3. Create concise, impactful bullet points that highlight quantifiable achievements
4. Ensure proper formatting and ATS compatibility (single-column layouts, standard fonts)
5. Keep the resume between 475-600 words for optimal length
6. Include LinkedIn profile and relevant contact information
7. Avoid generic buzzwords and clichés
8. Prioritize concrete metrics and results over vague statements
9. Tailor the content to emphasize relevant experience for the specific position
10. Format dates and locations consistently
11. Optimize keyword usage for ATS without keyword stuffing

Always follow these formatting rules:
- Single-column layout for optimal ATS compatibility
- Standard professional fonts (Arial, Calibri, Times New Roman)
- Consistent spacing and bullet formatting
- No graphics, tables, or images
- Include a concise professional summary that positions the candidate as the "right fit"
- Organize information in order of relevance to the job description

Focus on making the candidate appear as the perfect "puzzle piece" to fill the team's current gaps.
EOT;
    }
    
    /**
     * Get detailed instructions for the cover letter assistant
     */
    private function getCoverLetterInstructions(): string
    {
        return <<<EOT
You are an expert cover letter writer specialized in creating compelling, personalized cover letters that complement resumes and help candidates stand out.

Your responsibilities:
1. Create a strong hook that connects the candidate to the company in the opening paragraph
2. Address the hiring manager by name whenever possible (or use a targeted greeting)
3. Every sentence should have a clear purpose, focusing on skills relevant to the job
4. Make authentic connections between the candidate's experience and company needs
5. Demonstrate knowledge of the company's mission, values, or recent achievements
6. Highlight 2-3 key achievements that directly relate to the job requirements
7. Keep the cover letter to one page maximum (450-750 words)
8. Ensure proper formatting with sufficient white space
9. Include a strong closing paragraph that reiterates interest and suggests next steps
10. Maintain professional tone while showing personality

Always follow these best practices:
- Address specific pain points or needs mentioned in the job posting
- Use quantifiable metrics when possible to demonstrate impact
- Avoid generic statements that could apply to any company
- Include specific references to the company's products, services, or values
- Maintain a conversational yet professional tone
- Avoid unnecessary jargon unless it's industry-specific and relevant
- End with a clear call to action that expresses enthusiasm for the role

Focus on creating an emotional connection and positioning the candidate as the solution to the company's needs.
EOT;
    }
    
    /**
     * Get detailed instructions for the validator assistant
     */
    private function getValidatorInstructions(): string
    {
        return <<<EOT
You are an expert document validator specialized in ensuring resumes and cover letters follow best practices and are optimized for both human readers and ATS systems.

Your responsibilities:
1. Check for formatting issues that might cause ATS problems
2. Ensure proper keyword usage that aligns with the job description
3. Validate that achievements are properly quantified
4. Identify and flag generic buzzwords or clichés
5. Verify appropriate document length (475-600 words for resumes, 450-750 for cover letters)
6. Assess overall impact and persuasiveness of content
7. Check for typos, grammatical errors, and inconsistencies
8. Verify that all sections are properly formatted and organized
9. Ensure contact information is complete and properly formatted
10. Validate that the document is tailored to the specific job opportunity

For each validation check, provide:
- A yes/no assessment of compliance
- A brief explanation of why it passes or fails
- A suggested correction for any issues found
- A score from 1-10 for each criterion
- An overall document score

The validation will be comprehensive and actionable, providing specific feedback that can be used to improve the document before submission.
EOT;
    }
    
    /**
     * Create a new thread for an assistant interaction
     *
     * @return string The thread ID
     */
    public function createThread(): string
    {
        $response = OpenAI::threads()->create([]);
        return $response->id;
    }
    
    /**
     * Add a message to a thread
     *
     * @param string $threadId
     * @param string $content
     * @param array $files Optional file IDs to attach
     * @return string The message ID
     */
    public function addMessage(string $threadId, string $content, array $files = []): string
    {
        $params = [
            'role' => 'user',
            'content' => $content,
        ];
        
        if (!empty($files)) {
            $params['file_ids'] = $files;
        }
        
        $response = OpenAI::threads()->messages()->create($threadId, $params);
        
        return $response->id;
    }
    
    /**
     * Run an assistant on a thread and wait for completion
     *
     * @param string $threadId
     * @param string $assistantId
     * @param array $instructions Optional additional instructions
     * @return array The assistant's response messages
     */
    public function runAssistant(string $threadId, string $assistantId, array $instructions = []): array
    {
        // Create the run
        $run = OpenAI::threads()->runs()->create($threadId, [
            'assistant_id' => $assistantId,
            'instructions' => $instructions['instructions'] ?? null,
        ]);
        
        // Poll for completion (in production, you'd use a background job)
        $maxAttempts = 60; // 10 minutes max (10s intervals)
        $attempts = 0;
        
        do {
            sleep(10); // Wait 10 seconds between checks
            $run = OpenAI::threads()->runs()->retrieve($threadId, $run->id);
            $attempts++;
            
            // Handle required actions if needed (function calling, etc.)
            if ($run->status === 'requires_action') {
                // Process required actions (would need implementation based on your functions)
                // This is a simplified example
                $this->handleRequiredAction($threadId, $run);
            }
            
        } while ($run->status !== 'completed' && $run->status !== 'failed' && $attempts < $maxAttempts);
        
        if ($run->status !== 'completed') {
            throw new Exception("Assistant run failed or timed out: {$run->status}");
        }
        
        // Get the messages (newest first)
        $messages = OpenAI::threads()->messages()->list($threadId, ['limit' => 10]);
        
        // Filter to only get assistant messages from this run
        $responseMessages = [];
        foreach ($messages->data as $message) {
            if ($message->role === 'assistant' && $message->runId === $run->id) {
                $responseMessages[] = $message;
            }
        }
        
        return $responseMessages;
    }
    
    /**
     * Handle required actions for function calling
     *
     * @param string $threadId
     * @param object $run
     * @return void
     */
    private function handleRequiredAction(string $threadId, object $run): void
    {
        if ($run->requiredAction->type !== 'submit_tool_outputs') {
            return;
        }
        
        $toolOutputs = [];
        
        foreach ($run->requiredAction->submitToolOutputs->toolCalls as $toolCall) {
            // Example implementation - would need to be adapted to your specific functions
            $function = $toolCall->function->name;
            $arguments = json_decode($toolCall->function->arguments, true);
            
            // Call your function handling logic here
            $result = $this->callFunction($function, $arguments);
            
            $toolOutputs[] = [
                'tool_call_id' => $toolCall->id,
                'output' => json_encode($result),
            ];
        }
        
        // Submit the tool outputs
        OpenAI::threads()->runs()->submitToolOutputs($threadId, $run->id, [
            'tool_outputs' => $toolOutputs,
        ]);
    }
    
    /**
     * Call a function based on name and arguments
     *
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    private function callFunction(string $function, array $arguments): mixed
    {
        // Implementation would depend on your specific function needs
        // This is just a placeholder
        return match($function) {
            'validateResume' => ['score' => 8, 'feedback' => 'Good resume overall.'],
            'validateCoverLetter' => ['score' => 7, 'feedback' => 'Good cover letter, but could improve the opening.'],
            default => ['error' => "Unknown function: {$function}"]
        };
    }
}
````

## File: app/Services/EmbeddingsService.php
````php
<?php

namespace App\Services;

use App\Models\JobPost;
use App\Models\User;
use App\Models\Skill;
use App\Models\WorkExperience;
use App\Models\SkillEmbedding;
use App\Models\JobRequirementEmbedding;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Exception;

class EmbeddingsService
{
    /**
     * Generate embeddings for a text
     *
     * @param string $text
     * @return array
     */
    public function generateEmbedding(string $text): array
    {
        try {
            $response = OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $text,
            ]);

            return $response->embeddings[0]->embedding;

        } catch (Exception $e) {
            Log::error("Failed to generate embedding", [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Generate and store embeddings for a user's skills
     *
     * @param User $user
     * @return array
     */
    public function generateUserSkillEmbeddings(User $user): array
    {
        $results = [];

        foreach ($user->skills as $skill) {
            try {
                // Create a rich description of the skill
                $skillDescription = $this->createSkillDescription($skill);

                // Generate embedding
                $embedding = $this->generateEmbedding($skillDescription);

                // Store the embedding
                $skillEmbedding = SkillEmbedding::updateOrCreate(
                    ['skill_id' => $skill->id],
                    [
                        'user_id' => $user->id,
                        'embedding' => json_encode($embedding),
                        'skill_description' => $skillDescription,
                    ]
                );

                $results[$skill->id] = [
                    'status' => 'success',
                    'embedding_id' => $skillEmbedding->id,
                ];

            } catch (Exception $e) {
                Log::error("Failed to generate skill embedding", [
                    'skill_id' => $skill->id,
                    'error' => $e->getMessage(),
                ]);

                $results[$skill->id] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Generate and store embeddings for job requirements
     *
     * @param JobPost $jobPost
     * @return array
     */
    public function generateJobRequirementEmbeddings(JobPost $jobPost): array
    {
        $results = [];

        // Extract requirements from job post
        $requirementTypes = [
            'skills' => $jobPost->required_skills ?? [],
            'experience' => $jobPost->required_experience ?? [],
            'education' => $jobPost->required_education ?? [],
        ];

        foreach ($requirementTypes as $type => $requirements) {
            if (empty($requirements)) {
                continue;
            }

            // Handle both array of strings and array of arrays with 'fields'
            $requirementItems = [];
            foreach ($requirements as $requirement) {
                if (is_string($requirement)) {
                    $requirementItems[] = $requirement;
                } elseif (is_array($requirement) && isset($requirement['fields']['name'])) {
                    $requirementItems[] = $requirement['fields']['name'];

                    // Add description if available
                    if (isset($requirement['fields']['description']) && !empty($requirement['fields']['description'])) {
                        $requirementItems[] = $requirement['fields']['description'];
                    }
                }
            }

            // Create combined text for this requirement type
            $requirementText = "Job {$type} requirements: " . implode(", ", $requirementItems);

            try {
                // Generate embedding
                $embedding = $this->generateEmbedding($requirementText);

                // Store the embedding
                $requirementEmbedding = JobRequirementEmbedding::updateOrCreate(
                    [
                        'job_post_id' => $jobPost->id,
                        'requirement_type' => $type,
                    ],
                    [
                        'embedding' => json_encode($embedding),
                        'requirement_text' => $requirementText,
                    ]
                );

                $results[$type] = [
                    'status' => 'success',
                    'embedding_id' => $requirementEmbedding->id,
                ];

            } catch (Exception $e) {
                Log::error("Failed to generate job requirement embedding", [
                    'job_post_id' => $jobPost->id,
                    'requirement_type' => $type,
                    'error' => $e->getMessage(),
                ]);

                $results[$type] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        // Also generate an embedding for the entire job description
        try {
            $embedding = $this->generateEmbedding($jobPost->job_description);

            $requirementEmbedding = JobRequirementEmbedding::updateOrCreate(
                [
                    'job_post_id' => $jobPost->id,
                    'requirement_type' => 'full_description',
                ],
                [
                    'embedding' => json_encode($embedding),
                    'requirement_text' => $jobPost->job_description,
                ]
            );

            $results['full_description'] = [
                'status' => 'success',
                'embedding_id' => $requirementEmbedding->id,
            ];

        } catch (Exception $e) {
            Log::error("Failed to generate job description embedding", [
                'job_post_id' => $jobPost->id,
                'error' => $e->getMessage(),
            ]);

            $results['full_description'] = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }

        return $results;
    }

    /**
     * Create a rich description of a skill
     *
     * @param Skill $skill
     * @return string
     */
    private function createSkillDescription(Skill $skill): string
    {
        $description = "Skill: {$skill->name}";

        // Add type
        $description .= "\nType: {$skill->type}";

        // Add proficiency
        $description .= "\nProficiency: {$skill->proficiency}/10";

        // Add years of experience
        if ($skill->years_experience > 0) {
            $description .= "\nExperience: {$skill->years_experience} years";
        }

        // Find work experiences that might be related to this skill
        $relatedExperiences = WorkExperience::where('user_id', $skill->user_id)
            ->where(function ($query) use ($skill) {
                $query->whereJsonContains('skills_used', $skill->name)
                    ->orWhere('description', 'like', "%{$skill->name}%");
            })
            ->get();

        if ($relatedExperiences->isNotEmpty()) {
            $description .= "\n\nRelated work experience:";

            foreach ($relatedExperiences as $experience) {
                $description .= "\n- {$experience->position} at {$experience->company_name}";

                // Add achievements related to this skill
                if (!empty($experience->achievements)) {
                    foreach ($experience->achievements as $achievement => $achievementDesc) {
                        if (stripos($achievement, $skill->name) !== false ||
                            stripos($achievementDesc, $skill->name) !== false) {
                            $description .= "\n  Achievement: {$achievement} - {$achievementDesc}";
                        }
                    }
                }
            }
        }

        return $description;
    }

    /**
     * Find skill matches for job requirements
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return array
     */
    public function findSkillMatches(User $user, JobPost $jobPost): array
    {
        $matches = [];

        // Make sure embeddings exist
        $this->generateUserSkillEmbeddings($user);
        $this->generateJobRequirementEmbeddings($jobPost);

        // Get job requirement embeddings
        $jobRequirementEmbeddings = JobRequirementEmbedding::where('job_post_id', $jobPost->id)->get();

        // Get user skill embeddings
        $userSkillEmbeddings = SkillEmbedding::where('user_id', $user->id)->get();

        foreach ($jobRequirementEmbeddings as $requirementEmbedding) {
            $requirementVector = json_decode($requirementEmbedding->embedding, true);
            $requirementType = $requirementEmbedding->requirement_type;

            $matches[$requirementType] = [];

            foreach ($userSkillEmbeddings as $skillEmbedding) {
                $skillVector = json_decode($skillEmbedding->embedding, true);

                // Calculate cosine similarity
                $similarity = $this->cosineSimilarity($requirementVector, $skillVector);

                // Get the skill
                $skill = Skill::find($skillEmbedding->skill_id);

                $matches[$requirementType][] = [
                    'skill_id' => $skillEmbedding->skill_id,
                    'skill_name' => $skill ? $skill->name : 'Unknown',
                    'similarity' => $similarity,
                    'proficiency' => $skill ? $skill->proficiency : 0,
                    'years_experience' => $skill ? $skill->years_experience : 0,
                ];
            }

            // Sort by similarity (highest first)
            usort($matches[$requirementType], function ($a, $b) {
                return $b['similarity'] <=> $a['similarity'];
            });

            // Keep only top matches (threshold: 0.75)
            $matches[$requirementType] = array_filter($matches[$requirementType], function ($match) {
                return $match['similarity'] >= 0.75;
            });
        }

        return $matches;
    }

    /**
     * Calculate cosine similarity between two vectors
     *
     * @param array $a
     * @param array $b
     * @return float
     */
    private function cosineSimilarity(array $a, array $b): float
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($a); $i++) {
            $dotProduct += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        if ($normA == 0 || $normB == 0) {
            return 0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Generate job-specific recommendations based on embeddings analysis
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return array
     */
    public function generateRecommendations(User $user, JobPost $jobPost): array
    {
        // Find skill matches
        $matches = $this->findSkillMatches($user, $jobPost);

        // Prepare data for OpenAI
        $matchesJson = json_encode($matches, JSON_PRETTY_PRINT);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'temperature' => 0.4,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a job application expert providing strategic recommendations for candidates based on semantic skill matching.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Based on this semantic skill matching analysis, provide strategic recommendations for the candidate's resume and cover letter:

                        # Job Details
                        Company: {$jobPost->company_name}
                        Position: {$jobPost->job_title}

                        # Skill Matching Analysis
                        {$matchesJson}

                        Provide recommendations in JSON format with these categories:
                        1. key_skills_to_emphasize - list of skills to highlight based on strong matches
                        2. skills_to_reframe - skills with moderate match that should be reframed to better align with job requirements
                        3. gap_mitigation_strategies - ways to address skill gaps with related experience or transferable skills
                        4. achievements_to_highlight - types of achievements that would resonate most with this job
                        5. keywords_to_include - specific terms to include
                        6. overall_match_assessment - brief assessment of overall fit"
                    ]
                ]
            ]);

            $content = $response->choices[0]->message->content;

            // Parse JSON response
            $recommendations = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // If the response isn't valid JSON, try to extract it
                preg_match('/```json(.*?)```/s', $content, $matches);
                if (isset($matches[1])) {
                    $recommendations = json_decode(trim($matches[1]), true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new Exception("Failed to parse recommendations JSON");
                    }
                } else {
                    throw new Exception("Failed to parse recommendations JSON");
                }
            }

            return $recommendations;

        } catch (Exception $e) {
            Log::error("Failed to generate recommendations", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id,
                'error' => $e->getMessage(),
            ]);

            // Return basic recommendations if generation fails
            return [
                'key_skills_to_emphasize' => [],
                'skills_to_reframe' => [],
                'gap_mitigation_strategies' => [],
                'achievements_to_highlight' => [],
                'keywords_to_include' => [],
                'overall_match_assessment' => 'Unable to generate detailed recommendations.',
            ];
        }
    }
}
````

## File: app/Services/JobPostAIService.php
````php
<?php

namespace App\Services;

use App\Models\OpenAIPrompt;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobPostAIService
{
    /**
     * The OpenAI API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The OpenAI API URL
     *
     * @var string
     */
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');

        if (empty($this->apiKey)) {
            throw new Exception('OpenAI API key is not configured');
        }
    }

    /**
     * Analyze a job post using OpenAI
     *
     * @param string $content The job post content (HTML or markdown)
     * @return array The structured job data
     * @throws Exception If there is an error
     */
    public function analyzeJobPost(string $content): array
    {
        // Find the Job Post Analysis prompt
        $prompt = OpenAIPrompt::where('name', 'Job Post Analysis')
            ->where('type', 'analysis')
            ->where('active', true)
            ->first();

        if (!$prompt) {
            throw new Exception('Job Post Analysis prompt not found. Please run the OpenAIPromptSeeder.');
        }

        // Generate completion and parse result
        $result = $this->generateCompletion($prompt, ['job_content' => $content]);
        $jobData = json_decode($result, true);

        if (!$jobData || json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid JSON response from OpenAI', [
                'response' => $result,
                'error' => json_last_error_msg()
            ]);

            throw new Exception('Failed to parse OpenAI response: ' . json_last_error_msg());
        }

        return $jobData;
    }

    /**
     * Generate a completion using OpenAI API
     *
     * @param OpenAIPrompt $prompt The prompt to use
     * @param array $parameters The parameters to replace in the prompt
     * @return string The generated text
     * @throws Exception If there is an error
     */
    protected function generateCompletion(OpenAIPrompt $prompt, array $parameters): string
    {
        set_time_limit(0); // Disable time limit for long requests

        // Replace placeholders in the prompt template
        $promptText = $this->replacePlaceholders($prompt->prompt_template, $parameters);

        // Prepare the request payload
        $payload = [
            'model' => $prompt->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $promptText],
            ],
            'temperature' => (float) $prompt->temperature,
            'max_tokens' => (int) $prompt->max_tokens,
        ];

        // Log the request (omit the API key for security)
        Log::info('OpenAI Request', [
            'model' => $prompt->model,
            'max_tokens' => $prompt->max_tokens,
            'temperature' => $prompt->temperature,
        ]);

        // Make the API request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post($this->apiUrl, $payload);

        // Check if the request was successful
        if (!$response->successful()) {
            $error = $response->json();
            Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'error' => $error,
            ]);

            throw new Exception('OpenAI API Error: ' . ($error['error']['message'] ?? 'Unknown error'));
        }

        // Extract and return the response text
        $responseData = $response->json();
        $content = $responseData['choices'][0]['message']['content'] ?? '';

        // Remove any JSON code block markers if present (```json and ```)
        $content = preg_replace('/```json\s*|\s*```/', '', $content);

        return trim($content);
    }

    /**
     * Replace placeholders in the prompt template
     *
     * @param string $template
     * @param array $parameters
     * @return string
     */
    protected function replacePlaceholders(string $template, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $template = str_replace($placeholder, $value, $template);
        }

        return $template;
    }
}
````

## File: app/Services/PDFService.php
````php
<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Resume;
use App\Models\CoverLetter;
use Illuminate\Support\Facades\Storage;

class PDFService
{
    /**
     * Generate a PDF file for a resume
     * 
     * @param Resume $resume
     * @return string The file path
     */
    public function generateResumePDF(Resume $resume)
    {
        $pdf = PDF::loadView('pdfs.resume', [
            'resume' => $resume,
            'user' => $resume->user,
            'jobPost' => $resume->jobPost,
            'content' => $this->formatContentForPDF($resume->content)
        ]);
        
        $filename = 'resume_' . $resume->id . '_' . time() . '.pdf';
        $path = 'pdfs/' . $filename;
        
        // Save to storage
        Storage::put('public/' . $path, $pdf->output());
        
        // Update the resume
        $resume->update([
            'file_path' => $path,
        ]);
        
        return $path;
    }
    
    /**
     * Generate a PDF file for a cover letter
     * 
     * @param CoverLetter $coverLetter
     * @return string The file path
     */
    public function generateCoverLetterPDF(CoverLetter $coverLetter)
    {
        $pdf = PDF::loadView('pdfs.cover_letter', [
            'coverLetter' => $coverLetter,
            'user' => $coverLetter->user,
            'jobPost' => $coverLetter->jobPost,
            'content' => $this->formatContentForPDF($coverLetter->content)
        ]);
        
        $filename = 'cover_letter_' . $coverLetter->id . '_' . time() . '.pdf';
        $path = 'pdfs/' . $filename;
        
        // Save to storage
        Storage::put('public/' . $path, $pdf->output());
        
        // Update the cover letter
        $coverLetter->update([
            'file_path' => $path,
        ]);
        
        return $path;
    }
    
    /**
     * Format content for PDF rendering
     * 
     * @param string $content
     * @return string
     */
    protected function formatContentForPDF(string $content)
    {
        // Convert markdown to HTML if needed
        if ($this->isMarkdown($content)) {
            return $this->markdownToHtml($content);
        }
        
        // Format line breaks
        $content = nl2br($content);
        
        return $content;
    }
    
    /**
     * Check if content appears to be in markdown format
     * 
     * @param string $content
     * @return bool
     */
    protected function isMarkdown(string $content)
    {
        $markdownIndicators = [
            '# ', '## ', '### ', '#### ', '##### ', '###### ', // Headers
            '- ', '* ', '+ ', '1. ', '2. ', // Lists
            '```', '~~~', // Code blocks
            '[', '![', // Links and images
            '|', '---', // Tables and horizontal rules
            '_', '**', '~~', '`' // Emphasis and inline code
        ];
        
        foreach ($markdownIndicators as $indicator) {
            if (strpos($content, $indicator) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Convert markdown to HTML
     * 
     * @param string $markdown
     * @return string
     */
    protected function markdownToHtml(string $markdown)
    {
        // Use Laravel's built-in Str::markdown helper
        return \Illuminate\Support\Str::markdown($markdown);
    }
}
````

## File: app/Services/PromptEngineeringService.php
````php
<?php

namespace App\Services;

use App\Models\JobPost;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Exception;

class PromptEngineeringService
{
    /**
     * Implement the multi-step workflow for generation
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param string $type
     * @return array
     */
    public function generateWithMultiStepWorkflow(JobPost $jobPost, User $user, string $type): array
    {
        // Step 1: Extract key requirements from job description
        $requirements = $this->extractKeyRequirements($jobPost);

        // Step 2: Match user skills to requirements
        $matchedSkills = $this->matchUserSkillsToRequirements($user, $requirements);

        // Step 3: Generate content with tailored emphasis
        $variations = $this->generateContentVariations($jobPost, $user, $matchedSkills, $type);

        // Step 4: Validate against rules and refine
        $validatedResults = $this->validateAndRefine($variations, $jobPost, $type);

        return $validatedResults;
    }

    /**
     * Extract key requirements from job description
     *
     * @param JobPost $jobPost
     * @return array
     */
    private function extractKeyRequirements(JobPost $jobPost): array
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'temperature' => 0.2,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a job analysis expert. Extract key requirements from job descriptions, including explicit and implicit requirements.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Analyze the following job description and extract key requirements in JSON format:

                        Company: {$jobPost->company_name}
                        Position: {$jobPost->job_title}

                        {$jobPost->job_description}

                        Format your response as valid JSON with these categories:
                        1. Hard Skills - technical abilities required
                        2. Soft Skills - interpersonal and character traits
                        3. Experience - required work history and background
                        4. Education - required degrees or certifications
                        5. Implicit Requirements - not explicitly stated but implied
                        6. Keywords - important terms that should be included

                        For each requirement, include a 'priority' score (1-10) indicating how important it seems in the job post."
                    ]
                ]
            ]);

            $content = $response->choices[0]->message->content;

            // Parse JSON response
            $requirementsJson = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // If the response isn't valid JSON, try to extract it
                preg_match('/```json(.*?)```/s', $content, $matches);
                if (isset($matches[1])) {
                    $requirementsJson = json_decode(trim($matches[1]), true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new Exception("Failed to parse requirements JSON");
                    }
                } else {
                    throw new Exception("Failed to parse requirements JSON");
                }
            }

            return $requirementsJson;

        } catch (Exception $e) {
            Log::error("Failed to extract requirements", [
                'job_post_id' => $jobPost->id,
                'error' => $e->getMessage(),
            ]);

            // Return a basic structure if extraction fails
            return [
                'hard_skills' => [],
                'soft_skills' => [],
                'experience' => [],
                'education' => [],
                'implicit_requirements' => [],
                'keywords' => [],
            ];
        }
    }

    /**
     * Match user skills to job requirements
     *
     * @param User $user
     * @param array $requirements
     * @return array
     */
    private function matchUserSkillsToRequirements(User $user, array $requirements): array
    {
        // Gather user skills
        $userSkills = $user->skills()->get()->map(function ($skill) {
            return [
                'name' => $skill->name,
                'type' => $skill->type,
                'proficiency' => $skill->proficiency,
                'years' => $skill->years_experience,
            ];
        })->toArray();

        // Get user experience
        $userExperience = $user->workExperiences()->get()->map(function ($exp) {
            return [
                'position' => $exp->position,
                'company' => $exp->company_name,
                'duration' => $exp->current_job ?
                    now()->diffInYears($exp->start_date) :
                    $exp->end_date->diffInYears($exp->start_date),
                'description' => $exp->description,
                'achievements' => $exp->achievements,
            ];
        })->toArray();

        // Get user education
        $userEducation = $user->education()->get()->map(function ($edu) {
            return [
                'degree' => $edu->degree,
                'field' => $edu->field_of_study,
                'institution' => $edu->institution,
            ];
        })->toArray();

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'temperature' => 0.3,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a skilled job application analyst. Match candidate profiles to job requirements and identify strengths and gaps.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Match the following candidate profile to the job requirements and provide an analysis of strengths and gaps:

                        # Job Requirements
                        " . json_encode($requirements, JSON_PRETTY_PRINT) . "

                        # Candidate Profile
                        ## Skills
                        " . json_encode($userSkills, JSON_PRETTY_PRINT) . "

                        ## Experience
                        " . json_encode($userExperience, JSON_PRETTY_PRINT) . "

                        ## Education
                        " . json_encode($userEducation, JSON_PRETTY_PRINT) . "

                        Return your analysis as a JSON object with these categories:
                        1. matched_skills - skills that match requirements (with match score 1-10)
                        2. skill_gaps - skills the candidate lacks or needs to improve
                        3. experience_matches - experience that aligns with requirements
                        4. experience_gaps - missing or insufficient experience
                        5. education_matches - education that meets requirements
                        6. education_gaps - missing or insufficient education
                        7. overall_match_score - overall match percentage (0-100)
                        8. emphasis_suggestions - areas to emphasize in resume/cover letter"
                    ]
                ]
            ]);

            $content = $response->choices[0]->message->content;

            // Parse JSON response
            $matchAnalysis = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // If the response isn't valid JSON, try to extract it
                preg_match('/```json(.*?)```/s', $content, $matches);
                if (isset($matches[1])) {
                    $matchAnalysis = json_decode(trim($matches[1]), true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new Exception("Failed to parse match analysis JSON");
                    }
                } else {
                    throw new Exception("Failed to parse match analysis JSON");
                }
            }

            return $matchAnalysis;

        } catch (Exception $e) {
            Log::error("Failed to match skills to requirements", [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            // Return a basic structure if matching fails
            return [
                'matched_skills' => [],
                'skill_gaps' => [],
                'experience_matches' => [],
                'experience_gaps' => [],
                'education_matches' => [],
                'education_gaps' => [],
                'overall_match_score' => 50,
                'emphasis_suggestions' => [],
            ];
        }
    }

    /**
     * Generate multiple content variations (Power of Three technique)
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param array $matchedSkills
     * @param string $type
     * @return array
     */
    private function generateContentVariations(JobPost $jobPost, User $user, array $matchedSkills, string $type): array
    {
        $variations = [];
        $templates = $this->getTemplatesForType($type);

        // Prepare base prompt
        $basePrompt = $this->prepareBasePrompt($jobPost, $user, $matchedSkills, $type);

        // Generate one variation for each template style
        foreach ($templates as $templateName => $templateInstructions) {
            try {
                $prompt = $basePrompt . "\n\n" . $templateInstructions;

                $response = OpenAI::chat()->create([
                    'model' => 'gpt-4o',
                    'temperature' => 0.7, // Slightly higher temperature for variation
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $type === 'resume' ?
                                'You are an expert resume writer.' :
                                'You are an expert cover letter writer.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ]
                ]);

                $content = $response->choices[0]->message->content;

                $variations[$templateName] = [
                    'content' => $content,
                    'template' => $templateName,
                ];

            } catch (Exception $e) {
                Log::error("Failed to generate content variation", [
                    'template' => $templateName,
                    'type' => $type,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $variations;
    }

    /**
     * Get templates for the specified document type
     *
     * @param string $type
     * @return array
     */
    private function getTemplatesForType(string $type): array
    {
        if ($type === 'resume') {
            return [
                'Chronological' => 'Create a chronological resume that emphasizes work history in reverse chronological order. Focus on progression and growth in responsibilities.',

                'Functional' => 'Create a functional resume that emphasizes skills and abilities rather than the chronological work history. Group achievements under skill categories to highlight transferable expertise.',

                'Combination' => 'Create a combination resume that blends chronological and functional formats. Start with a strong skills section followed by a concise work history section.',
            ];
        } else { // cover_letter
            return [
                'Problem-Solution' => 'Create a cover letter that identifies a specific challenge mentioned in the job description, then demonstrates how the candidate has solved similar problems in the past.',

                'Company Research' => 'Create a cover letter that demonstrates deep knowledge of the company\'s mission, recent achievements, or culture, connecting the candidate\'s values and experience to the company specifically.',

                'Story-Based' => 'Create a narrative-driven cover letter that tells a compelling story about a relevant accomplishment that demonstrates why the candidate is perfect for this role.',
            ];
        }
    }

    /**
     * Prepare the base prompt for generation
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param array $matchedSkills
     * @param string $type
     * @return string
     */
    private function prepareBasePrompt(JobPost $jobPost, User $user, array $matchedSkills, string $type): string
    {
        $jobInfo = "Company: {$jobPost->company_name}\nPosition: {$jobPost->job_title}\n\nJob Description:\n{$jobPost->job_description}";

        $userInfo = "Name: {$user->first_name} {$user->last_name}\nEmail: {$user->email}\nPhone: {$user->phone_number}\nLocation: {$user->location}";

        if ($user->linkedin_url) {
            $userInfo .= "\nLinkedIn: {$user->linkedin_url}";
        }

        if ($user->github_url) {
            $userInfo .= "\nGitHub: {$user->github_url}";
        }

        if ($user->personal_website_url) {
            $userInfo .= "\nWebsite: {$user->personal_website_url}";
        }

        $matchInfo = json_encode($matchedSkills, JSON_PRETTY_PRINT);

        if ($type === 'resume') {
            return <<<EOT
Create a tailored resume for the following job posting:

## Job Information
{$jobInfo}

## Candidate Information
{$userInfo}

## Skills Analysis
{$matchInfo}

Important guidelines:
1. Focus on the matched skills and experience that align with job requirements
2. Address skill gaps by highlighting transferable skills or related experience
3. Use keywords from the job posting
4. Quantify achievements with numbers and metrics
5. Keep the resume between 475-600 words
6. Use a clean, ATS-friendly format
7. Include a LinkedIn profile link
8. Avoid generic buzzwords
EOT;
        } else { // cover_letter
            return <<<EOT
Create a compelling cover letter for the following job posting:

## Job Information
{$jobInfo}

## Candidate Information
{$userInfo}

## Skills Analysis
{$matchInfo}

Important guidelines:
1. Open with a strong hook that connects to the company
2. Address the hiring manager directly if possible
3. Focus on the candidate's top 2-3 most relevant experiences/achievements
4. Directly address how the candidate can solve specific problems mentioned in the job description
5. Show knowledge of the company's mission, products, or culture
6. Keep the letter concise (about 500 words maximum)
7. Include a clear call to action in the closing paragraph
8. Maintain a professional yet conversational tone
EOT;
        }
    }

    /**
     * Validate and refine generated content variations
     *
     * @param array $variations
     * @param JobPost $jobPost
     * @param string $type
     * @return array
     */
    private function validateAndRefine(array $variations, JobPost $jobPost, string $type): array
    {
        $results = [];

        foreach ($variations as $templateName => $variation) {
            try {
                // Analyze content against rules
                $validationResponse = OpenAI::chat()->create([
                    'model' => 'gpt-4o',
                    'temperature' => 0.2,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert document reviewer who validates resumes and cover letters against best practices and job requirements.'
                        ],
                        [
                            'role' => 'user',
                            'content' => "Evaluate the following {$type} against job posting requirements and best practices:

                            # Job Posting
                            Company: {$jobPost->company_name}
                            Position: {$jobPost->job_title}

                            # {$type} Content
                            {$variation['content']}

                            Evaluate the document on these criteria:
                            1. Relevance to job requirements
                            2. Use of appropriate keywords
                            3. Quantifiable achievements
                            4. ATS-friendliness
                            5. Clarity and conciseness
                            6. Appropriate length
                            7. Overall impact

                            Return your analysis as JSON with:
                            - scores for each criterion (1-10)
                            - overall_score (1-100)
                            - strengths (array of strings)
                            - weaknesses (array of strings)
                            - suggestions for improvement (array of strings)"
                        ]
                    ]
                ]);

                $validationContent = $validationResponse->choices[0]->message->content;

                // Parse JSON validation
                $validation = json_decode($validationContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    // If the response isn't valid JSON, try to extract it
                    preg_match('/```json(.*?)```/s', $validationContent, $matches);
                    if (isset($matches[1])) {
                        $validation = json_decode(trim($matches[1]), true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new Exception("Failed to parse validation JSON");
                        }
                    } else {
                        throw new Exception("Failed to parse validation JSON");
                    }
                }

                // If score is low, refine the content
                if (isset($validation['overall_score']) && $validation['overall_score'] < 75) {
                    $refinedContent = $this->refineContent($variation['content'], $validation, $jobPost, $type);

                    $results[$templateName] = [
                        'original_content' => $variation['content'],
                        'refined_content' => $refinedContent,
                        'template' => $templateName,
                        'validation' => $validation,
                        'was_refined' => true,
                    ];
                } else {
                    $results[$templateName] = [
                        'content' => $variation['content'],
                        'template' => $templateName,
                        'validation' => $validation,
                        'was_refined' => false,
                    ];
                }

            } catch (Exception $e) {
                Log::error("Failed to validate and refine content", [
                    'template' => $templateName,
                    'type' => $type,
                    'error' => $e->getMessage(),
                ]);

                $results[$templateName] = [
                    'content' => $variation['content'],
                    'template' => $templateName,
                    'validation' => [
                        'overall_score' => 50,
                        'note' => 'Validation failed, using original content',
                    ],
                    'was_refined' => false,
                ];
            }
        }

        return $results;
    }

    /**
     * Refine content based on validation feedback
     *
     * @param string $content
     * @param array $validation
     * @param JobPost $jobPost
     * @param string $type
     * @return string
     */
    private function refineContent(string $content, array $validation, JobPost $jobPost, string $type): string
    {
        try {
            // Prepare feedback for refinement
            $feedbackPoints = [];

            if (isset($validation['weaknesses']) && is_array($validation['weaknesses'])) {
                foreach ($validation['weaknesses'] as $weakness) {
                    $feedbackPoints[] = "- {$weakness}";
                }
            }

            if (isset($validation['suggestions']) && is_array($validation['suggestions'])) {
                foreach ($validation['suggestions'] as $suggestion) {
                    $feedbackPoints[] = "- {$suggestion}";
                }
            }

            $feedback = implode("\n", $feedbackPoints);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'temperature' => 0.4,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $type === 'resume' ?
                            'You are an expert resume writer who refines resumes to perfection.' :
                            'You are an expert cover letter writer who refines cover letters to perfection.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Refine the following {$type} based on this feedback:

                        # Original {$type}
                        {$content}

                        # Feedback for Improvement
                        {$feedback}

                        # Job Details
                        Company: {$jobPost->company_name}
                        Position: {$jobPost->job_title}

                        Improve the {$type} while maintaining its basic structure. Address each feedback point. Ensure the result is polished, professional, and optimized for the specific job."
                    ]
                ]
            ]);

            return $response->choices[0]->message->content;

        } catch (Exception $e) {
            Log::error("Failed to refine content", [
                'error' => $e->getMessage(),
            ]);

            // Return original content if refinement fails
            return $content;
        }
    }

    /**
     * Select the best variation based on validation scores
     *
     * @param array $results
     * @return array
     */
    public function selectBestVariation(array $results): array
    {
        $bestTemplate = null;
        $bestScore = -1;

        foreach ($results as $templateName => $result) {
            $score = $result['validation']['overall_score'] ?? 0;

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestTemplate = $templateName;
            }
        }

        if ($bestTemplate) {
            $selected = $results[$bestTemplate];

            // If it was refined, use the refined content
            if (isset($selected['was_refined']) && $selected['was_refined']) {
                $content = $selected['refined_content'];
            } else {
                $content = $selected['content'] ?? $selected['original_content'];
            }

            return [
                'content' => $content,
                'template' => $bestTemplate,
                'score' => $bestScore,
                'validation' => $selected['validation'],
                'all_variations' => $results,
            ];
        }

        // Fallback if no best template found
        $firstTemplate = array_key_first($results);
        return [
            'content' => $results[$firstTemplate]['content'] ?? '',
            'template' => $firstTemplate,
            'score' => $results[$firstTemplate]['validation']['overall_score'] ?? 0,
            'validation' => $results[$firstTemplate]['validation'] ?? [],
            'all_variations' => $results,
        ];
    }
}
````

## File: app/Services/ThreadManagementService.php
````php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\JobPost;
use App\Models\ThreadSession;
use App\Services\AssistantsService;
use Illuminate\Support\Facades\Log;
use Exception;

class ThreadManagementService
{
    protected $assistantsService;
    
    public function __construct(AssistantsService $assistantsService)
    {
        $this->assistantsService = $assistantsService;
    }
    
    /**
     * Start a new generation session for a resume
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return ThreadSession
     */
    public function startResumeSession(User $user, JobPost $jobPost): ThreadSession
    {
        // Get or create the resume assistant
        $assistantId = $this->assistantsService->getOrCreateAssistant(AssistantsService::TYPE_RESUME);
        
        // Create a new thread
        $threadId = $this->assistantsService->createThread();
        
        // Create a ThreadSession record
        $session = ThreadSession::create([
            'user_id' => $user->id,
            'job_post_id' => $jobPost->id,
            'assistant_id' => $assistantId,
            'thread_id' => $threadId,
            'type' => 'resume',
            'status' => 'created',
        ]);
        
        // Prepare initial message with job details and user profile
        $initialMessage = $this->prepareResumeInitialMessage($user, $jobPost);
        
        // Add the message to the thread
        $this->assistantsService->addMessage($threadId, $initialMessage);
        
        return $session;
    }
    
    /**
     * Start a new generation session for a cover letter
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return ThreadSession
     */
    public function startCoverLetterSession(User $user, JobPost $jobPost): ThreadSession
    {
        // Get or create the cover letter assistant
        $assistantId = $this->assistantsService->getOrCreateAssistant(AssistantsService::TYPE_COVER_LETTER);
        
        // Create a new thread
        $threadId = $this->assistantsService->createThread();
        
        // Create a ThreadSession record
        $session = ThreadSession::create([
            'user_id' => $user->id,
            'job_post_id' => $jobPost->id,
            'assistant_id' => $assistantId,
            'thread_id' => $threadId,
            'type' => 'cover_letter',
            'status' => 'created',
        ]);
        
        // Prepare initial message with job details and user profile
        $initialMessage = $this->prepareCoverLetterInitialMessage($user, $jobPost);
        
        // Add the message to the thread
        $this->assistantsService->addMessage($threadId, $initialMessage);
        
        return $session;
    }
    
    /**
     * Run a session and get the generated content
     *
     * @param ThreadSession $session
     * @return string The generated content
     */
    public function generateContent(ThreadSession $session): string
    {
        try {
            // Update session status
            $session->update(['status' => 'processing']);
            
            // Run the assistant
            $messages = $this->assistantsService->runAssistant(
                $session->thread_id, 
                $session->assistant_id
            );
            
            if (empty($messages)) {
                throw new Exception("No response generated from assistant");
            }
            
            // Get the content from the first (most recent) message
            $content = '';
            foreach ($messages[0]->content as $contentPart) {
                if ($contentPart->type === 'text') {
                    $content .= $contentPart->text->value;
                }
            }
            
            // Update session status and content
            $session->update([
                'status' => 'completed',
                'content' => $content,
                'completed_at' => now(),
            ]);
            
            return $content;
            
        } catch (Exception $e) {
            // Update session status to failed
            $session->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
            
            Log::error("Generation failed", [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Prepare the initial message for resume generation
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return string
     */
    private function prepareResumeInitialMessage(User $user, JobPost $jobPost): string
    {
        $userProfile = $this->formatUserProfile($user);
        $jobDetails = $this->formatJobDetails($jobPost);
        
        return <<<EOT
I need you to create a tailored resume for the following job posting.

## Job Details:
{$jobDetails}

## User Profile:
{$userProfile}

Please create a resume that:
1. Is tailored specifically to this job posting
2. Highlights the most relevant skills and experience
3. Follows ATS-friendly formatting (single column, standard fonts)
4. Includes quantifiable achievements
5. Is between 475-600 words in length
6. Avoids generic buzzwords and focuses on concrete results
7. Positions the candidate as the "perfect puzzle piece" for this role

Create the resume in markdown format.
EOT;
    }
    
    /**
     * Prepare the initial message for cover letter generation
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return string
     */
    private function prepareCoverLetterInitialMessage(User $user, JobPost $jobPost): string
    {
        $userProfile = $this->formatUserProfile($user);
        $jobDetails = $this->formatJobDetails($jobPost);
        $companyName = $jobPost->company_name;
        
        return <<<EOT
I need you to create a compelling cover letter for the following job posting.

## Job Details:
{$jobDetails}

## User Profile:
{$userProfile}

## Company Research:
The company name is {$companyName}. Please craft a compelling hook that connects the candidate to this company.

Please create a cover letter that:
1. Starts with a strong hook connecting the candidate to the company
2. Is addressed properly (to the hiring manager by name if available)
3. Every sentence has a clear purpose relating to the job requirements
4. Highlights 2-3 key achievements that directly relate to the position
5. Demonstrates knowledge of the company
6. Maintains a professional yet conversational tone
7. Is one page maximum (450-750 words)
8. Includes a strong closing paragraph
9. Is formatted with proper white space

Create the cover letter in markdown format.
EOT;
    }
    
    /**
     * Format the user profile for messages
     *
     * @param User $user
     * @return string
     */
    private function formatUserProfile(User $user): string
    {
        $profile = "### Personal Information:\n";
        $profile .= "Name: {$user->first_name} {$user->last_name}\n";
        $profile .= "Email: {$user->email}\n";
        $profile .= "Phone: {$user->phone_number}\n";
        $profile .= "Location: {$user->location}\n";
        
        if ($user->linkedin_url) {
            $profile .= "LinkedIn: {$user->linkedin_url}\n";
        }
        
        if ($user->github_url) {
            $profile .= "GitHub: {$user->github_url}\n";
        }
        
        if ($user->personal_website_url) {
            $profile .= "Website: {$user->personal_website_url}\n";
        }
        
        // Add work experience
        $profile .= "\n### Work Experience:\n";
        foreach ($user->workExperiences()->orderBy('start_date', 'desc')->get() as $experience) {
            $endDate = $experience->current_job ? "Present" : $experience->end_date->format('M Y');
            $profile .= "- **{$experience->position}** at {$experience->company_name} ({$experience->start_date->format('M Y')} - {$endDate})\n";
            $profile .= "  {$experience->description}\n";
            
            if (!empty($experience->achievements)) {
                $profile .= "  **Achievements:**\n";
                foreach ($experience->achievements as $achievement => $description) {
                    $profile .= "  - {$achievement}: {$description}\n";
                }
            }
        }
        
        // Add education
        $profile .= "\n### Education:\n";
        foreach ($user->education()->orderBy('start_date', 'desc')->get() as $education) {
            $endDate = $education->current ? "Present" : $education->end_date->format('M Y');
            $fieldOfStudy = !empty($education->field_of_study) ? " in {$education->field_of_study}" : "";
            $profile .= "- **{$education->degree}{$fieldOfStudy}** from {$education->institution} ({$education->start_date->format('M Y')} - {$endDate})\n";
            
            if (!empty($education->achievements)) {
                $profile .= "  **Achievements:**\n";
                foreach ($education->achievements as $achievement => $description) {
                    $profile .= "  - {$achievement}: {$description}\n";
                }
            }
        }
        
        // Add skills
        $profile .= "\n### Skills:\n";
        foreach ($user->skills()->orderBy('proficiency', 'desc')->get() as $skill) {
            $experience = $skill->years_experience > 0 ? " ({$skill->years_experience} years)" : "";
            $profile .= "- {$skill->name}{$experience}\n";
        }
        
        // Add projects
        $profile .= "\n### Projects:\n";
        foreach ($user->projects as $project) {
            $profile .= "- **{$project->name}**\n";
            $profile .= "  {$project->description}\n";
            
            if (!empty($project->technologies_used)) {
                $techs = implode(", ", array_keys($project->technologies_used));
                $profile .= "  **Technologies:** {$techs}\n";
            }
            
            if (!empty($project->url)) {
                $profile .= "  **URL:** {$project->url}\n";
            }
        }
        
        return $profile;
    }
    
    /**
     * Format the job details for messages
     *
     * @param JobPost $jobPost
     * @return string
     */
    private function formatJobDetails(JobPost $jobPost): string
    {
        $details = "### Job Information:\n";
        $details .= "Company: {$jobPost->company_name}\n";
        $details .= "Position: {$jobPost->job_title}\n";
        $details .= "Location Type: {$jobPost->job_location_type}\n";
        $details .= "Job Type: {$jobPost->job_type}\n";
        $details .= "Position Level: {$jobPost->position_level}\n\n";
        
        $details .= "### Job Description:\n";
        $details .= "{$jobPost->job_description}\n\n";
        
        // Add required skills
        if (!empty($jobPost->required_skills)) {
            $details .= "### Required Skills:\n";
            foreach ($jobPost->required_skills as $skill) {
                if (is_array($skill) && isset($skill['fields']['name'])) {
                    // Format for complex skill objects
                    $name = $skill['fields']['name'];
                    $level = $skill['fields']['level'] ?? null;
                    $levelText = $level ? " (Level: {$level})" : "";
                    $details .= "- {$name}{$levelText}\n";
                } else {
                    // Simple string format
                    $details .= "- {$skill}\n";
                }
            }
            $details .= "\n";
        }
        
        // Add preferred skills
        if (!empty($jobPost->preferred_skills)) {
            $details .= "### Preferred Skills:\n";
            foreach ($jobPost->preferred_skills as $skill) {
                if (is_array($skill) && isset($skill['fields']['name'])) {
                    // Format for complex skill objects
                    $name = $skill['fields']['name'];
                    $level = $skill['fields']['level'] ?? null;
                    $levelText = $level ? " (Level: {$level})" : "";
                    $details .= "- {$name}{$levelText}\n";
                } else {
                    // Simple string format
                    $details .= "- {$skill}\n";
                }
            }
            $details .= "\n";
        }
        
        // Add required experience
        if (!empty($jobPost->required_experience)) {
            $details .= "### Required Experience:\n";
            foreach ($jobPost->required_experience as $experience) {
                if (is_array($experience) && isset($experience['fields']['title'])) {
                    // Format for complex experience objects
                    $title = $experience['fields']['title'];
                    $years = $experience['fields']['years'] ?? null;
                    $yearsText = $years ? " ({$years} years)" : "";
                    $details .= "- {$title}{$yearsText}\n";
                    
                    if (isset($experience['fields']['description']) && !empty($experience['fields']['description'])) {
                        $details .= "  {$experience['fields']['description']}\n";
                    }
                } else {
                    // Simple format
                    $details .= "- {$experience}\n";
                }
            }
            $details .= "\n";
        }
        
        // Add required education
        if (!empty($jobPost->required_education)) {
            $details .= "### Required Education:\n";
            foreach ($jobPost->required_education as $education) {
                if (is_array($education) && isset($education['fields']['level'])) {
                    // Format for complex education objects
                    $level = $education['fields']['level'];
                    $field = $education['fields']['field'] ?? '';
                    $details .= "- {$level} in {$field}\n";
                    
                    if (isset($education['fields']['description']) && !empty($education['fields']['description'])) {
                        $details .= "  {$education['fields']['description']}\n";
                    }
                } else {
                    // Simple format
                    $details .= "- {$education}\n";
                }
            }
        }
        
        return $details;
    }
    
    /**
     * Validate a generated document
     *
     * @param string $content The document content
     * @param string $type The document type (resume or cover_letter)
     * @param JobPost $jobPost The job post
     * @return array Validation results with scores and feedback
     */
    public function validateDocument(string $content, string $type, JobPost $jobPost): array
    {
        // Get the validator assistant
        $assistantId = $this->assistantsService->getOrCreateAssistant(AssistantsService::TYPE_VALIDATOR);
        
        // Create a new thread
        $threadId = $this->assistantsService->createThread();
        
        // Prepare the validation message
        $message = $this->prepareValidationMessage($content, $type, $jobPost);
        
        // Add the message to the thread
        $this->assistantsService->addMessage($threadId, $message);
        
        // Run the assistant
        $messages = $this->assistantsService->runAssistant($threadId, $assistantId);
        
        if (empty($messages)) {
            throw new Exception("No validation response generated");
        }
        
        // Parse the validation results
        $validationText = '';
        foreach ($messages[0]->content as $contentPart) {
            if ($contentPart->type === 'text') {
                $validationText .= $contentPart->text->value;
            }
        }
        
        // Parse the validation text into a structured format
        // This is a simplified parsing - in production you might want more robust parsing
        $lines = explode("\n", $validationText);
        $results = [
            'overall_score' => 0,
            'criteria' => [],
            'summary' => '',
            'suggestions' => [],
        ];
        
        $currentCriterion = null;
        
        foreach ($lines as $line) {
            // Try to extract overall score
            if (preg_match('/overall score:?\s*(\d+)(?:\/10)?/i', $line, $matches)) {
                $results['overall_score'] = (int) $matches[1];
            }
            
            // Try to extract criterion
            if (preg_match('/^(\d+)\.?\s+(.+?):?\s*(\d+)(?:\/10)?/i', $line, $matches)) {
                $criterionName = trim($matches[2]);
                $score = (int) $matches[3];
                $currentCriterion = $criterionName;
                $results['criteria'][$currentCriterion] = [
                    'score' => $score,
                    'feedback' => '',
                ];
            } elseif ($currentCriterion && !empty(trim($line)) && !preg_match('/^(\d+)\./', $line)) {
                // Add to current criterion feedback
                $results['criteria'][$currentCriterion]['feedback'] .= " " . trim($line);
            }
            
            // Try to extract suggestions
            if (preg_match('/suggestion(?:s)?:?/i', $line)) {
                $inSuggestions = true;
            }
            
            if (isset($inSuggestions) && $inSuggestions && preg_match('/^-\s+(.+)$/', $line, $matches)) {
                $results['suggestions'][] = trim($matches[1]);
            }
            
            // Try to extract summary
            if (preg_match('/summary:?/i', $line)) {
                $inSummary = true;
                continue;
            }
            
            if (isset($inSummary) && $inSummary && !empty(trim($line)) && 
                !preg_match('/suggestion(?:s)?:?/i', $line)) {
                $results['summary'] .= " " . trim($line);
            }
        }
        
        $results['summary'] = trim($results['summary']);
        
        return $results;
    }
    
    /**
     * Prepare a message for document validation
     *
     * @param string $content The document content
     * @param string $type The document type
     * @param JobPost $jobPost The job post
     * @return string
     */
    private function prepareValidationMessage(string $content, string $type, JobPost $jobPost): string
    {
        $jobDetails = $this->formatJobDetails($jobPost);
        $documentType = $type === 'resume' ? 'Resume' : 'Cover Letter';
        
        return <<<EOT
I need you to validate the following {$documentType} against best practices and the job description.

## Job Details:
{$jobDetails}

## {$documentType} Content:
{$content}

Please provide a thorough assessment including:
1. An overall score from 1-10
2. Individual scores for key criteria:
   - ATS Compatibility (formatting, layout)
   - Keyword Optimization
   - Achievement Quantification
   - Tailoring to the Job
   - Writing Quality
   - Document Length
3. Specific issues identified
4. Suggestions for improvement
5. A brief summary assessment

Format your response with clear sections for each of these elements.
EOT;
    }
}
````

## File: database/factories/CoverLetterFactory.php
````php
<?php

namespace Database\Factories;

use App\Models\CoverLetter;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoverLetterFactory extends Factory
{
    protected $model = CoverLetter::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_post_id' => JobPost::factory(),
            'content' => fake()->paragraphs(3, true),
            'file_path' => null,
            'word_count' => fake()->numberBetween(400, 750),
            'rule_compliance' => json_encode(['rule1' => ['passed' => true]]),
            'generation_metadata' => json_encode(['model' => 'gpt-4', 'usage' => ['total_tokens' => 400]])
        ];
    }
}
````

## File: database/factories/JobPostFactory.php
````php
<?php

namespace Database\Factories;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobPost::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => fake()->company(),
            'job_title' => fake()->jobTitle(),
            'job_description' => fake()->paragraphs(3, true),
            'job_post_url' => fake()->url(),
            'job_post_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'job_location_type' => fake()->randomElement(['remote', 'in-office', 'hybrid']),
            'required_skills' => json_encode([$this->randomSkill(), $this->randomSkill(), $this->randomSkill()]),
            'preferred_skills' => json_encode([$this->randomSkill(), $this->randomSkill()]),
            'required_experience' => json_encode(['years' => fake()->numberBetween(1, 10), 'description' => fake()->sentence()]),
            'required_education' => json_encode(['level' => fake()->randomElement(['Bachelors', 'Masters', 'PhD']), 'field' => fake()->word()]),
            'resume_min_words' => 450,
            'resume_max_words' => 850,
            'cover_letter_min_words' => 450,
            'cover_letter_max_words' => 750,
            'resume_min_pages' => 1,
            'resume_max_pages' => 2,
            'cover_letter_min_pages' => 1,
            'cover_letter_max_pages' => 1,
            'things_i_like' => fake()->paragraph(),
            'things_i_dislike' => fake()->paragraph(),
            'things_i_like_about_company' => fake()->paragraph(),
            'things_i_dislike_about_company' => fake()->paragraph(),
            'open_to_travel' => fake()->boolean(),
            'salary_range_min' => fake()->numberBetween(50000, 80000),
            'salary_range_max' => fake()->numberBetween(90000, 150000),
            'min_acceptable_salary' => fake()->numberBetween(45000, 70000),
            'position_level' => fake()->randomElement(['entry-level', 'mid-level', 'senior', 'lead', 'manager', 'director', 'executive']),
            'job_type' => fake()->randomElement(['full-time', 'part-time', 'contract', 'internship', 'freelance']),
            'ideal_start_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'position_preference' => fake()->numberBetween(1, 5),
            'first_time_applying' => fake()->boolean(80),
        ];
    }

    /**
     * Generate a random skill name
     */
    private function randomSkill(): string
    {
        return fake()->randomElement([
            'JavaScript', 'PHP', 'Python', 'Java', 'C#',
            'React', 'Vue', 'Angular', 'Laravel', 'Django',
            'Node.js', 'AWS', 'Docker', 'Kubernetes', 'SQL',
            'NoSQL', 'Product Management', 'Agile', 'Scrum', 'UI/UX',
            'SEO', 'Content Marketing', 'Data Analysis', 'Machine Learning'
        ]);
    }
}
````

## File: database/factories/ResumeFactory.php
````php
<?php

namespace Database\Factories;

use App\Models\Resume;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResumeFactory extends Factory
{
    protected $model = Resume::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_post_id' => JobPost::factory(),
            'content' => fake()->paragraphs(5, true),
            'file_path' => null,
            'word_count' => fake()->numberBetween(400, 900),
            'skills_included' => json_encode(['JavaScript', 'PHP', 'React']),
            'experiences_included' => json_encode(['Software Engineer', 'Web Developer']),
            'education_included' => json_encode(['Bachelor of Science']),
            'projects_included' => json_encode(['Portfolio Website', 'E-commerce Platform']),
            'rule_compliance' => json_encode(['rule1' => ['passed' => true]]),
            'generation_metadata' => json_encode(['model' => 'gpt-4', 'usage' => ['total_tokens' => 500]])
        ];
    }
}
````

## File: database/migrations/0001_01_01_000001_create_cache_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
````

## File: database/migrations/0001_01_01_000002_create_jobs_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
````

## File: database/migrations/2025_04_05_174213_create_telescope_entries_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Get the migration connection name.
     */
    public function getConnection(): ?string
    {
        return config('telescope.storage.database.connection');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->create('telescope_entries', function (Blueprint $table) {
            $table->bigIncrements('sequence');
            $table->uuid('uuid');
            $table->uuid('batch_id');
            $table->string('family_hash')->nullable();
            $table->boolean('should_display_on_index')->default(true);
            $table->string('type', 20);
            $table->longText('content');
            $table->dateTime('created_at')->nullable();

            $table->unique('uuid');
            $table->index('batch_id');
            $table->index('family_hash');
            $table->index('created_at');
            $table->index(['type', 'should_display_on_index']);
        });

        $schema->create('telescope_entries_tags', function (Blueprint $table) {
            $table->uuid('entry_uuid');
            $table->string('tag');

            $table->primary(['entry_uuid', 'tag']);
            $table->index('tag');

            $table->foreign('entry_uuid')
                ->references('uuid')
                ->on('telescope_entries')
                ->onDelete('cascade');
        });

        $schema->create('telescope_monitoring', function (Blueprint $table) {
            $table->string('tag')->primary();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->dropIfExists('telescope_entries_tags');
        $schema->dropIfExists('telescope_entries');
        $schema->dropIfExists('telescope_monitoring');
    }
};
````

## File: database/migrations/2025_04_05_175254_add_two_factor_columns_to_users_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')
                ->after('password')
                ->nullable();

            $table->text('two_factor_recovery_codes')
                ->after('two_factor_secret')
                ->nullable();

            $table->timestamp('two_factor_confirmed_at')
                ->after('two_factor_recovery_codes')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
            ]);
        });
    }
};
````

## File: database/migrations/2025_04_05_185149_create_job_posts_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('company_name');
            $table->string('job_title');
            $table->text('job_description');
            $table->string('job_post_url')->nullable();
            $table->date('job_post_date')->nullable();
            $table->enum('job_location_type', ['remote', 'in-office', 'hybrid'])->default('remote');
            $table->json('required_skills')->nullable();
            $table->json('preferred_skills')->nullable();
            $table->json('required_experience')->nullable();
            $table->json('required_education')->nullable();
            $table->integer('resume_min_words')->default(450);
            $table->integer('resume_max_words')->default(850);
            $table->integer('cover_letter_min_words')->default(450);
            $table->integer('cover_letter_max_words')->default(750);
            $table->integer('resume_min_pages')->default(1);
            $table->integer('resume_max_pages')->default(2);
            $table->integer('cover_letter_min_pages')->default(1);
            $table->integer('cover_letter_max_pages')->default(1);
            $table->text('things_i_like')->nullable();
            $table->text('things_i_dislike')->nullable();
            $table->text('things_i_like_about_company')->nullable();
            $table->text('things_i_dislike_about_company')->nullable();
            $table->boolean('open_to_travel')->default(true);
            $table->decimal('salary_range_min', 10, 2)->nullable();
            $table->decimal('salary_range_max', 10, 2)->nullable();
            $table->decimal('min_acceptable_salary', 10, 2)->nullable();
            $table->enum('position_level', ['entry-level', 'mid-level', 'senior', 'lead', 'manager', 'director', 'executive'])->default('mid-level');
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'internship', 'freelance'])->default('full-time');
            $table->date('ideal_start_date')->nullable();
            $table->integer('position_preference')->default(1); // 1 = top choice, 2 = second choice, etc.
            $table->boolean('first_time_applying')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
````

## File: database/migrations/2025_04_05_185524_create_work_experiences_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('company_name');
            $table->string('position');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('current_job')->default(false);
            $table->text('description');
            $table->json('skills_used')->nullable();
            $table->json('achievements')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
````

## File: database/migrations/2025_04_05_185606_create_education_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('institution');
            $table->string('degree');
            $table->string('field_of_study')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('current')->default(false);
            $table->decimal('gpa', 3, 2)->nullable();
            $table->text('achievements')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
````

## File: database/migrations/2025_04_05_185713_create_skills_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->enum('type', ['technical', 'soft', 'language', 'other'])->default('technical');
            $table->integer('proficiency')->default(0); // 1-10 scale
            $table->integer('years_experience')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
````

## File: database/migrations/2025_04_05_190541_create_projects_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->text('description');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('url')->nullable();
            $table->json('technologies_used')->nullable();
            $table->json('achievements')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
````

## File: database/migrations/2025_04_05_190552_create_resumes_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('job_post_id')->constrained();
            $table->text('content');
            $table->string('file_path')->nullable();
            $table->integer('word_count')->nullable();
            $table->json('skills_included')->nullable();
            $table->json('experiences_included')->nullable();
            $table->json('education_included')->nullable();
            $table->json('projects_included')->nullable();
            $table->json('rule_compliance')->nullable(); // Track which rules were followed
            $table->json('generation_metadata')->nullable(); // Store OpenAI generation details
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
````

## File: database/migrations/2025_04_05_190603_create_cover_letters_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cover_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('job_post_id')->constrained();
            $table->text('content');
            $table->string('file_path')->nullable();
            $table->integer('word_count')->nullable();
            $table->json('rule_compliance')->nullable(); // Track which rules were followed
            $table->json('generation_metadata')->nullable(); // Store OpenAI generation details
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cover_letters');
    }
};
````

## File: database/migrations/2025_04_05_190617_create_rules_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['resume', 'cover_letter', 'both'])->default('both');
            $table->string('source')->nullable(); // Where the rule came from
            $table->integer('importance')->default(5); // 1-10 scale
            $table->json('validation_logic')->nullable(); // Store logic to validate rule
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
````

## File: database/migrations/2025_04_05_190623_create_openai_prompts_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('openai_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['resume', 'cover_letter', 'analysis', 'rule_check']);
            $table->text('prompt_template');
            $table->json('parameters')->nullable();
            $table->string('model')->default('gpt-4o');
            $table->integer('max_tokens')->default(2000);
            $table->decimal('temperature', 2, 1)->default(0.7);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('openai_prompts');
    }
};
````

## File: database/migrations/2025_04_06_040907_create_thread_sessions_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('thread_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('job_post_id')->constrained();
            $table->string('assistant_id');
            $table->string('thread_id');
            $table->enum('type', ['resume', 'cover_letter', 'validation']);
            $table->enum('status', ['created', 'processing', 'completed', 'failed']);
            $table->longText('content')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('metrics')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_sessions');
    }
};
````

## File: database/migrations/2025_04_06_041204_add_thread_session_id_to_resumes_and_cvs.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->foreignId('thread_session_id')->nullable()->after('job_post_id')->constrained();
        });

        Schema::table('cover_letters', function (Blueprint $table) {
            $table->foreignId('thread_session_id')->nullable()->after('job_post_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropForeign(['thread_session_id']);
            $table->dropColumn('thread_session_id');
        });

        Schema::table('cover_letters', function (Blueprint $table) {
            $table->dropForeign(['thread_session_id']);
            $table->dropColumn('thread_session_id');
        });
    }
};
````

## File: database/migrations/2025_04_06_044443_create_skill_embeddings_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skill_embeddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('skill_id')->constrained();
            $table->json('embedding');
            $table->text('skill_description');
            $table->timestamps();

            $table->unique(['skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_embeddings');
    }
};
````

## File: database/migrations/2025_04_06_044453_create_job_requirement_embeddings_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('job_requirement_embeddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained();
            $table->enum('requirement_type', ['skills', 'experience', 'education', 'full_description']);
            $table->json('embedding');
            $table->text('requirement_text');
            $table->timestamps();

            $table->unique(['job_post_id', 'requirement_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requirement_embeddings');
    }
};
````

## File: database/seeders/RulesSeeder.php
````php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rule;

class RulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resume Rules
        $resumeRules = [
            [
                'name' => 'One or Two Pages Maximum',
                'description' => 'Resume should be one page for less than 10 years of experience, two pages maximum for more experience.',
                'type' => 'resume',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 9,
                'validation_logic' => json_encode(['type' => 'page_count', 'min' => 1, 'max' => 2])
            ],
            [
                'name' => 'Quantify Achievements',
                'description' => 'Use numbers and percentages to quantify achievements wherever possible (e.g., "Increased sales by 25%").',
                'type' => 'resume',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 8,
                'validation_logic' => null
            ],
            [
                'name' => 'Tailor Keywords to Job Description',
                'description' => 'Include keywords from the job description to pass ATS screening.',
                'type' => 'resume',
                'source' => 'Ex-Google Recruiter (8 Secrets)',
                'importance' => 10,
                'validation_logic' => null
            ],
            [
                'name' => 'Action Verbs Only',
                'description' => 'Use strong action verbs to start bullet points; avoid passive language.',
                'type' => 'resume',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 7,
                'validation_logic' => null
            ],
            [
                'name' => 'No Personal Pronouns',
                'description' => 'Avoid using "I", "me", or "my" in resume content.',
                'type' => 'resume',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 6,
                'validation_logic' => null
            ],
        ];

        // Cover Letter Rules
        $coverLetterRules = [
            [
                'name' => 'Address Hiring Manager by Name',
                'description' => 'Research and address the hiring manager by name whenever possible.',
                'type' => 'cover_letter',
                'source' => 'Writing Amazing Cover Letters (3 Golden Rules)',
                'importance' => 8,
                'validation_logic' => null
            ],
            [
                'name' => 'Show Enthusiasm for Company',
                'description' => 'Demonstrate knowledge of and enthusiasm for the company\'s mission, values, and work.',
                'type' => 'cover_letter',
                'source' => 'Writing Amazing Cover Letters (3 Golden Rules)',
                'importance' => 9,
                'validation_logic' => null
            ],
            [
                'name' => 'One Page Maximum',
                'description' => 'Cover letter should never exceed one page.',
                'type' => 'cover_letter',
                'source' => 'Cover Letter Mistakes to Avoid',
                'importance' => 10,
                'validation_logic' => json_encode(['type' => 'page_count', 'max' => 1])
            ],
            [
                'name' => 'Connect Experience to Job Requirements',
                'description' => 'Explicitly connect your past experience to the specific requirements in the job posting.',
                'type' => 'cover_letter',
                'source' => 'Writing Amazing Cover Letters (3 Golden Rules)',
                'importance' => 9,
                'validation_logic' => null
            ],
            [
                'name' => 'Strong Opening Hook',
                'description' => 'Begin with a compelling hook that grabs attention and shows your value.',
                'type' => 'cover_letter',
                'source' => 'Writing Amazing Cover Letters (3 Golden Rules)',
                'importance' => 7,
                'validation_logic' => null
            ],
        ];

        // General Rules for Both
        $generalRules = [
            [
                'name' => 'No Spelling or Grammar Errors',
                'description' => 'Documents must be free of all spelling and grammar errors.',
                'type' => 'both',
                'source' => 'Ex-Google Recruiter (8 Secrets)',
                'importance' => 10,
                'validation_logic' => null
            ],
            [
                'name' => 'Consistent Formatting',
                'description' => 'Maintain consistent formatting, fonts, spacing, and bullet styles throughout.',
                'type' => 'both',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 7,
                'validation_logic' => null
            ],
        ];

        foreach (array_merge($resumeRules, $coverLetterRules, $generalRules) as $rule) {
            Rule::create($rule);
        }
    }
}
````

## File: resources/views/pdfs/cover_letter.blade.php
````php
@extends('pdfs.layout', ['title' => 'Cover Letter - ' . $user->first_name . ' ' . $user->last_name])

@section('content')
    <div class="header">
        <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="contact-info">
            {{ $user->email }} | {{ $user->phone_number ?? '' }} | {{ $user->location ?? '' }}
        </div>
        
        <div style="margin-top: 1.5rem;">
            <div>{{ date('F j, Y') }}</div>
            <div>{{ $jobPost->company_name }}</div>
            <div>RE: {{ $jobPost->job_title }}</div>
        </div>
    </div>
    
    <div class="content">
        {!! $content !!}
        
        <div style="margin-top: 2rem;">
            <p>Sincerely,</p>
            <div style="margin-top: 1.5rem;">
                {{ $user->first_name }} {{ $user->last_name }}
            </div>
        </div>
    </div>
@endsection
````

## File: resources/views/pdfs/layout.blade.php
````php
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Document' }}</title>
    <style>
        @page {
            margin: 0.75in;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
        }
        .header {
            margin-bottom: 1.5rem;
        }
        .name {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }
        .contact-info {
            font-size: 11pt;
            margin-bottom: 1rem;
        }
        .job-title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            border-bottom: 1pt solid #333;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        .content {
            font-size: 12pt;
        }
        p {
            margin: 0.5rem 0;
        }
        ul {
            margin: 0.5rem 0;
            padding-left: 1.5rem;
        }
        li {
            margin-bottom: 0.25rem;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9pt;
            font-style: italic;
            color: #777;
        }
    </style>
</head>
<body>
    @yield('content')
    
    <div class="footer">
        Generated by HireGlue on {{ date('F j, Y') }}
    </div>
</body>
</html>
````

## File: resources/views/pdfs/resume.blade.php
````php
@extends('pdfs.layout', ['title' => 'Resume - ' . $user->first_name . ' ' . $user->last_name])

@section('content')
    <div class="header">
        <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="contact-info">
            {{ $user->email }} | {{ $user->phone_number ?? '' }} | {{ $user->location ?? '' }}
            @if($user->linkedin_url)
                | LinkedIn: {{ $user->linkedin_url }}
            @endif
            @if($user->github_url)
                | GitHub: {{ $user->github_url }}
            @endif
            @if($user->personal_website_url)
                | Website: {{ $user->personal_website_url }}
            @endif
        </div>
    </div>
    
    <div class="content">
        {!! $content !!}
    </div>
@endsection
````

## File: routes/web.php
````php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->redirectTo('/app');
});
````

## File: tests/Unit/ExampleTest.php
````php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
````

## File: tests/Unit/OpenAIServiceTest.php
````php
<?php

namespace Tests\Unit;

use App\Models\JobPost;
use App\Models\OpenAIPrompt;
use App\Models\User;
use App\Services\OpenAIService;
use App\Services\RulesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use OpenAI\Laravel\Facades\OpenAI as OpenAIFacade;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Responses\Meta\MetaInformation;
use Tests\TestCase;

class OpenAIServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $openAIService;
    protected $rulesServiceMock;
    protected $user;
    protected $jobPost;
    protected $prompt;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the RulesService
        $this->rulesServiceMock = Mockery::mock(RulesService::class);
        $this->rulesServiceMock->shouldReceive('getAllRules')
            ->andReturn(collect([]));
        
        $this->openAIService = new OpenAIService($this->rulesServiceMock);
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test job post
        $this->jobPost = JobPost::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        // Create test prompt
        $this->prompt = OpenAIPrompt::create([
            'name' => 'resume_generation',
            'type' => 'resume',
            'prompt_template' => 'Generate a resume for {{job_data}} with user {{user_data}}',
            'parameters' => json_encode(['job_data', 'user_data']),
            'model' => 'gpt-4o',
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'active' => true
        ]);
        
        OpenAIPrompt::create([
            'name' => 'cover_letter_generation',
            'type' => 'cover_letter',
            'prompt_template' => 'Generate a cover letter for {{job_data}} with user {{user_data}}',
            'parameters' => json_encode(['job_data', 'user_data']),
            'model' => 'gpt-4o',
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'active' => true
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_generate_resume()
    {
        // Mock the OpenAI response
        $mockResponse = $this->createMockOpenAIResponse(
            'This is a generated resume content',
            $this->prompt->model,
            ['prompt_tokens' => 100, 'completion_tokens' => 200, 'total_tokens' => 300]
        );
        
        Mockery::mock('alias:OpenAI\Laravel\Facades\OpenAI')
            ->shouldReceive('chat->create')
            ->once()
            ->andReturn($mockResponse);
        
        $result = $this->openAIService->generateResume($this->jobPost, $this->user);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertEquals('This is a generated resume content', $result['content']);
        $this->assertEquals($this->prompt->model, $result['metadata']['model']);
    }
    
    public function test_can_generate_cover_letter()
    {
        // Mock the OpenAI response
        $mockResponse = $this->createMockOpenAIResponse(
            'This is a generated cover letter content',
            'gpt-4o',
            ['prompt_tokens' => 100, 'completion_tokens' => 200, 'total_tokens' => 300]
        );
        
        Mockery::mock('alias:OpenAI\Laravel\Facades\OpenAI')
            ->shouldReceive('chat->create')
            ->once()
            ->andReturn($mockResponse);
        
        $result = $this->openAIService->generateCoverLetter($this->jobPost, $this->user);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertEquals('This is a generated cover letter content', $result['content']);
    }
    
    public function test_can_generate_with_feedback()
    {
        // Mock the OpenAI response
        $mockResponse = $this->createMockOpenAIResponse(
            'This is a regenerated content with feedback',
            'gpt-4o',
            ['prompt_tokens' => 150, 'completion_tokens' => 250, 'total_tokens' => 400]
        );
        
        Mockery::mock('alias:OpenAI\Laravel\Facades\OpenAI')
            ->shouldReceive('chat->create')
            ->once()
            ->andReturn($mockResponse);
        
        $result = $this->openAIService->generateResume(
            $this->jobPost, 
            $this->user, 
            null, 
            ['feedback' => 'Please make it more professional']
        );
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertEquals('This is a regenerated content with feedback', $result['content']);
        $this->assertEquals(['feedback' => 'Please make it more professional'], $result['metadata']['extra_context']);
    }
    
    /**
     * Create a mock OpenAI API response
     */
    protected function createMockOpenAIResponse(string $content, string $model, array $usage)
    {
        $mockUsage = Mockery::mock();
        $mockUsage->shouldReceive('toArray')->andReturn($usage);
        
        $mockChoice = Mockery::mock();
        $mockChoice->message = Mockery::mock();
        $mockChoice->message->content = $content;
        
        $mockResponse = Mockery::mock();
        $mockResponse->choices = [$mockChoice];
        $mockResponse->usage = $mockUsage;
        
        return $mockResponse;
    }
}
````

## File: app/Models/CoverLetter.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverLetter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_post_id',
        'thread_session_id',
        'content',
        'file_path',
        'word_count',
        'rule_compliance',
        'generation_metadata'
    ];

    protected $casts = [
        'rule_compliance' => 'array',
        'generation_metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    /**
     * Get the thread session that generated this cover letter
     */
    public function threadSession()
    {
        return $this->belongsTo(ThreadSession::class);
    }
}
````

## File: app/Models/Resume.php
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_post_id',
        'thread_session_id',
        'content',
        'file_path',
        'word_count',
        'skills_included',
        'experiences_included',
        'education_included',
        'projects_included',
        'rule_compliance',
        'generation_metadata'
    ];

    protected $casts = [
        'skills_included' => 'array',
        'experiences_included' => 'array',
        'education_included' => 'array',
        'projects_included' => 'array',
        'rule_compliance' => 'array',
        'generation_metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    /**
     * Get the thread session that generated this resume
     */
    public function threadSession()
    {
        return $this->belongsTo(ThreadSession::class);
    }
}
````

## File: app/Models/User.php
````php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'profile_photo_url',
        'email',
        'phone_number',
        'location',
        'linkedin_url',
        'github_url',
        'personal_website_url',
        'portfolio_url',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function coverLetters()
    {
        return $this->hasMany(CoverLetter::class);
    }
}
````

## File: app/Nova/CoverLetter.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class CoverLetter extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\CoverLetter>
     */
    public static $model = \App\Models\CoverLetter::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Cover Letters';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Cover Letter';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'content'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            BelongsTo::make('Job Post', 'jobPost', JobPost::class),

            Textarea::make('Content')
                ->alwaysShow()
                ->hideFromIndex(),

            Text::make('File Path')
                ->nullable(),

            Number::make('Word Count')
                ->nullable(),

            Code::make('Rule Compliance')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),

            Code::make('Generation Metadata')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
}
````

## File: app/Nova/Education.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Education extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Education>
     */
    public static $model = \App\Models\Education::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'institution';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'institution', 'degree', 'field_of_study'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            Text::make('Institution')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Degree')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Field of Study')
                ->nullable(),

            Date::make('Start Date')
                ->rules('required'),

            Date::make('End Date')
                ->nullable()
                ->hideFromIndex()
                ->help('Leave blank if currently attending'),

            Boolean::make('Current')
                ->default(false),

            Number::make('GPA')
                ->step(0.01)
                ->min(0)
                ->max(4.0)
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Achievements')
                ->keyLabel('Achievement')
                ->valueLabel('Description')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
}
````

## File: app/Nova/Project.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;

class Project extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Project>
     */
    public static $model = \App\Models\Project::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'description'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),
                
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->rules('required')
                ->hideFromIndex(),

            Date::make('Start Date')
                ->nullable(),

            Date::make('End Date')
                ->nullable(),

            Text::make('URL')
                ->hideFromIndex()
                ->nullable(),

            KeyValue::make('Technologies Used')
                ->keyLabel('Technology')
                ->valueLabel('Details')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Achievements')
                ->keyLabel('Achievement')
                ->valueLabel('Description')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
}
````

## File: app/Nova/Resume.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class Resume extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Resume>
     */
    public static $model = \App\Models\Resume::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Resumes';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Resume';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'content'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            BelongsTo::make('Job Post', 'jobPost', JobPost::class),

            Textarea::make('Content')
                ->alwaysShow()
                ->hideFromIndex(),

            Text::make('File Path')
                ->nullable(),

            Number::make('Word Count')
                ->nullable(),

            KeyValue::make('Skills Included', 'skills_included')
                ->keyLabel('Skill')
                ->valueLabel('Relevance')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Experiences Included', 'experiences_included')
                ->keyLabel('Experience')
                ->valueLabel('Relevance')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Education Included', 'education_included')
                ->keyLabel('Education')
                ->valueLabel('Relevance')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Projects Included', 'projects_included')
                ->keyLabel('Project')
                ->valueLabel('Relevance')
                ->nullable()
                ->hideFromIndex(),

            Code::make('Rule Compliance')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),

            Code::make('Generation Metadata')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
}
````

## File: app/Nova/Skill.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Skill extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Skill>
     */
    public static $model = \App\Models\Skill::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'type'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('Type')
                ->options([
                    'technical' => 'Technical',
                    'soft' => 'Soft',
                    'language' => 'Language',
                    'other' => 'Other'
                ])
                ->default('technical')
                ->rules('required'),

            Number::make('Proficiency')
                ->min(1)
                ->max(10)
                ->default(0)
                ->help('Rate your proficiency from 1-10'),

            Number::make('Years Experience')
                ->min(0)
                ->default(0),
        ];
    }
}
````

## File: app/Nova/WorkExperience.php
````php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;

class WorkExperience extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\WorkExperience>
     */
    public static $model = \App\Models\WorkExperience::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'company_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'company_name',
        'position'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            Text::make('Company Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Position')
                ->sortable()
                ->rules('required', 'max:255'),

            Date::make('Start Date')
                ->rules('required'),

            Date::make('End Date')
                ->nullable()
                ->hideFromIndex()
                ->help('Leave blank if this is your current job'),

            Boolean::make('Current Job')
                ->default(false),

            Textarea::make('Description')
                ->rules('required')
                ->hideFromIndex(),

            KeyValue::make('Skills Used')
                ->keyLabel('Skill')
                ->valueLabel('Description')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Achievements')
                ->keyLabel('Achievement')
                ->valueLabel('Description')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
}
````

## File: app/Providers/NovaServiceProvider.php
````php
<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Nova::withBreadcrumbs();

        Nova::footer(function (Request $request) {
            return Blade::render('
                <div class="mt-8 leading-normal text-xs text-gray-500 space-y-1">
                    <p class="text-center">© 2025 Attentiv Development.</p>
                    <p class="text-center text-xxs">Powered by <a class="link-default" href="https://nova.laravel.com">Laravel Nova</a> · v5.4.3 (Silver Surfer)</p>
                </div>
            ');
        });
    }

    /**
     * Register the configurations for Laravel Fortify.
     */
    protected function fortify(): void
    {
        Nova::fortify()
            ->features([
                Features::updatePasswords(),
                // Features::emailVerification(),
                // Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
            ])
            ->register();
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes(default: true)
            ->withPasswordResetRoutes()
            ->withoutEmailVerificationRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewNova', function (User $user) {
            return true;

//            return in_array($user->email, [
//                'nathaniel@attentiv.dev',
//            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Dashboard>
     */
    protected function dashboards(): array
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Tool>
     */
    public function tools(): array
    {
        return [];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();

        Nova::initialPath('/resources/users');

        Nova::report(function ($exception) {
            Log::error($exception->getMessage(), [
                'exception' => $exception,
                'stack' => $exception->getTraceAsString(),
            ]);
        });
    }
}
````

## File: app/Services/RulesService.php
````php
<?php

namespace App\Services;

use App\Models\Rule;

class RulesService
{
    /**
     * Get all rules or filter by type
     *
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRules(string $type = null)
    {
        if ($type) {
            return Rule::where('type', $type)
                ->orWhere('type', 'both')
                ->orderBy('importance', 'desc')
                ->get();
        }

        return Rule::orderBy('importance', 'desc')->get();
    }

    /**
     * Validate content against applicable rules
     *
     * @param string $content
     * @param string $type
     * @param array $metadata
     * @return array
     */
    public function validateContent(string $content, string $type, array $metadata = [])
    {
        $rules = $this->getAllRules($type);
        $results = [];

        foreach ($rules as $rule) {
            $results[$rule->id] = [
                'rule' => $rule->name,
                'passed' => $this->checkRule($content, $rule, $metadata),
                'importance' => $rule->importance,
            ];
        }

        return $results;
    }

    /**
     * Check content against a specific rule
     *
     * @param string $content
     * @param Rule $rule
     * @param array $metadata
     * @return bool
     */
    public function checkRule(string $content, Rule $rule, array $metadata)
    {
        // If no validation logic, default to pass
        if (empty($rule->validation_logic)) {
            return true;
        }

        if(is_string($rule->validation_logic)) {
            $logic = json_decode($rule->validation_logic, true);
        } else {
            $logic = $rule->validation_logic ?? [];
        }
        if (!$logic || !isset($logic['type'])) {
            return true;
        }

        switch ($logic['type']) {
            case 'word_count':
                return $this->checkWordCount($content, $logic);

            case 'page_count':
                return $this->checkPageCount($content, $logic, $metadata);

            case 'keyword_inclusion':
                return $this->checkKeywordInclusion($content, $logic, $metadata);

            case 'contact_info':
                return $this->checkContactInfo($content, $metadata);

            case 'passive_language':
                return $this->checkPassiveLanguage($content);

            case 'personal_pronouns':
                return $this->checkPersonalPronouns($content);

            case 'quantifiable_results':
                return $this->checkQuantifiableResults($content);

            case 'grammar_spelling':
                return $this->checkGrammarSpelling($content);

            case 'formatting_consistency':
                return $this->checkFormattingConsistency($content);

            default:
                return true;
        }
    }

    /**
     * Check if content meets word count requirements
     */
    private function checkWordCount(string $content, array $logic): bool
    {
        $wordCount = str_word_count($content);
        $min = $logic['min'] ?? 0;
        $max = $logic['max'] ?? PHP_INT_MAX;

        return $wordCount >= $min && $wordCount <= $max;
    }

    /**
     * Estimate if content meets page count requirements
     */
    private function checkPageCount(string $content, array $logic, array $metadata): bool
    {
        // Estimate page count based on word count
        // Assuming ~450 words per page for resume/cover letter
        $wordCount = str_word_count($content);
        $estimatedPages = ceil($wordCount / 450);

        $min = $logic['min'] ?? 1;
        $max = $logic['max'] ?? PHP_INT_MAX;

        return $estimatedPages >= $min && $estimatedPages <= $max;
    }

    /**
     * Check if content includes required keywords
     */
    private function checkKeywordInclusion(string $content, array $logic, array $metadata): bool
    {
        if (!isset($logic['keywords']) || !is_array($logic['keywords'])) {
            return true;
        }

        // Extract keywords from job description if available
        $jobDescription = $metadata['job_post']['job_description'] ?? '';
        $requiredKeywords = $logic['keywords'] ?? [];

        // If job description is available, extract keywords from it
        if (!empty($jobDescription) && empty($requiredKeywords)) {
            // Simple keyword extraction - extract nouns and skills
            // This is simplified - in production, you'd want more robust extraction
            $requiredKeywords = $this->extractKeywordsFromJobDescription($jobDescription);
        }

        $contentLowercase = strtolower($content);
        $matchCount = 0;

        foreach ($requiredKeywords as $keyword) {
            if (strpos($contentLowercase, strtolower($keyword)) !== false) {
                $matchCount++;
            }
        }

        // Require at least 70% of keywords to match
        $requiredMatches = max(1, round(count($requiredKeywords) * 0.7));
        return $matchCount >= $requiredMatches;
    }

    /**
     * Extract keywords from job description
     */
    private function extractKeywordsFromJobDescription(string $jobDescription): array
    {
        // This is a simplified implementation
        // In production, use NLP libraries for better extraction
        $words = str_word_count($jobDescription, 1);
        $stopWords = ['the', 'and', 'a', 'to', 'of', 'in', 'for', 'with', 'on', 'at'];

        return array_diff($words, $stopWords);
    }

    /**
     * Check if content includes contact information
     */
    private function checkContactInfo(string $content, array $metadata): bool
    {
        $user = $metadata['user'] ?? null;
        if (!$user) {
            return true;
        }

        // Check if basic contact info is included
        $contactInfoPresent = 0;

        if (strpos($content, $user['email']) !== false) {
            $contactInfoPresent++;
        }

        if (!empty($user['phone_number']) && strpos($content, $user['phone_number']) !== false) {
            $contactInfoPresent++;
        }

        if (!empty($user['location']) && strpos($content, $user['location']) !== false) {
            $contactInfoPresent++;
        }

        // Require at least 2 contact methods
        return $contactInfoPresent >= 2;
    }

    /**
     * Check for passive language
     */
    private function checkPassiveLanguage(string $content): bool
    {
        // Passive voice constructions to look for
        $passiveConstructions = [
            'is done', 'are done', 'was done', 'were done',
            'has been', 'have been', 'had been',
            'is being', 'are being', 'was being', 'were being',
            'will be', 'would be'
        ];

        $contentLowercase = strtolower($content);
        $passiveCount = 0;

        foreach ($passiveConstructions as $construction) {
            $passiveCount += substr_count($contentLowercase, $construction);
        }

        // For a resume/cover letter, passive voice should be minimal
        // Allow up to 3 instances in the entire document
        return $passiveCount <= 3;
    }

    /**
     * Check for personal pronouns usage
     */
    private function checkPersonalPronouns(string $content): bool
    {
        $contentLowercase = strtolower($content);
        $pronouns = ['i', 'me', 'my', 'mine', 'myself'];

        $pronounCount = 0;
        foreach ($pronouns as $pronoun) {
            // Check for whole word matches only
            preg_match_all('/\b' . $pronoun . '\b/', $contentLowercase, $matches);
            $pronounCount += count($matches[0]);
        }

        // For a resume, personal pronouns should be minimal or absent
        // For a cover letter, some pronouns are acceptable
        return $pronounCount <= 5;
    }

    /**
     * Check for quantifiable results
     */
    private function checkQuantifiableResults(string $content): bool
    {
        // Look for numbers and percentages as indicators of quantified achievements
        preg_match_all('/\d+%|\d+\s*percent|\$\d+|\d+\s*million|\d+\s*billion|\d+\s*thousand/i', $content, $matches);

        // Should have at least 3 quantified achievements
        return count($matches[0]) >= 3;
    }

    /**
     * Basic check for potential grammar/spelling issues
     */
    private function checkGrammarSpelling(string $content): bool
    {
        // This is a simplified implementation
        // In production, you'd integrate with a grammar/spell checking API

        // Common grammar errors to check
        $commonErrors = [
            'their/there/they\'re',
            'you\'re/your',
            'its/it\'s',
            'affect/effect',
            'then/than',
            'to/too/two',
            'alot', // should be "a lot"
            'seperate', // should be "separate"
            'definately', // should be "definitely"
            'recieve', // should be "receive"
        ];

        $contentLowercase = strtolower($content);
        $errorCount = 0;

        foreach ($commonErrors as $error) {
            if (strpos($contentLowercase, $error) !== false) {
                $errorCount++;
            }
        }

        // For a professional document, there should be no obvious errors
        return $errorCount === 0;
    }

    /**
     * Check for consistent formatting
     */
    private function checkFormattingConsistency(string $content): bool
    {
        // This is challenging to implement without parsing the actual document
        // For a basic implementation, we'll check for consistent use of bullet points

        $bulletPoints = [
            '• ', '- ', '* ', '· ',
            '→ ', '✓ ', '✔ ', '→ ',
            '○ ', '◆ ', '□ ', '■ '
        ];

        $bulletCounts = [];
        foreach ($bulletPoints as $bullet) {
            $bulletCounts[$bullet] = substr_count($content, $bullet);
        }

        // Remove zero counts
        $bulletCounts = array_filter($bulletCounts);

        // If multiple bullet styles are used, it's inconsistent
        return count($bulletCounts) <= 1;
    }
}
````

## File: database/factories/UserFactory.php
````php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'profile_photo_url' => fake()->imageUrl(200, 200, 'people'),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'location' => fake()->city() . ', ' . fake()->stateAbbr() . ', USA',
            'linkedin_url' => 'https://linkedin.com/in/' . fake()->userName(),
            'github_url' => 'https://github.com/' . fake()->userName(),
            'personal_website_url' => 'https://' . fake()->domainName(),
            'portfolio_url' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
````

## File: database/migrations/0001_01_01_000000_create_users_table.php
````php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('profile_photo_url')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('location')->nullable(); // City, State, Country
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('personal_website_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
````

## File: database/seeders/OpenAIPromptsSeeder.php
````php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OpenAIPrompt;

class OpenAIPromptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prompts = [
            [
                'name' => 'resume_generation',
                'type' => 'resume',
                'prompt_template' => "You are an expert resume writer. Create a tailored resume for the following job posting based on the user's information. Follow all the rules provided.

Job Details:
{{job_data}}

User Information:
{{user_data}}

Resume Rules:
{{rules}}

The resume should be formatted in a clean, professional way. Use bullet points for achievements and responsibilities. Quantify achievements wherever possible. Tailor the content to match keywords from the job description. The resume should be between {{job_post.resume_min_words}} and {{job_post.resume_max_words}} words.",
                'parameters' => ['job_data', 'user_data', 'rules', 'job_post'],
                'model' => 'gpt-4o',
                // 'max_tokens' => 2000,
                'max_tokens' => 6000,
                'temperature' => 0.7,
                'active' => true
            ],
            [
                'name' => 'cover_letter_generation',
                'type' => 'cover_letter',
                'prompt_template' => "You are an expert cover letter writer. Create a compelling cover letter for the following job posting based on the user's information. Follow all the rules provided.

Job Details:
{{job_data}}

User Information:
{{user_data}}

Cover Letter Rules:
{{rules}}

The cover letter should be one page maximum, demonstrate enthusiasm for the company, and clearly connect the user's experience to the job requirements. Begin with a strong hook and address the hiring manager by name if available. The cover letter should be between {{job_post.cover_letter_min_words}} and {{job_post.cover_letter_max_words}} words.",
                'parameters' => ['job_data', 'user_data', 'rules', 'job_post'],
                'model' => 'gpt-4o',
                // 'max_tokens' => 2000,
                'max_tokens' => 6000,
                'temperature' => 0.7,
                'active' => true
            ],
            [
                'name' => 'rule_compliance_check',
                'type' => 'rule_check',
                'prompt_template' => "You are an expert at evaluating resumes and cover letters. Review the following document and determine if it complies with the provided rules. For each rule, provide a yes/no answer and a brief explanation.

Document:
{{document_content}}

Document Type: {{document_type}}

Rules to Check:
{{rules}}

For each rule, provide an assessment in the following format:
Rule: [Rule Name]
Compliant: [Yes/No]
Explanation: [Brief explanation]",
                'parameters' => ['document_content', 'document_type', 'rules'],
                'model' => 'gpt-4o',
                // 'max_tokens' => 1000,
                'max_tokens' => 6000,
                'temperature' => 0.3,
                'active' => true
            ],
            [
                // Job Post Analysis Prompt

                'name' => 'Job Post Analysis',
                'type' => 'analysis',

                'model' => 'gpt-4o',
                'max_tokens' => 16384,

                // 'model' => 'gpt-4o-mini',
                // 'max_tokens' => 16384,

                // 'model' => 'o3-mini',
                // 'max_tokens' => 100000,

                // 'model' => 'o1',
                // 'max_tokens' => 100000,

                // 'model' => 'o1-pro', // SUPER COSTLY BUT SUPER SMART
                // 'max_tokens' => 100000,
//
//                'model' => 'gpt-4.5-preview', // SUPER COSTLY BUT SUPER SMART
//                'max_tokens' => 16384,

                'temperature' => 0.2,

                'active' => true,

                'prompt_template' => <<<EOT
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
        EOT,
                'parameters' => [
                    'job_content',
//                    'job_content' => [
//                        'type' => 'string',
//                        'description' => 'HTML or markdown content of the job posting',
//                    ]
                ]
            ]
        ];

        foreach ($prompts as $prompt) {
            OpenAIPrompt::create($prompt);
        }
    }
}
````

## File: database/seeders/UserSeeder.php
````php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Nathaniel',
            'last_name' => 'Williams',
            'email' => 'nathaniel@attentiv.dev',
            'password' => Hash::make('propel42'),
            'location' => 'Boca Raton, FL, USA',
            'phone_number' => '(330) 458-9393',
            'date_of_birth' => '1992-07-08',
            'profile_photo_url' => 'https://media.licdn.com/dms/image/v2/C4D03AQElW2erCpRQww/profile-displayphoto-shrink_800_800/profile-displayphoto-shrink_800_800/0/1565713960651?e=1749081600&v=beta&t=wdsEcXFxcV7fyUGMVyuF7ZUBfwo-QcspH2kzrO8_9Ps',
            'linkedin_url' => 'https://linkedin.com/in/attentivnate/',
            'github_url' => 'https://github.com/gluebag',
            'personal_website_url' => 'https://attentiv.dev',
        ]);

        // You can add more users if needed
    }
}
````

## File: routes/console.php
````php
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('telescope:prune --hours=48')->daily();


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
````

## File: tests/Feature/ExampleTest.php
````php
<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }
}
````

## File: tests/Unit/GenerationServiceTest.php
````php
<?php

namespace Tests\Unit;

use App\Models\CoverLetter;
use App\Models\JobPost;
use App\Models\Resume;
use App\Models\User;
use App\Services\GenerationService;
use App\Services\OpenAIService;
use App\Services\PDFService;
use App\Services\RulesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GenerationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $openAIServiceMock;
    protected $rulesServiceMock;
    protected $pdfServiceMock;
    protected $generationService;
    protected $user;
    protected $jobPost;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mocks
        $this->openAIServiceMock = Mockery::mock(OpenAIService::class);
        $this->rulesServiceMock = Mockery::mock(RulesService::class);
        $this->pdfServiceMock = Mockery::mock(PDFService::class);
        
        // Fix: Create the service with correct property names
        $this->generationService = new GenerationService(
            $this->openAIServiceMock,
            $this->rulesServiceMock,
            $this->pdfServiceMock
        );
        
        // Inject the mocks into the service with reflection to match property names
        $reflection = new \ReflectionClass($this->generationService);
        
        $openAIProp = $reflection->getProperty('openAI');
        $openAIProp->setAccessible(true);
        $openAIProp->setValue($this->generationService, $this->openAIServiceMock);
        
        $rulesProp = $reflection->getProperty('rules');
        $rulesProp->setAccessible(true);
        $rulesProp->setValue($this->generationService, $this->rulesServiceMock);
        
        $pdfProp = $reflection->getProperty('pdf');
        $pdfProp->setAccessible(true);
        $pdfProp->setValue($this->generationService, $this->pdfServiceMock);

        // Create test user
        $this->user = User::factory()->create();

        // Create test job post
        $this->jobPost = JobPost::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_generate_resume()
    {
        // Set up expectations for the mocks
        $this->openAIServiceMock->shouldReceive('generateResume')
            ->once()
            ->with(Mockery::type(JobPost::class), Mockery::type(User::class), null, [])
            ->andReturn([
                'content' => 'Generated resume content',
                'metadata' => ['model' => 'gpt-4', 'usage' => ['total_tokens' => 500]]
            ]);

        $this->rulesServiceMock->shouldReceive('validateContent')
            ->once()
            ->andReturn(['rule1' => ['passed' => true]]);

        $this->pdfServiceMock->shouldReceive('generateResumePDF')
            ->once()
            ->andReturn('pdfs/resume_1_12345.pdf');

        $resume = $this->generationService->generateResume($this->jobPost);

        $this->assertInstanceOf(Resume::class, $resume);
        $this->assertEquals('Generated resume content', $resume->content);
        $this->assertEquals($this->user->id, $resume->user_id);
        $this->assertEquals($this->jobPost->id, $resume->job_post_id);
    }

    public function test_can_generate_cover_letter()
    {
        // Set up expectations for the mocks
        $this->openAIServiceMock->shouldReceive('generateCoverLetter')
            ->once()
            ->with(Mockery::on(function ($job) {
                return $job instanceof JobPost;
            }), Mockery::on(function ($user) {
                return $user instanceof User;
            }))
            ->andReturn([
                'content' => 'Generated cover letter content',
                'metadata' => ['model' => 'gpt-4', 'usage' => ['total_tokens' => 400]]
            ]);

        $this->rulesServiceMock->shouldReceive('validateContent')
            ->once()
            ->andReturn(['rule1' => ['passed' => true]]);

        $this->pdfServiceMock->shouldReceive('generateCoverLetterPDF')
            ->once()
            ->andReturn('pdfs/cover_letter_1_12345.pdf');

        $coverLetter = $this->generationService->generateCoverLetter($this->jobPost);

        $this->assertInstanceOf(CoverLetter::class, $coverLetter);
        $this->assertEquals('Generated cover letter content', $coverLetter->content);
        $this->assertEquals($this->user->id, $coverLetter->user_id);
        $this->assertEquals($this->jobPost->id, $coverLetter->job_post_id);
    }

    public function test_can_regenerate_with_feedback()
    {
        // Create test resume
        $resume = Resume::factory()->create([
            'user_id' => $this->user->id,
            'job_post_id' => $this->jobPost->id,
            'content' => 'Original resume content'
        ]);

        // Set up expectations for the mocks
        $this->openAIServiceMock->shouldReceive('generateResume')
            ->once()
            ->withArgs(function ($jobPost, $user, $promptName, $extraContext) {
                return $jobPost instanceof JobPost &&
                       $user instanceof User && 
                       $extraContext['feedback'] === 'Make it better' &&
                       $extraContext['previous_content'] === 'Original resume content';
            })
            ->andReturn([
                'content' => 'Improved resume content',
                'metadata' => ['model' => 'gpt-4', 'usage' => ['total_tokens' => 600]]
            ]);

        $this->rulesServiceMock->shouldReceive('validateContent')
            ->once()
            ->andReturn(['rule1' => ['passed' => true]]);

        $this->pdfServiceMock->shouldReceive('generateResumePDF')
            ->once()
            ->andReturn('pdfs/resume_1_67890.pdf');

        $regenerated = $this->generationService->regenerateWithFeedback($resume, [
            'feedback' => 'Make it better'
        ]);

        $this->assertInstanceOf(Resume::class, $regenerated);
        $this->assertEquals('Improved resume content', $regenerated->content);
        $this->assertEquals($this->user->id, $regenerated->user_id);
        $this->assertEquals($this->jobPost->id, $regenerated->job_post_id);
        $this->assertNull($regenerated->file_path); // Should be reset so PDF is regenerated
    }
}
````

## File: tests/Unit/PDFServiceTest.php
````php
<?php

namespace Tests\Unit;

use App\Models\CoverLetter;
use App\Models\JobPost;
use App\Models\Resume;
use App\Models\User;
use App\Services\PDFService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class PDFServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $pdfService;
    protected $user;
    protected $jobPost;
    protected $resume;
    protected $coverLetter;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->pdfService = new PDFService();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test job post
        $this->jobPost = JobPost::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        // Create test resume
        $this->resume = Resume::factory()->create([
            'user_id' => $this->user->id,
            'job_post_id' => $this->jobPost->id,
            'content' => 'This is a test resume content'
        ]);
        
        // Create test cover letter
        $this->coverLetter = CoverLetter::factory()->create([
            'user_id' => $this->user->id,
            'job_post_id' => $this->jobPost->id,
            'content' => 'This is a test cover letter content'
        ]);
        
        // Mock the storage
        Storage::fake('public');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_generate_resume_pdf()
    {
        // Create a proper PDF mock 
        $pdf = $this->createMock(\Barryvdh\DomPDF\PDF::class);
        $pdf->method('output')->willReturn('PDF Content');
        
        // Mock the PDF facade
        Pdf::shouldReceive('loadView')
            ->once()
            ->andReturn($pdf);
        
        // Don't mock Storage here - use the fake disk from setUp
        $path = $this->pdfService->generateResumePDF($this->resume);
        
        // Check resume was updated
        $this->resume->refresh();
        $this->assertEquals($path, $this->resume->file_path);
    }
    
    public function test_can_generate_cover_letter_pdf()
    {
        // Create a proper PDF mock
        $pdf = $this->createMock(\Barryvdh\DomPDF\PDF::class);
        $pdf->method('output')->willReturn('PDF Content');
        
        // Mock the PDF facade
        Pdf::shouldReceive('loadView')
            ->once()
            ->andReturn($pdf);
        
        // Don't mock Storage here - use the fake disk from setUp
        $path = $this->pdfService->generateCoverLetterPDF($this->coverLetter);
        
        // Check cover letter was updated
        $this->coverLetter->refresh();
        $this->assertEquals($path, $this->coverLetter->file_path);
    }
    
    public function test_can_detect_markdown_content()
    {
        $markdownContent = "# Heading\n\n- List item 1\n- List item 2\n\n**Bold text**";
        $regularContent = "This is just regular text without any markdown.";
        
        // Use reflection to access private method
        $reflection = new \ReflectionClass($this->pdfService);
        $method = $reflection->getMethod('isMarkdown');
        $method->setAccessible(true);
        
        $this->assertTrue($method->invoke($this->pdfService, $markdownContent));
        $this->assertFalse($method->invoke($this->pdfService, $regularContent));
    }
}
````

## File: tests/Unit/RulesServiceTest.php
````php
<?php

namespace Tests\Unit;

use App\Models\Rule;
use App\Services\RulesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RulesServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var RulesService
     */
    protected $rulesService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->rulesService = new RulesService();
        
        // Create some test rules
        Rule::create([
            'name' => 'Word Count Test',
            'description' => 'Test rule for word count',
            'type' => 'resume',
            'source' => 'Tests',
            'importance' => 10,
            'validation_logic' => json_encode(['type' => 'word_count', 'min' => 100, 'max' => 500])
        ]);
        
        Rule::create([
            'name' => 'Page Count Test',
            'description' => 'Test rule for page count',
            'type' => 'cover_letter',
            'source' => 'Tests',
            'importance' => 8,
            'validation_logic' => json_encode(['type' => 'page_count', 'max' => 1])
        ]);
        
        Rule::create([
            'name' => 'Both Types Test',
            'description' => 'Test rule for both document types',
            'type' => 'both',
            'source' => 'Tests',
            'importance' => 5,
            'validation_logic' => null
        ]);
    }

    public function test_can_get_all_rules()
    {
        $rules = $this->rulesService->getAllRules();
        
        $this->assertCount(3, $rules);
        $this->assertEquals('Word Count Test', $rules->first()->name);
    }
    
    public function test_can_filter_rules_by_type()
    {
        $resumeRules = $this->rulesService->getAllRules('resume');
        
        // Should include 'resume' and 'both' types
        $this->assertCount(2, $resumeRules);
        
        $coverLetterRules = $this->rulesService->getAllRules('cover_letter');
        
        // Should include 'cover_letter' and 'both' types
        $this->assertCount(2, $coverLetterRules);
    }
    
    public function test_can_validate_word_count_rule()
    {
        $shortText = 'This is a short text with only a few words.';
        $appropriateText = str_repeat('Lorem ipsum dolor sit amet. ', 20); // ~100 words
        $longText = str_repeat('Lorem ipsum dolor sit amet. ', 120); // ~600 words
        
        $rule = Rule::where('name', 'Word Count Test')->first();
        
        // Should fail (too short)
        $this->assertFalse($this->rulesService->checkRule($shortText, $rule, []));
        
        // Should pass
        $this->assertTrue($this->rulesService->checkRule($appropriateText, $rule, []));
        
        // Should fail (too long)
        $this->assertFalse($this->rulesService->checkRule($longText, $rule, []));
    }
    
    public function test_validate_content_returns_results()
    {
        $text = str_repeat('Lorem ipsum dolor sit amet. ', 20); // ~100 words
        
        $results = $this->rulesService->validateContent($text, 'resume');
        
        $this->assertIsArray($results);
        $this->assertCount(2, $results); // Should have results for resume rules
        
        foreach ($results as $result) {
            $this->assertArrayHasKey('rule', $result);
            $this->assertArrayHasKey('passed', $result);
            $this->assertArrayHasKey('importance', $result);
        }
    }
}
````

## File: tests/TestCase.php
````php
<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // run seeders



}
````

## File: app/Nova/User.php
````php
<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    use PasswordValidationRules;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return \Stringable|string
     */
    public function title()
    {
        // return full name aka first name + last name
        return $this->first_name . ' ' . $this->last_name;
    }

    public function subtitle()
    {
        return $this->email;
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Users');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('User');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'last_name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Gravatar::make()
                ->maxWidth(50),

            Text::make('First Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Last Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Name', function () {
                return $this->first_name . ' ' . $this->last_name;
            })->onlyOnIndex(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules($this->passwordRules())
                ->updateRules($this->optionalPasswordRules()),

            Date::make('Date of Birth')->nullable(),

            Text::make('Phone Number')->nullable(),

            Text::make('Location')->nullable(),

            Text::make('LinkedIn', 'linkedin_url')
                // ->hideFromIndex()
                ->displayUsing(function ($value) {
                    $username = Str::after($value, 'linkedin.com/in/');
                    $username = Str::before($username, '/');
                    return "<a href='{$value}' target='_blank'>@{$username}</a>";
                })
                ->asHtml()
                ->nullable(),

            Text::make('GitHub', 'github_url')
                // ->hideFromIndex()
                ->displayUsing(function ($value) {
                    $username = Str::after($value, 'github.com/');
                    $username = Str::before($username, '/');
                    return "<a href='{$value}' target='_blank'>@{$username}</a>";
                })
                ->asHtml()
                ->nullable(),

            Text::make('Personal Website URL')
                ->hideFromIndex()
                ->copyable()
                ->nullable(),

            Text::make('Portfolio URL')
                 ->hideFromIndex()
                ->copyable()
                ->nullable(),

            HasMany::make('Work Experiences', 'workExperiences', WorkExperience::class),
            HasMany::make('Education', 'education', Education::class),
            HasMany::make('Skills', 'skills', Skill::class),
            HasMany::make('Projects', 'projects', Project::class),
            HasMany::make('Job Posts', 'jobPosts', JobPost::class),
            HasMany::make('Resumes', 'resumes', Resume::class),
            HasMany::make('Cover Letters', 'coverLetters', CoverLetter::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
````

## File: app/Services/OpenAIService.php
````php
<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use App\Models\OpenAIPrompt;
use App\Models\User;
use App\Models\JobPost;
use App\Services\RulesService;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $rulesService;

    /**
     * The OpenAI API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The OpenAI API URL
     *
     * @var string
     */
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct(RulesService $rulesService)
    {
        $this->rulesService = $rulesService;
        $this->apiKey = config('services.openai.api_key');

        if (empty($this->apiKey)) {
            throw new Exception('OpenAI API key is not configured');
        }
    }

    /**
     * Generate a resume based on job post and user data
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param string|null $promptName
     * @param array $extraContext Additional context for the prompt (like feedback)
     * @return array
     * @throws Exception
     */
    public function generateResume(JobPost $jobPost, User $user, ?string $promptName = null, array $extraContext = [])
    {
        $promptName = $promptName ?? 'resume_generation';
        $prompt = $this->getPrompt($promptName);

        if (!$prompt) {
            throw new Exception("Prompt not found: {$promptName}");
        }

        // Prepare context data
        $jobData = $this->prepareJobData($jobPost);
        $userData = $this->prepareUserData($user);
        $rules = $this->rulesService->getAllRules('resume');
        $rulesText = $this->prepareRulesText($rules);

        // Replace placeholders in the prompt template
        $finalPrompt = $this->replacePlaceholders($prompt->prompt_template, [
            'job_data' => $jobData,
            'user_data' => $userData,
            'rules' => $rulesText,
            'job_post' => $jobPost->toArray()
        ]);

        // Add feedback for regeneration if available
        if (!empty($extraContext['feedback'])) {
            $finalPrompt .= "\n\nFeedback for improvement:\n" . $extraContext['feedback'];

            if (!empty($extraContext['previous_content'])) {
                $finalPrompt .= "\n\nPrevious version:\n" . $extraContext['previous_content'];
            }
        }

        // Call OpenAI API
        $result = $this->callOpenAI($prompt->model, $finalPrompt, $prompt->max_tokens, $prompt->temperature);

        // Return generated content
        return [
            'content' => $result->choices[0]->message->content,
            'metadata' => [
                'model' => $prompt->model,
                'usage' => $result->usage->toArray(),
                'created_at' => now(),
                'extra_context' => $extraContext,
            ],
        ];
    }

    /**
     * Generate a cover letter based on job post and user data
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param string|null $promptName
     * @param array $extraContext Additional context for the prompt (like feedback)
     * @return array
     * @throws Exception
     */
    public function generateCoverLetter(JobPost $jobPost, User $user, ?string $promptName = null, array $extraContext = [])
    {
        $promptName = $promptName ?? 'cover_letter_generation';
        $prompt = $this->getPrompt($promptName);

        if (!$prompt) {
            throw new Exception("Prompt not found: {$promptName}");
        }

        // Prepare context data
        $jobData = $this->prepareJobData($jobPost);
        $userData = $this->prepareUserData($user);
        $rules = $this->rulesService->getAllRules('cover_letter');
        $rulesText = $this->prepareRulesText($rules);

        // Replace placeholders in the prompt template
        $finalPrompt = $this->replacePlaceholders($prompt->prompt_template, [
            'job_data' => $jobData,
            'user_data' => $userData,
            'rules' => $rulesText,
            'job_post' => $jobPost->toArray()
        ]);

        // Add feedback for regeneration if available
        if (!empty($extraContext['feedback'])) {
            $finalPrompt .= "\n\nFeedback for improvement:\n" . $extraContext['feedback'];

            if (!empty($extraContext['previous_content'])) {
                $finalPrompt .= "\n\nPrevious version:\n" . $extraContext['previous_content'];
            }
        }

        // Call OpenAI API
        $result = $this->callOpenAI($prompt->model, $finalPrompt, $prompt->max_tokens, $prompt->temperature);

        // Return generated content
        return [
            'content' => $result->choices[0]->message->content,
            'metadata' => [
                'model' => $prompt->model,
                'usage' => $result->usage->toArray(),
                'created_at' => now(),
                'extra_context' => $extraContext,
            ],
        ];
    }

    /**
     * Check if content follows specific rules using OpenAI
     *
     * @param string $content
     * @param string $type
     * @param array $rules
     * @return array
     * @throws Exception
     */
    public function checkRuleCompliance(string $content, string $type, $rules)
    {
        $prompt = $this->getPrompt('rule_compliance_check');

        if (!$prompt) {
            throw new Exception("Prompt not found: rule_compliance_check");
        }

        $rulesText = $this->prepareRulesText($rules);

        // Replace placeholders in the prompt template
        $finalPrompt = $this->replacePlaceholders($prompt->prompt_template, [
            'document_content' => $content,
            'document_type' => $type,
            'rules' => $rulesText,
        ]);

        // Call OpenAI API with lower temperature for more deterministic response
        $result = $this->callOpenAI($prompt->model, $finalPrompt, $prompt->max_tokens, 0.3);

        // Parse the response to extract rule compliance results
        return [
            'analysis' => $result->choices[0]->message->content,
            'metadata' => [
                'model' => $prompt->model,
                'usage' => $result->usage->toArray(),
                'created_at' => now(),
            ],
        ];
    }

    /**
     * Get a prompt by name
     *
     * @param string $name
     * @return OpenAIPrompt|null
     */
    protected function getPrompt(string $name)
    {
        return OpenAIPrompt::where('name', $name)
            ->where('active', true)
            ->first();
    }

    /**
     * Prepare job data for the prompt
     *
     * @param JobPost $jobPost
     * @return string
     */
    protected function prepareJobData(JobPost $jobPost)
    {
        $data = [
            "Company: {$jobPost->company_name}",
            "Job Title: {$jobPost->job_title}",
            "Job Description: {$jobPost->job_description}",
            "Job Location Type: {$jobPost->job_location_type}",
            "Position Level: {$jobPost->position_level}",
            "Job Type: {$jobPost->job_type}",
        ];

        // Add required skills if available
        if (!empty($jobPost->required_skills)) {
            if(is_string($jobPost->required_skills)) {
                $jobPost->required_skills = json_decode($jobPost->required_skills, true);
            }
            $data[] = "Required Skills: " . implode(", ", $jobPost->required_skills);
        }

        // Add preferred skills if available
        if (!empty($jobPost->preferred_skills)) {
            if(is_string($jobPost->preferred_skills)) {
                $jobPost->preferred_skills = json_decode($jobPost->preferred_skills, true);
            }
            $data[] = "Preferred Skills: " . implode(", ", $jobPost->preferred_skills);
        }

        // Add required experience if available
        if (!empty($jobPost->required_experience)) {
            if(is_string($jobPost->required_experience)) {
                $jobPost->required_experience = json_decode($jobPost->required_experience, true);
            }
            $data[] = "Required Experience: " . implode(", ", $jobPost->required_experience);
        }

        // Add required education if available
        if (!empty($jobPost->required_education)) {
            if(is_string($jobPost->required_education)) {
                $jobPost->required_education = json_decode($jobPost->required_education, true);
            }
            $data[] = "Required Education: " . implode(", ", $jobPost->required_education);
        }

        return implode("\n", $data);
    }

    /**
     * Prepare user data for the prompt
     *
     * @param User $user
     * @return string
     */
    protected function prepareUserData(User $user)
    {
        $data = [
            "Name: {$user->first_name} {$user->last_name}",
            "Email: {$user->email}",
            "Phone: {$user->phone_number}",
            "Location: {$user->location}",
        ];

        // Add LinkedIn URL if available
        if (!empty($user->linkedin_url)) {
            $data[] = "LinkedIn: {$user->linkedin_url}";
        }

        // Add GitHub URL if available
        if (!empty($user->github_url)) {
            $data[] = "GitHub: {$user->github_url}";
        }

        // Add personal website URL if available
        if (!empty($user->personal_website_url)) {
            $data[] = "Website: {$user->personal_website_url}";
        }

        // Add portfolio URL if available
        if (!empty($user->portfolio_url)) {
            $data[] = "Portfolio: {$user->portfolio_url}";
        }

        // Add work experiences
        $data[] = "\nWork Experience:";
        $workExperiences = $user->workExperiences()->orderBy('start_date', 'desc')->get();

        foreach ($workExperiences as $exp) {
            $endDate = $exp->current_job ? "Present" : $exp->end_date->format('M Y');
            $data[] = "- {$exp->position} at {$exp->company_name} ({$exp->start_date->format('M Y')} - {$endDate})";
            $data[] = "  {$exp->description}";

            if (!empty($exp->achievements)) {
                $data[] = "  Achievements:";
                foreach ($exp->achievements as $achievement => $description) {
                    $data[] = "  - {$achievement}: {$description}";
                }
            }
        }

        // Add education
        $data[] = "\nEducation:";
        $education = $user->education()->orderBy('start_date', 'desc')->get();

        foreach ($education as $edu) {
            $endDate = $edu->current ? "Present" : $edu->end_date->format('M Y');
            $fieldOfStudy = !empty($edu->field_of_study) ? " in {$edu->field_of_study}" : "";
            $data[] = "- {$edu->degree}{$fieldOfStudy} from {$edu->institution} ({$edu->start_date->format('M Y')} - {$endDate})";

            if (!empty($edu->achievements)) {
                $data[] = "  Achievements:";
                foreach ($edu->achievements as $achievement => $description) {
                    $data[] = "  - {$achievement}: {$description}";
                }
            }
        }

        // Add skills
        $data[] = "\nSkills:";
        $skills = $user->skills()->orderBy('proficiency', 'desc')->get();

        foreach ($skills as $skill) {
            $experience = $skill->years_experience > 0 ? " ({$skill->years_experience} years)" : "";
            $data[] = "- {$skill->name}{$experience}";
        }

        // Add projects
        $data[] = "\nProjects:";
        $projects = $user->projects()->get();

        foreach ($projects as $project) {
            $data[] = "- {$project->name}";
            $data[] = "  {$project->description}";

            if (!empty($project->technologies_used)) {
                $techs = implode(", ", array_keys($project->technologies_used));
                $data[] = "  Technologies: {$techs}";
            }

            if (!empty($project->url)) {
                $data[] = "  URL: {$project->url}";
            }
        }

        return implode("\n", $data);
    }

    /**
     * Prepare rules text for the prompt
     *
     * @param \Illuminate\Database\Eloquent\Collection $rules
     * @return string
     */
    protected function prepareRulesText($rules)
    {
        $rulesText = [];

        foreach ($rules as $rule) {
            $rulesText[] = "Rule: {$rule->name}";
            $rulesText[] = "Description: {$rule->description}";
            $rulesText[] = "Importance: {$rule->importance}/10";
            $rulesText[] = ""; // Empty line between rules
        }

        return implode("\n", $rulesText);
    }

    /**
     * Replace placeholders in prompt template
     *
     * @param string $template
     * @param array $replacements
     * @return string
     */
    protected function replacePlaceholders(string $template, array $replacements)
    {
        $result = $template;

        foreach ($replacements as $key => $value) {
            if (is_array($value)) {
                // Handle nested arrays by replacing dot notation placeholders
                $this->replaceArrayPlaceholders($result, $key, $value);
            } else {
                $result = str_replace("{{" . $key . "}}", $value, $result);
            }
        }

        return $result;
    }

    /**
     * Replace array placeholders in dot notation
     *
     * @param string &$template
     * @param string $prefix
     * @param array $array
     */
    protected function replaceArrayPlaceholders(string &$template, string $prefix, array $array)
    {
        foreach ($array as $key => $value) {
            $placeholder = "{{" . $prefix . "." . $key . "}}";

            if (is_array($value)) {
                $this->replaceArrayPlaceholders($template, $prefix . "." . $key, $value);
            } else {
                $template = str_replace($placeholder, $value, $template);
            }
        }
    }

    /**
     * Call OpenAI API
     *
     * @param string $model
     * @param string $prompt
     * @param int $maxTokens
     * @param float $temperature
     * @return mixed
     */
    protected function callOpenAI(string $model, string $prompt, int $maxTokens, float $temperature)
    {
        return OpenAI::chat()->create([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert resume and cover letter writer who creates perfectly tailored documents for specific job postings.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ]);
    }

    /**
     * Generate a completion using OpenAI API
     *
     * @param OpenAIPrompt $prompt The prompt to use
     * @param array $parameters The parameters to replace in the prompt
     * @return string The generated text
     * @throws Exception If there is an error
     */
    public function generateCompletion(OpenAIPrompt $prompt, array $parameters): string
    {
        set_time_limit(0); // Disable time limit for long requests
        // Replace placeholders in the prompt template
        $promptText = $this->replacePlaceholders($prompt->prompt_template, $parameters);

        // Prepare the request payload
        $payload = [
            'model' => $prompt->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $promptText],
            ],
            'temperature' => (float) $prompt->temperature,
            'max_tokens' => (int) $prompt->max_tokens,
        ];

        // Log the request (omit the API key for security)
        Log::info('OpenAI Request', [
            'model' => $prompt->model,
            'max_tokens' => $prompt->max_tokens,
            'temperature' => $prompt->temperature,
        ]);

        // Make the API request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post($this->apiUrl, $payload);

        // Check if the request was successful
        if (!$response->successful()) {
            $error = $response->json();
            Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'error' => $error,
            ]);

            throw new Exception('OpenAI API Error: ' . ($error['error']['message'] ?? 'Unknown error'));
        }

        // Extract and return the response text
        $responseData = $response->json();
        $content = $responseData['choices'][0]['message']['content'] ?? '';

        // Remove any JSON code block markers if present (```json and ```)
        $content = preg_replace('/```json\s*|\s*```/', '', $content);

        return trim($content);
    }
}
````

## File: database/seeders/DatabaseSeeder.php
````php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RulesSeeder::class,
            OpenAIPromptsSeeder::class,
            OpenAIPromptSeeder::class,
        ]);
    }
}
````

## File: app/Nova/JobPost.php
````php
<?php

namespace App\Nova;

use App\Nova\Actions\ConvertGoogleJobPost;
use App\Nova\Actions\ImportJobPostFromContent;
use App\Nova\Repeaters\EducationItem;
use App\Nova\Repeaters\ExperienceItem;
use App\Nova\Repeaters\SkillItem;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;

class JobPost extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\JobPost>
     */
    public static $model = \App\Models\JobPost::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'job_title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'company_name',
        'job_title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            Text::make('Company Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Job Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Job Description')
                ->rules('required')
                ->hideFromIndex(),

            Repeater::make('Required Education')
                ->repeatables([
                    EducationItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Required Experience')
                ->repeatables([
                    ExperienceItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Required Skills')
                ->repeatables([
                    SkillItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Preferred Skills')
                ->repeatables([
                    SkillItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Text::make('Job Post URL')
                ->hideFromIndex()
                ->nullable(),

            Date::make('Job Post Date')
                ->nullable(),

            Select::make('Job Location Type')
                ->options([
                    'remote' => 'Remote',
                    'in-office' => 'In-Office',
                    'hybrid' => 'Hybrid',
                ])
                ->default('remote'),

            Select::make('Position Level')
                ->options([
                    'entry-level' => 'Entry Level',
                    'mid-level' => 'Mid Level',
                    'senior' => 'Senior',
                    'lead' => 'Lead',
                    'manager' => 'Manager',
                    'director' => 'Director',
                    'executive' => 'Executive',
                ])
                ->default('mid-level'),

            Select::make('Job Type')
                ->options([
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                    'freelance' => 'Freelance',
                ])
                ->default('full-time'),

            Number::make('Resume Min Words')
                ->default(450)
                ->hideFromIndex(),

            Number::make('Resume Max Words')
                ->default(850)
                ->hideFromIndex(),

            Number::make('Cover Letter Min Words')
                ->default(450)
                ->hideFromIndex(),

            Number::make('Cover Letter Max Words')
                ->default(750)
                ->hideFromIndex(),

            Number::make('Resume Min Pages')
                ->default(1)
                ->hideFromIndex(),

            Number::make('Resume Max Pages')
                ->default(2)
                ->hideFromIndex(),

            Number::make('Cover Letter Min Pages')
                ->default(1)
                ->hideFromIndex(),

            Number::make('Cover Letter Max Pages')
                ->default(1)
                ->hideFromIndex(),

            Currency::make('Salary Range Min')
                ->nullable()
                ->hideFromIndex(),

            Currency::make('Salary Range Max')
                ->nullable()
                ->hideFromIndex(),

            Currency::make('Min Acceptable Salary')
                ->nullable()
                ->hideFromIndex(),

            Date::make('Ideal Start Date')
                ->nullable()
                ->hideFromIndex(),

            Number::make('Position Preference')
                ->default(1)
                ->help('1 = top choice, 2 = second choice, etc.')
                ->hideFromIndex(),

            Boolean::make('Open To Travel')
                ->default(true)
                ->hideFromIndex(),

            Boolean::make('First Time Applying')
                ->default(true)
                ->hideFromIndex(),

            Textarea::make('Things I Like')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Dislike')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Like About Company')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Dislike About Company')
                ->nullable()
                ->hideFromIndex(),

            HasMany::make('Resumes'),
            HasMany::make('Cover Letters', 'coverLetters'),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            ConvertGoogleJobPost::make()->standalone(),
            ImportJobPostFromContent::make()->standalone(),
            
            new \App\Nova\Actions\GenerateResume,
            new \App\Nova\Actions\GenerateCoverLetter,
            new \App\Nova\Actions\GenerateApplicationMaterials,
            new \App\Nova\Actions\RegenerateWithFeedback,
        ];
    }
}
````

## File: app/Services/GenerationService.php
````php
<?php

namespace App\Services;

use App\Models\JobPost;
use App\Models\Resume;
use App\Models\CoverLetter;
use App\Models\ThreadSession;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class GenerationService
{
    protected $threadManager;
    protected $promptEngineering;
    protected $embeddings;
    protected $pdf;

    /**
     * Create a new service instance.
     *
     * @param ThreadManagementService $threadManager
     * @param PromptEngineeringService $promptEngineering
     * @param EmbeddingsService $embeddings
     * @param PDFService $pdf
     * @return void
     */
    public function __construct(
        ThreadManagementService $threadManager,
        PromptEngineeringService $promptEngineering,
        EmbeddingsService $embeddings,
        PDFService $pdf
    ) {
        $this->threadManager = $threadManager;
        $this->promptEngineering = $promptEngineering;
        $this->embeddings = $embeddings;
        $this->pdf = $pdf;
    }

    /**
     * Generate a resume for a job post
     *
     * @param JobPost $jobPost
     * @return Resume
     */
    public function generateResume(JobPost $jobPost): Resume
    {
        $user = $jobPost->user;

        try {
            // Step 1: Generate embeddings and find matches
            $matches = $this->embeddings->findSkillMatches($user, $jobPost);
            $recommendations = $this->embeddings->generateRecommendations($user, $jobPost);

            // Step 2: Use multi-step workflow to generate content variations
            $variations = $this->promptEngineering->generateWithMultiStepWorkflow($jobPost, $user, 'resume');

            // Step 3: Select the best variation
            $selectedResult = $this->promptEngineering->selectBestVariation($variations);
            $content = $selectedResult['content'];

            // Step 4: Start a resume generation session with the assistant
            $session = $this->threadManager->startResumeSession($user, $jobPost);

            // Add context from our semantic analysis and variations
            $contextMessage = $this->prepareContextMessage($recommendations, $selectedResult, 'resume');
            $this->threadManager->addMessage($session->thread_id, $contextMessage);

            // Add content to refine
            $this->threadManager->addMessage($session->thread_id, "Here's the content to refine:\n\n" . $content);

            // Generate final content with the assistant
            $finalContent = $this->threadManager->generateContent($session);

            // Count words
            $wordCount = str_word_count(strip_tags($finalContent));

        // Create resume record
        $resume = Resume::create([
            'user_id' => $user->id,
            'job_post_id' => $jobPost->id,
                'thread_session_id' => $session->id,
                'content' => $finalContent,
                'word_count' => $wordCount,
                'skills_included' => array_column($recommendations['key_skills_to_emphasize'] ?? [], 'name'),
                'experiences_included' => $recommendations['achievements_to_highlight'] ?? [],
                'generation_metadata' => [
                    'template_used' => $selectedResult['template'],
                    'variations_score' => $selectedResult['score'],
                    'semantic_match_data' => [
                        'top_matches' => $this->getTopMatches($matches),
                        'recommendations' => $recommendations,
                    ],
                ],
        ]);

        // Generate PDF
        $this->pdf->generateResumePDF($resume);

            // Validate the resume
            $validation = $this->threadManager->validateDocument($finalContent, 'resume', $jobPost);

            // Update resume with validation results
            $resume->update([
                'rule_compliance' => $validation,
            ]);

        return $resume;

        } catch (Exception $e) {
            Log::error("Resume generation failed", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Generate a cover letter for a job post
     *
     * @param JobPost $jobPost
     * @return CoverLetter
     */
    public function generateCoverLetter(JobPost $jobPost): CoverLetter
    {
        $user = $jobPost->user;

        try {
            // Step 1: Generate embeddings and find matches
            $matches = $this->embeddings->findSkillMatches($user, $jobPost);
            $recommendations = $this->embeddings->generateRecommendations($user, $jobPost);

            // Step 2: Use multi-step workflow to generate content variations
            $variations = $this->promptEngineering->generateWithMultiStepWorkflow($jobPost, $user, 'cover_letter');

            // Step 3: Select the best variation
            $selectedResult = $this->promptEngineering->selectBestVariation($variations);
            $content = $selectedResult['content'];

            // Step 4: Start a cover letter generation session with the assistant
            $session = $this->threadManager->startCoverLetterSession($user, $jobPost);

            // Add context from our semantic analysis and variations
            $contextMessage = $this->prepareContextMessage($recommendations, $selectedResult, 'cover_letter');
            $this->threadManager->addMessage($session->thread_id, $contextMessage);

            // Add content to refine
            $this->threadManager->addMessage($session->thread_id, "Here's the content to refine:\n\n" . $content);

            // Generate final content with the assistant
            $finalContent = $this->threadManager->generateContent($session);

            // Count words
            $wordCount = str_word_count(strip_tags($finalContent));

        // Create cover letter record
        $coverLetter = CoverLetter::create([
            'user_id' => $user->id,
            'job_post_id' => $jobPost->id,
                'thread_session_id' => $session->id,
                'content' => $finalContent,
                'word_count' => $wordCount,
                'generation_metadata' => [
                    'template_used' => $selectedResult['template'],
                    'variations_score' => $selectedResult['score'],
                    'semantic_match_data' => [
                        'top_matches' => $this->getTopMatches($matches),
                        'recommendations' => $recommendations,
                    ],
                ],
        ]);

        // Generate PDF
        $this->pdf->generateCoverLetterPDF($coverLetter);

            // Validate the cover letter
            $validation = $this->threadManager->validateDocument($finalContent, 'cover_letter', $jobPost);

            // Update cover letter with validation results
            $coverLetter->update([
                'rule_compliance' => $validation,
            ]);

        return $coverLetter;

        } catch (Exception $e) {
            Log::error("Cover letter generation failed", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Prepare context message for the assistant
     *
     * @param array $recommendations
     * @param array $selectedResult
     * @param string $type
     * @return string
     */
    private function prepareContextMessage(array $recommendations, array $selectedResult, string $type): string
    {
        $message = "I've performed semantic analysis and generated variations for this {$type}. Please use this context to refine the content I'll provide:\n\n";

        $message .= "# Semantic Analysis Recommendations\n";

        if (!empty($recommendations['key_skills_to_emphasize'])) {
            $message .= "## Key Skills to Emphasize\n";
            foreach ($recommendations['key_skills_to_emphasize'] as $skill) {
                $message .= "- {$skill}\n";
            }
            $message .= "\n";
        }

        if (!empty($recommendations['skills_to_reframe'])) {
            $message .= "## Skills to Reframe\n";
            foreach ($recommendations['skills_to_reframe'] as $skill) {
                $message .= "- {$skill}\n";
            }
            $message .= "\n";
        }

        if (!empty($recommendations['gap_mitigation_strategies'])) {
            $message .= "## Gap Mitigation Strategies\n";
            foreach ($recommendations['gap_mitigation_strategies'] as $strategy) {
                $message .= "- {$strategy}\n";
            }
            $message .= "\n";
        }

        if (!empty($recommendations['achievements_to_highlight'])) {
            $message .= "## Achievements to Highlight\n";
            foreach ($recommendations['achievements_to_highlight'] as $achievement) {
                $message .= "- {$achievement}\n";
            }
            $message .= "\n";
        }

        if (!empty($recommendations['keywords_to_include'])) {
            $message .= "## Keywords to Include\n";
            foreach ($recommendations['keywords_to_include'] as $keyword) {
                $message .= "- {$keyword}\n";
            }
            $message .= "\n";
        }

        if (!empty($recommendations['overall_match_assessment'])) {
            $message .= "## Overall Match Assessment\n";
            $message .= $recommendations['overall_match_assessment'] . "\n\n";
        }

        $message .= "# Variation Information\n";
        $message .= "Template used: {$selectedResult['template']}\n";
        $message .= "Overall score: {$selectedResult['score']}/100\n\n";

        if (!empty($selectedResult['validation']['strengths'])) {
            $message .= "## Strengths\n";
            foreach ($selectedResult['validation']['strengths'] as $strength) {
                $message .= "- {$strength}\n";
            }
            $message .= "\n";
        }

        if (!empty($selectedResult['validation']['weaknesses'])) {
            $message .= "## Areas to Improve\n";
            foreach ($selectedResult['validation']['weaknesses'] as $weakness) {
                $message .= "- {$weakness}\n";
            }
            $message .= "\n";
        }

        $message .= "Please use this analysis to improve the content while maintaining its strengths. Focus particularly on incorporating the key skills and keywords identified through semantic analysis.";

        return $message;
    }

    /**
     * Get top matches from semantic matching
     *
     * @param array $matches
     * @return array
     */
    private function getTopMatches(array $matches): array
    {
        $topMatches = [];

        foreach ($matches as $type => $typeMatches) {
            // Sort by similarity (highest first)
            usort($typeMatches, function ($a, $b) {
                return $b['similarity'] <=> $a['similarity'];
            });

            // Take top 5 matches
            $topMatches[$type] = array_slice($typeMatches, 0, 5);
        }

        return $topMatches;
    }

    /**
     * Regenerate a document with feedback
     *
     * @param Resume|CoverLetter $document
     * @param array $feedback
     * @return Resume|CoverLetter
     */
    public function regenerateWithFeedback($document, array $feedback)
    {
        $jobPost = $document->jobPost;
        $user = $document->user;
        $type = $document instanceof Resume ? 'resume' : 'cover_letter';

        try {
            // Get the previous session
            $previousSession = $document->threadSession;

            if (!$previousSession) {
                throw new Exception("No previous session found for this document");
            }

            // Create a new session of the same type
            if ($type === 'resume') {
                $session = $this->threadManager->startResumeSession($user, $jobPost);
            } else {
                $session = $this->threadManager->startCoverLetterSession($user, $jobPost);
            }

            // Add the feedback to the thread
            $feedbackMessage = "Please improve the previous {$type} based on this feedback:\n\n";
            $feedbackMessage .= $feedback['feedback'] ?? 'Please improve this document.';
            $feedbackMessage .= "\n\nPrevious version:\n\n";
            $feedbackMessage .= $document->content;

            $this->threadManager->addMessage($session->thread_id, $feedbackMessage);

        // Generate new content
            $content = $this->threadManager->generateContent($session);

            // Count words
            $wordCount = str_word_count(strip_tags($content));

            // Update the document with new content
        $document->update([
                'thread_session_id' => $session->id,
                'content' => $content,
                'word_count' => $wordCount,
            'file_path' => null, // Clear file path so it will be regenerated
        ]);

        // Generate PDF
        if ($type === 'resume') {
            $this->pdf->generateResumePDF($document);
        } else {
            $this->pdf->generateCoverLetterPDF($document);
        }

            // Validate the new document
            $validation = $this->threadManager->validateDocument($content, $type, $jobPost);

            // Update document with validation results
            $document->update([
                'rule_compliance' => $validation,
            ]);

        return $document;

        } catch (Exception $e) {
            Log::error("Document regeneration failed", [
                'document_id' => $document->id,
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
````

## File: app/Providers/AppServiceProvider.php
````php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AssistantsService;
use App\Services\ThreadManagementService;
use App\Services\PromptEngineeringService;
use App\Services\EmbeddingsService;
use App\Services\GenerationService;
use App\Services\PDFService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AssistantsService::class, function ($app) {
            return new AssistantsService();
        });

        $this->app->singleton(ThreadManagementService::class, function ($app) {
            return new ThreadManagementService(
                $app->make(AssistantsService::class)
            );
        });

        $this->app->singleton(PromptEngineeringService::class, function ($app) {
            return new PromptEngineeringService();
        });

        $this->app->singleton(EmbeddingsService::class, function ($app) {
            return new EmbeddingsService();
        });

        $this->app->singleton(GenerationService::class, function ($app) {
            return new GenerationService(
                $app->make(ThreadManagementService::class),
                $app->make(PromptEngineeringService::class),
                $app->make(EmbeddingsService::class),
                $app->make(PDFService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
````

## File: composer.json
````json
{
    "$schema": "https://getcomposer.org/schema.json",
    "name": "laravel/laravel",
    "type": "project",
    "description": "The skeleton application for the Laravel framework.",
    "keywords": [
        "laravel",
        "framework"
    ],
    "license": "MIT",
    "require": {
        "php": "^8.2",
        "barryvdh/laravel-dompdf": "^3.1",
        "laravel/framework": "^12.0",
        "laravel/horizon": "^5.31",
        "laravel/nova": "^5.0",
        "laravel/sanctum": "^4.0",
        "laravel/telescope": "^5.7",
        "laravel/tinker": "^2.10.1",
        "openai-php/client": "^0.10.3",
        "openai-php/laravel": "^0.11.0",
        "spatie/laravel-backup": "^9.2",
        "ext-dom": "*"
    },
    "require-dev": {
        "brianium/paratest": "^7.8",
        "fakerphp/faker": "^1.23",
        "laravel/nova-devtool": "^1.8",
        "laravel/pail": "^1.2.2",
        "laravel/pint": "^1.13",
        "laravel/sail": "^1.41",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "phpunit/phpunit": "^11.5.3"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://nova.laravel.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
            "@php artisan nova:publish --ansi"
        ],
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php artisan migrate --graceful --ansi"
        ],
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite"
        ]
    },
    "extra": {
        "laravel": {
            "dont-discover": []
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "php-http/discovery": true
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}
````
