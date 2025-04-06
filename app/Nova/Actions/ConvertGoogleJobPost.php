<?php

namespace App\Nova\Actions;

use App\Services\HtmlToMarkdownService;
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

            // Step 2: Convert HTML to Markdown using html-to-markdown
            Log::debug('Converting HTML to Markdown', [
                'url' => $jobPostUrl,
                'html_length' => strlen($htmlContent),
                'html_content' => $htmlContent,
            ]);
            $markdown = app(HtmlToMarkdownService::class)->convert($htmlContent, true, $jobPostUrl);
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
