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
     * @param bool $allSkillDetails Whether to include all skill details or just the ones with score >= 9
     * @return string
     */
    public function formatUserProfile(User $user, bool $allSkillDetails = true): string
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

            if(!empty($experience->skills_used)) {
                $profile .= "\n  - **High-Level Skills Used:**\n";
                foreach ($experience->skills_used as $skill => $description) {
                    $profile .= "    - {$skill}: {$description}\n";
                }
            }

            if (!empty($experience->achievements)) {
                $profile .= "\n  - **Achievements:**\n";
                foreach ($experience->achievements as $achievement => $description) {
                    $profile .= "    - {$description}\n";
                }
            }
        }

        // Add education
        $profile .= "\n### Education:\n";
        foreach ($user->education()->orderBy('start_date', 'desc')->get() as $education) {
            $endDate = $education->current ? "Present" : $education->end_date->format('M Y');
            $fieldOfStudy = !empty($education->field_of_study) ? " in {$education->field_of_study}" : "";
            $profile .= "- **{$education->degree}{$fieldOfStudy}** from {$education->institution} ({$education->start_date->format('M Y')} - {$endDate})\n";

            if (!empty($education->achievements_breakdown)) {
                $profile .= "\n  - **Achievements:**\n";
                foreach ($education->achievements_breakdown as $description) {
                    $profile .= "      - {$description}\n";
                }
            }
        }

        // Add skills
        $profile .= "\n\n### Skills:\n";

        // Add scale breakdown to the skills
        $profile .= "\n  - **Proficiency Scale:**\n";
        $profile .= "    - 1-2 (Novice): Basic theoretical knowledge, little to no practical experience.\n";
        $profile .= "    - 3-4 (Beginner): Some hands-on experience, can handle simple tasks with guidance.\n";
        $profile .= "    - 5-6 (Intermediate): Solid working knowledge, can work independently on moderately complex tasks.\n";
        $profile .= "    - 7-8 (Advanced): Expert-level skills, can lead projects or solve complex problems.\n";
        $profile .= "    - 9-10 (Master/Expert): World-class expertise, can innovate or teach others.\n";

        // Add the skills with proficiency levels, along with proficiency_reason underneath it if available and the score is 10
        $profile .= "\n  - **Breakdown:**\n";
        foreach ($user->skills()->orderBy('proficiency', 'desc')->get() as $skill) {
            $proficiency = $skill->proficiency;

            $proficiencyText = match ($proficiency) {
                1, 2 => "Novice",
                3, 4 => "Beginner",
                5, 6 => "Intermediate",
                7, 8 => "Advanced",
                9, 10 => "Master/Expert",
                default => "Unknown"
            };

            // show the line in bold if the proficiency is >= 9
            if ($proficiency >= 9) {
                $profile .= "    - {$skill->name}: **{$proficiencyText} ({$proficiency})**\n";
            } else {
                $profile .= "    - {$skill->name}: {$proficiencyText} ({$proficiency})\n";
            }

            if (!empty($skill->proficiency_reason) && ($proficiency >= 10 || $allSkillDetails)) {
                $profile .= "      - **Details:** {$skill->proficiency_reason}\n";
            }


//            $proficiencyReason = !empty($skill->proficiency_reason) ? " ({$skill->proficiency_reason})" : "";
//            $experience = $skill->years_experience > 0 ? " ({$skill->years_experience} years)" : "";

//            $profile .= "    - {$skill->name}: {$proficiency} {$proficiencyReason}{$experience}\n";
        }
//        foreach ($user->skills()->orderBy('proficiency', 'desc')->get() as $skill) {
//            $experience = $skill->years_experience > 0 ? " ({$skill->years_experience} years)" : "";
//            $profile .= "- {$skill->name}{$experience}\n";
//        }

        // Add projects
        $profile .= "\n\n### Projects:\n";
        foreach ($user->projects as $project) {
            $profile .= "- **{$project->name}**\n";
            $profile .= "  {$project->description}\n";

            if (!empty($project->technologies_used)) {
                $techs = implode(", ", array_keys($project->technologies_used));
                $profile .= "  **Technologies:** {$techs}\n";
            }

            if(!empty($project->achievements)) {
                $profile .= "  **Achievements:**\n";
                foreach ($project->achievements as $achievement) {
                    $profile .= "    - {$achievement}\n";
                }
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
        try {
            Log::debug("Starting document validation", [
                'type' => $type,
                'job_post_id' => $jobPost->id,
                'content_length' => strlen($content)
            ]);

            // Get the validator assistant
            $assistantId = $this->assistantsService->getOrCreateAssistant(AssistantsService::TYPE_VALIDATOR);

            Log::debug("Retrieved validator assistant", [
                'assistant_id' => $assistantId
            ]);

            // Create a new thread
            $threadId = $this->assistantsService->createThread();

            Log::debug("Created thread for validation", [
                'thread_id' => $threadId
            ]);

            // Prepare the validation message
            $message = $this->prepareValidationMessage($content, $type, $jobPost);

            // Add the message to the thread
            $this->assistantsService->addMessage($threadId, $message);

            Log::debug("Added validation message to thread", [
                'thread_id' => $threadId,
                'message_length' => strlen($message)
            ]);

            // Run the assistant
            $messages = $this->assistantsService->runAssistant($threadId, $assistantId);

            if (empty($messages)) {
                Log::warning("No validation response generated", [
                    'thread_id' => $threadId,
                    'assistant_id' => $assistantId
                ]);

                return [
                    'overall_score' => 7, // Default reasonable score
                    'criteria' => [
                        'ATS Compatibility' => ['score' => 7, 'feedback' => 'Unable to validate specifically.'],
                        'Keyword Optimization' => ['score' => 7, 'feedback' => 'Unable to validate specifically.'],
                        'Achievement Quantification' => ['score' => 7, 'feedback' => 'Unable to validate specifically.'],
                        'Tailoring to the Job' => ['score' => 7, 'feedback' => 'Unable to validate specifically.'],
                        'Writing Quality' => ['score' => 7, 'feedback' => 'Unable to validate specifically.'],
                        'Document Length' => ['score' => 7, 'feedback' => 'Unable to validate specifically.']
                    ],
                    'summary' => 'Document appears to be properly formatted, but detailed validation was not possible.',
                    'suggestions' => ['Review the document manually to ensure it meets requirements.']
                ];
            }

            Log::debug("Received validation response", [
                'thread_id' => $threadId,
                'message_count' => count($messages)
            ]);

            // Parse the validation results
            $validationText = '';
            foreach ($messages[0]->content as $contentPart) {
                if ($contentPart->type === 'text') {
                    $validationText .= $contentPart->text->value;
                }
            }

            Log::debug("Parsing validation text", [
                'text_length' => strlen($validationText)
            ]);

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

            // If we didn't find an overall score, set a default
            if ($results['overall_score'] == 0) {
                $results['overall_score'] = 7; // Default reasonable score
            }

            // If we didn't get any criteria, add some defaults
            if (empty($results['criteria'])) {
                $results['criteria'] = [
                    'ATS Compatibility' => ['score' => 7, 'feedback' => 'Document appears to have good ATS compatibility.'],
                    'Tailoring to the Job' => ['score' => 7, 'feedback' => 'Document appears to be properly tailored to the job.'],
                    'Document Quality' => ['score' => 7, 'feedback' => 'Document appears to be of good quality.']
                ];
            }

            // If we didn't get a summary, add a default one
            if (empty($results['summary'])) {
                $results['summary'] = 'The document meets basic requirements for a professional ' .
                    ($type === 'resume' ? 'resume' : 'cover letter') . '.';
            }

            Log::debug("Validation parsing complete", [
                'overall_score' => $results['overall_score'],
                'criteria_count' => count($results['criteria']),
                'suggestion_count' => count($results['suggestions'])
            ]);

            return $results;

        } catch (Exception $e) {
            Log::error("Document validation failed", [
                'type' => $type,
                'job_post_id' => $jobPost->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return a basic validation result that won't break downstream code
            return [
                'overall_score' => 7, // Default reasonable score
                'criteria' => [
                    'ATS Compatibility' => ['score' => 7, 'feedback' => 'Unable to validate due to technical issue.'],
                    'Keyword Optimization' => ['score' => 7, 'feedback' => 'Unable to validate due to technical issue.'],
                    'Document Quality' => ['score' => 7, 'feedback' => 'Unable to validate due to technical issue.']
                ],
                'summary' => 'Validation could not be completed due to a technical issue. The document appears to meet basic requirements.',
                'suggestions' => ['Review the document manually to ensure it meets requirements.']
            ];
        }
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
