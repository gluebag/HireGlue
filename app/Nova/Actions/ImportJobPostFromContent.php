<?php

namespace App\Nova\Actions;

use App\Services\HtmlToMarkdownService;
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
    protected function importFromHtml($htmlContent, $url = null)
    {
        try {
            // Convert HTML to markdown using the html-to-markdown
            $markdown = app(HtmlToMarkdownService::class)->convert($htmlContent, true, $url);
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
