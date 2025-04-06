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

                'Company Research' => 'Create a cover letter that demonstrates deep knowledge of the company's mission, recent achievements, or culture, connecting the candidate's values and experience to the company specifically.',

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
