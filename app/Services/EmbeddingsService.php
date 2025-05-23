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
                        'embedding' => $embedding,
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
                        'embedding' => $embedding,
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
                    'embedding' => $embedding,
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

        try {
            // Make sure embeddings exist
            $this->generateUserSkillEmbeddings($user);
            $this->generateJobRequirementEmbeddings($jobPost);

            // Get job requirement embeddings
            $jobRequirementEmbeddings = JobRequirementEmbedding::where('job_post_id', $jobPost->id)->get();

            // Get user skill embeddings
            $userSkillEmbeddings = SkillEmbedding::where('user_id', $user->id)->get();

            foreach ($jobRequirementEmbeddings as $requirementEmbedding) {
                $requirementVector = $requirementEmbedding->embedding;
                $requirementType = $requirementEmbedding->requirement_type;

                $matches[$requirementType] = [];

                foreach ($userSkillEmbeddings as $skillEmbedding) {
                    $skillVector = $skillEmbedding->embedding;

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
        } catch (Exception $e) {
            Log::error("Error in findSkillMatches", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Return empty matches if an error occurs
            return [];
        }
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
        try {
            // Find skill matches
            Log::debug("Starting to find skill matches for recommendations", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id
            ]);
            
            $matches = $this->findSkillMatches($user, $jobPost);
            
            if (empty($matches)) {
                Log::warning("No skill matches found for recommendations", [
                    'user_id' => $user->id,
                    'job_post_id' => $jobPost->id
                ]);
                
                // Return basic recommendations if no matches
                return [
                    'key_skills_to_emphasize' => [],
                    'skills_to_reframe' => [],
                    'gap_mitigation_strategies' => [],
                    'achievements_to_highlight' => [],
                    'keywords_to_include' => [],
                    'overall_match_assessment' => 'Unable to generate detailed recommendations due to insufficient skill matches.',
                ];
            }
            
            Log::debug("Skill matches found for recommendations", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id,
                'match_count' => count($matches)
            ]);

            // Prepare data for OpenAI
            $matchesJson = json_encode($matches, JSON_PRETTY_PRINT);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Failed to encode matches as JSON", [
                    'user_id' => $user->id,
                    'job_post_id' => $jobPost->id,
                    'error' => json_last_error_msg()
                ]);
                throw new Exception("Failed to encode matches as JSON: " . json_last_error_msg());
            }
            
            Log::debug("Calling OpenAI for skill recommendations", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id
            ]);

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
            
            Log::debug("Received response from OpenAI for recommendations", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id
            ]);

            $content = $response->choices[0]->message->content;
            
            Log::debug("Parsing recommendation response content", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id,
                'content_length' => strlen($content)
            ]);

            // Try direct decode first
            $recommendations = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::debug("Direct JSON decode failed, trying to extract JSON from markdown", [
                    'user_id' => $user->id,
                    'job_post_id' => $jobPost->id,
                    'error' => json_last_error_msg()
                ]);
                
                // If the response isn't valid JSON, try to extract it from markdown code blocks
                preg_match('/```(?:json)?\s*(.*?)\s*```/s', $content, $matches);
                
                if (isset($matches[1])) {
                    $jsonContent = trim($matches[1]);
                    Log::debug("Found JSON content in markdown code block", [
                        'user_id' => $user->id,
                        'job_post_id' => $jobPost->id,
                        'json_length' => strlen($jsonContent)
                    ]);
                    
                    $recommendations = json_decode($jsonContent, true);
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error("Failed to parse extracted JSON content", [
                            'user_id' => $user->id,
                            'job_post_id' => $jobPost->id,
                            'error' => json_last_error_msg(),
                            'json_content' => $jsonContent
                        ]);
                        throw new Exception("Failed to parse recommendations JSON: " . json_last_error_msg());
                    }
                } else {
                    Log::error("Could not extract JSON content from response", [
                        'user_id' => $user->id,
                        'job_post_id' => $jobPost->id,
                        'content' => $content
                    ]);
                    throw new Exception("Failed to extract recommendations JSON from response");
                }
            }
            
            Log::debug("Successfully parsed recommendations", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id,
                'recommendation_count' => count($recommendations)
            ]);

            // Ensure all expected fields exist
            $expectedFields = [
                'key_skills_to_emphasize', 
                'skills_to_reframe', 
                'gap_mitigation_strategies',
                'achievements_to_highlight', 
                'keywords_to_include', 
                'overall_match_assessment'
            ];
            
            foreach ($expectedFields as $field) {
                if (!isset($recommendations[$field])) {
                    $recommendations[$field] = $field === 'overall_match_assessment' 
                        ? 'No assessment provided.' 
                        : [];
                }
            }

            return $recommendations;

        } catch (Exception $e) {
            Log::error("Failed to generate recommendations", [
                'user_id' => $user->id,
                'job_post_id' => $jobPost->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return basic recommendations if generation fails
            return [
                'key_skills_to_emphasize' => [],
                'skills_to_reframe' => [],
                'gap_mitigation_strategies' => [],
                'achievements_to_highlight' => [],
                'keywords_to_include' => [],
                'overall_match_assessment' => 'Unable to generate detailed recommendations due to a technical issue.',
            ];
        }
    }
}
