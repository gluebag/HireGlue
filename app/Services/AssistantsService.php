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
        $aiHttpParameters = [
            'name' => $config['name'],
            'instructions' => $config['instructions'],
            'tools' => $config['tools'],
            'model' => $config['model'] ?? 'gpt-4o',
        ];
        Log::debug("Creating OpenAI assistant", [
            'type' => $type,
            'ai_http_parameters' => $aiHttpParameters,
        ]);

        $response = OpenAI::assistants()->create($aiHttpParameters);

        Log::debug("OpenAI assistant created successfully", [
            'type' => $type,
            'ai_http_parameters' => $aiHttpParameters,
            'assistant_id' => $response->id,
            'created_at' => $response->createdAt,
            'full_http_response' => $response->toArray()
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
                    ['type' => 'file_search'],
                    ['type' => 'code_interpreter'],
                ],
                'model' => 'gpt-4o',
            ],
            self::TYPE_COVER_LETTER => [
                'name' => 'Cover Letter Generator',
                'instructions' => $this->getCoverLetterInstructions(),
                'tools' => [
                    ['type' => 'file_search'],
                    ['type' => 'code_interpreter'],
                ],
                'model' => 'gpt-4o',
            ],
            self::TYPE_VALIDATOR => [
                'name' => 'Document Validator',
                'instructions' => $this->getValidatorInstructions(),
                'tools' => [
                    ['type' => 'file_search'],
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
        Log::debug("Creating new OpenAI thread");

        $response = OpenAI::threads()->create([]);

        Log::debug("OpenAI thread created successfully", [
            'thread_id' => $response->id,
            'created_at' => $response->createdAt
        ]);

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

        Log::debug("Adding message to OpenAI thread", [
            'thread_id' => $threadId,
            'role' => 'user',
            'content' => $content,
            'content_length' => strlen($content),
            'has_files' => !empty($files),
            'file_count' => count($files),
            'file_ids' => $files,
        ]);

        $response = OpenAI::threads()->messages()->create($threadId, $params);

        Log::debug("Message added to OpenAI thread successfully", [
            'thread_id' => $threadId,
            'message_id' => $response->id,
            'created_at' => $response->createdAt,

        ]);

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
        Log::debug("Running OpenAI assistant on thread", [
            'thread_id' => $threadId,
            'assistant_id' => $assistantId,
            'has_instructions' => isset($instructions['instructions']),
        ]);

        $run = OpenAI::threads()->runs()->create($threadId, [
            'assistant_id' => $assistantId,
            'instructions' => $instructions['instructions'] ?? null,
        ]);

        Log::debug("OpenAI run created", [
            'thread_id' => $threadId,
            'run_id' => $run->id,
            'status' => $run->status,
        ]);

        // Poll for completion (in production, you'd use a background job)
        $maxAttempts = 60; // 10 minutes max (10s intervals)
        $attempts = 0;

        do {
            sleep(10); // Wait 10 seconds between checks

            Log::debug("Checking OpenAI run status", [
                'thread_id' => $threadId,
                'run_id' => $run->id,
                'attempt' => $attempts + 1,
                'max_attempts' => $maxAttempts,
            ]);

            $run = OpenAI::threads()->runs()->retrieve($threadId, $run->id);

            Log::debug("OpenAI run status update", [
                'thread_id' => $threadId,
                'run_id' => $run->id,
                'status' => $run->status,
                'attempt' => $attempts + 1,
            ]);

            $attempts++;

            // Handle required actions if needed (function calling, etc.)
            if ($run->status === 'requires_action') {
                Log::debug("OpenAI run requires action", [
                    'thread_id' => $threadId,
                    'run_id' => $run->id,
                    'action_type' => $run->requiredAction->type ?? 'unknown',
                ]);

                // Process required actions (would need implementation based on your functions)
                // This is a simplified example
                $this->handleRequiredAction($threadId, $run);
            }

        } while ($run->status !== 'completed' && $run->status !== 'failed' && $attempts < $maxAttempts);

        if ($run->status !== 'completed') {
            Log::error("OpenAI run failed or timed out", [
                'thread_id' => $threadId,
                'run_id' => $run->id,
                'status' => $run->status,
                'attempts' => $attempts,
            ]);

            throw new Exception("Assistant run failed or timed out: {$run->status}");
        }

        Log::debug("OpenAI run completed successfully", [
            'thread_id' => $threadId,
            'run_id' => $run->id,
            'attempts' => $attempts,
            'total_time' => ($attempts * 10) . ' seconds',
        ]);

        // Get the messages (newest first)
        Log::debug("Retrieving messages from completed OpenAI run", [
            'thread_id' => $threadId,
            'run_id' => $run->id,
        ]);

        $messages = OpenAI::threads()->messages()->list($threadId, ['limit' => 10]);

        // Filter to only get assistant messages from this run
        $responseMessages = [];
        foreach ($messages->data as $message) {
            if ($message->role === 'assistant' && $message->runId === $run->id) {
                $responseMessages[] = $message;
            }
        }

        Log::debug("Retrieved assistant messages from OpenAI run", [
            'thread_id' => $threadId,
            'run_id' => $run->id,
            'message_count' => count($responseMessages),
        ]);

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
            Log::debug("Skipping required action - not a tool output submission", [
                'thread_id' => $threadId,
                'run_id' => $run->id,
                'action_type' => $run->requiredAction->type ?? 'unknown',
            ]);
            return;
        }

        $toolOutputs = [];

        Log::debug("Processing required tool outputs for OpenAI run", [
            'thread_id' => $threadId,
            'run_id' => $run->id,
            'tool_calls_count' => count($run->requiredAction->submitToolOutputs->toolCalls ?? []),
        ]);

        foreach ($run->requiredAction->submitToolOutputs->toolCalls as $toolCall) {
            // Example implementation - would need to be adapted to your specific functions
            $function = $toolCall->function->name;
            $arguments = json_decode($toolCall->function->arguments, true);

            Log::debug("Executing function for OpenAI tool call", [
                'thread_id' => $threadId,
                'run_id' => $run->id,
                'tool_call_id' => $toolCall->id,
                'function' => $function,
                'arguments' => $arguments,
            ]);

            // Call your function handling logic here
            $result = $this->callFunction($function, $arguments);

            Log::debug("Function execution completed for OpenAI tool call", [
                'thread_id' => $threadId,
                'run_id' => $run->id,
                'tool_call_id' => $toolCall->id,
                'function' => $function,
                'result' => $result,
            ]);

            $toolOutputs[] = [
                'tool_call_id' => $toolCall->id,
                'output' => json_encode($result),
            ];
        }

        Log::debug("Submitting tool outputs for OpenAI run", [
            'thread_id' => $threadId,
            'run_id' => $run->id,
            'output_count' => count($toolOutputs),
        ]);

        // Submit the tool outputs
        OpenAI::threads()->runs()->submitToolOutputs($threadId, $run->id, [
            'tool_outputs' => $toolOutputs,
        ]);

        Log::debug("Tool outputs submitted for OpenAI run", [
            'thread_id' => $threadId,
            'run_id' => $run->id,
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
