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
    public function generateResumeLegacy(JobPost $jobPost, User $user, ?string $promptName = null, array $extraContext = [])
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
    public function generateCoverLetterLegacy(JobPost $jobPost, User $user, ?string $promptName = null, array $extraContext = [])
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
    public function checkRuleComplianceLegacy(string $content, string $type, $rules)
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
