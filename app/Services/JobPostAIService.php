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
