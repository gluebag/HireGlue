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
