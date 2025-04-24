<?php

namespace App\Services;

use App\Models\OpenAIPrompt;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class JobPostAIService
{
    /**
     * The Claude API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The Claude API URL
     *
     * @var string
     */
    protected $apiUrl = 'https://api.anthropic.com/v1/messages';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key');

        if (empty($this->apiKey)) {
            throw new Exception('Claude API key is not configured');
        }
    }

    /**
     * Analyze a job post using Claude
     *
     * @param string $content The job post content (HTML or markdown)
     * @param string|null $url The original job URL
     * @return array The structured job data
     * @throws Exception If there is an error
     */
    public function analyzeJobPost(string $content, ?string $url = null, ?string $jobTitle = null, ?string $companyName = null): array
    {
        // Find the Job Post Analysis prompt
        $prompt = OpenAIPrompt::where('name', 'job_post_analysis_claude')
            ->where('type', 'analysis')
            ->where('active', true)
            ->first();

        if (!$prompt) {
            throw new Exception('job_post_analysis_claude prompt not found. Please run the OpenAIPromptSeeder or check DB.');
        }

        // Default to content as markdown
        $markdownContent = $content;

        // If URL is provided, save it for reference
        $jobPostUrl = $url;

        // Get user skills breakdown for comparison (optional)
        $threadService = app(ThreadManagementService::class);
        $skillsBreakdown = '';
        if (Auth::check()) {
            $skillsBreakdown = $threadService->formatSkillsSimple(Auth::user());
        }

        // Prepare variables for prompt
        $promptVariables = [
            'job_content' => $markdownContent,
            'company' => $companyName,
            'target_role' => $jobTitle,
            'my_skills' => $skillsBreakdown,
        ];

        // Generate completion using Claude API and parse result
        $result = $this->generateClaudeCompletion($prompt, $promptVariables);

        if (!$result || json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid JSON response from Claude', [
                'response' => $result,
                'error' => json_last_error_msg()
            ]);

            throw new Exception('Failed to parse Claude response: ' . json_last_error_msg());
        }

        // Add the job post URL if it was provided
        if ($jobPostUrl) {
            $result['job_post_url'] = $jobPostUrl;
        }

        return $result;
    }

    /**
     * Generate a completion using Claude API
     *
     * @param OpenAIPrompt $prompt The prompt to use
     * @param array $parameters The parameters to replace in the prompt
     * @return array The parsed JSON response
     * @throws Exception If there is an error
     */
    protected function generateClaudeCompletion(OpenAIPrompt $prompt, array $parameters): array
    {
        set_time_limit(0); // Disable time limit for long requests

        // Replace placeholders in the prompt template
        $promptText = $this->replacePlaceholders($prompt->prompt_template, $parameters);

        if(empty($systemMessage = $prompt->system_message)) {
            throw new Exception('System message is empty. Please check the prompt configuration.');
        }

        // Prepare the request payload for Claude API
        $postData = [
            'model' => 'claude-3-7-sonnet-20250219', // Use Claude 3 Sonnet
            'max_tokens' => 128000,
            'temperature' => (float) $prompt->temperature,
            'system' => $prompt->system_message,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => []
                ]
            ],
            'thinking' => [
                'type' => 'enabled',
                'budget_tokens' => 64000
            ],
        ];

        // Add examples message if available as ephemeral cache
        if (!empty($prompt->examples_message)) {
            $postData['messages'][0]['content'][] = [
                'type' => 'text',
                'text' => $prompt->examples_message,
                'cache_control' => [
                    'type' => 'ephemeral',
                ]
            ];
        }

        // Add the main prompt
        $postData['messages'][0]['content'][] = [
            'type' => 'text',
            'text' => $promptText,
        ];

        // Log the request (excluding sensitive data)
        Log::info('Claude Request', [
            'model' => $postData['model'],
            'temperature' => $postData['temperature'],
        ]);

        // Make the API request to Claude
        $startedAt = microtime(true);
        $response = Http::withHeaders(config('services.anthropic.headers'))
            ->timeout(300)
            ->post($this->apiUrl, $postData);

        // Check if the request was successful
        if (!$response->successful()) {
            $error = $response->json();
            Log::error('Claude API Error', [
                'status' => $response->status(),
                'error' => $error,
            ]);

            throw new Exception('Claude API Error: ' . ($error['error']['message'] ?? 'Unknown error'));
        }

        // Extract and process the response
        $claudeResp = $response->json();
        $endedAt = microtime(true);
        $elapsedTimeSecs = round($endedAt - $startedAt);

        // Store prompt history if needed
        $this->storePromptHistory($prompt, $postData, $claudeResp, $elapsedTimeSecs);

        // Log token usage
        $tokenStats = $claudeResp['usage'];
        Log::info('Claude Response', [
            'elapsed_time' => $elapsedTimeSecs,
            'input_tokens' => $tokenStats['input_tokens'],
            'output_tokens' => $tokenStats['output_tokens']
        ]);

        // Extract the text response
        $analyzedJobJson = trim(collect($claudeResp['content'])->where('type', 'text')->join("\n\n"));
        $thinkingText = trim(collect($claudeResp['content'])->where('type', 'thinking')->join("\n\n"));

        // Process JSON response if it's in a code block
        if(Str::startsWith($analyzedJobJson, '```json')) {
            $analyzedJobJson = Str::after($analyzedJobJson, '```json');
            $analyzedJobJson = Str::before($analyzedJobJson, '```');
        }

        // Parse JSON
        $jobData = json_decode($analyzedJobJson, true);

        return $jobData;
    }

    /**
     * Store prompt history for tracking
     *
     * @param OpenAIPrompt $prompt
     * @param array $postData
     * @param array $claudeResp
     * @param int $elapsedTimeSecs
     * @return void
     */
    protected function storePromptHistory(OpenAIPrompt $prompt, array $postData, array $claudeResp, int $elapsedTimeSecs): void
    {
        $tokenStats = $claudeResp['usage'];

        \App\Models\PromptHistory::create([
            'prompt_id' => $prompt->id,
            'user_id' => Auth::check() ? Auth::id() : null,
            'type' => $prompt->type,
            'name' => $prompt->name,
            'status' => 'completed',
            'src_class' => class_basename($this),
            'src_function' => 'analyzeJobPost',
            'src_stack' => Str::after(debug_backtrace()[1]['function'] ?? '', 'App\\'),
            'tokens_used' => $tokenStats['input_tokens'] + $tokenStats['output_tokens'],
            'elapsed_time' => $elapsedTimeSecs,
            'api_response' => $claudeResp,
            'model_config' => Arr::except($postData, ['messages', 'system']),
            'system_message' => $prompt->system_message,
            'user_messages' => $postData['messages'],
        ]);
    }

    /**
     * Replace placeholders in the prompt template
     *
     * @param string $template
     * @param array $parameters
     * @return string
     */
    public function replacePlaceholders(string $template, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $template = str_replace($placeholder, $value, $template);
        }

        return $template;
    }
}
