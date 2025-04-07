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
        ThreadManagementService  $threadManager,
        PromptEngineeringService $promptEngineering,
        EmbeddingsService        $embeddings,
        PDFService               $pdf
    )
    {
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
            Log::debug("Starting resume generation process", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'job_title' => $jobPost->job_title,
                'company' => $jobPost->company_name,
            ]);

            // Step 1: Generate embeddings and find matches
            Log::debug("Generating embeddings and finding skill matches", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
            ]);
            $matches = $this->embeddings->findSkillMatches($user, $jobPost);
            $recommendations = $this->embeddings->generateRecommendations($user, $jobPost);
            Log::debug("Embeddings and skill matches generated", [
                'job_post_id' => $jobPost->id,
                'matches_count' => count($matches),
                'has_recommendations' => !empty($recommendations),
            ]);

            // Step 2: Use multi-step workflow to generate content variations
            Log::debug("Starting multi-step workflow for content variations", [
                'job_post_id' => $jobPost->id,
                'type' => 'resume',
            ]);
            $variations = $this->promptEngineering->generateWithMultiStepWorkflow($jobPost, $user, 'resume');
            Log::debug("Generated content variations", [
                'job_post_id' => $jobPost->id,
                'variations_count' => count($variations),
            ]);

            // Step 3: Select the best variation
            Log::debug("Selecting best content variation", [
                'job_post_id' => $jobPost->id,
            ]);
            $selectedResult = $this->promptEngineering->selectBestVariation($variations);
            $content = $selectedResult['content'];
            Log::debug("Selected best variation", [
                'job_post_id' => $jobPost->id,
                'template' => $selectedResult['template'],
                'score' => $selectedResult['score'],
            ]);

            // Step 4: Start a resume generation session with the assistant
            Log::debug("Starting resume generation session with assistant", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
            ]);
            $session = $this->threadManager->startResumeSession($user, $jobPost);
            Log::debug("Resume session started", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
                'thread_id' => $session->thread_id,
            ]);

            // Add context from our semantic analysis and variations
            Log::debug("Preparing context message for assistant", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
            ]);
            $contextMessage = $this->prepareContextMessage($recommendations, $selectedResult, 'resume');
            Log::debug("Adding context message to thread", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
                'message_length' => strlen($contextMessage),
            ]);
            $this->threadManager->addMessage($session->thread_id, $contextMessage);

            // Add content to refine
            Log::debug("Adding content to refine to thread", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
                'content_length' => strlen($content),
            ]);
            $this->threadManager->addMessage($session->thread_id, "Here's the content to refine:\n\n" . $content);

            // Generate final content with the assistant
            Log::debug("Generating final content with assistant", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
            ]);
            $finalContent = $this->threadManager->generateContent($session);
            Log::debug("Final content generated", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
                'content_length' => strlen($finalContent),
            ]);

            // Count words
            $wordCount = str_word_count(strip_tags($finalContent));
            Log::debug("Counted words in final content", [
                'job_post_id' => $jobPost->id,
                'word_count' => $wordCount,
            ]);

            // Create resume record
            Log::debug("Creating resume record", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
            ]);
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
            Log::debug("Resume record created", [
                'job_post_id' => $jobPost->id,
                'resume_id' => $resume->id,
            ]);

            // Generate PDF
            Log::debug("Generating PDF for resume", [
                'job_post_id' => $jobPost->id,
                'resume_id' => $resume->id,
            ]);
            $this->pdf->generateResumePDF($resume);
            Log::debug("PDF generated for resume", [
                'job_post_id' => $jobPost->id,
                'resume_id' => $resume->id,
            ]);

            // Validate the resume
            Log::debug("Validating resume document", [
                'job_post_id' => $jobPost->id,
                'resume_id' => $resume->id,
            ]);
            $validation = $this->threadManager->validateDocument($finalContent, 'resume', $jobPost);
            Log::debug("Resume validated", [
                'job_post_id' => $jobPost->id,
                'resume_id' => $resume->id,
                'validation_result' => !empty($validation),
            ]);

            // Update resume with validation results
            Log::debug("Updating resume with validation results", [
                'job_post_id' => $jobPost->id,
                'resume_id' => $resume->id,
            ]);
            $resume->update([
                'rule_compliance' => $validation,
            ]);
            Log::debug("Resume updated with validation results", [
                'job_post_id' => $jobPost->id,
                'resume_id' => $resume->id,
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
            Log::debug("[START][COVER-LETTER]: Starting generation process...", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'job_title' => $jobPost->job_title,
                'company' => $jobPost->company_name,
            ]);

            // Step 1: Generate embeddings and find matches
            Log::debug("[COVER-LETTER]: Generating embeddings and finding skill matches", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
                'job_title' => $jobPost->job_title,
                'company' => $jobPost->company_name,
            ]);
            $matches = $this->embeddings->findSkillMatches($user, $jobPost);
            $recommendations = $this->embeddings->generateRecommendations($user, $jobPost);
            Log::debug("[COVER-LETTER]: Embeddings and skill matches generated", [
                'job_post_id' => $jobPost->id,
                'matches_count' => count($matches),
                'has_recommendations' => !empty($recommendations),
            ]);

            // Step 2: Use multi-step workflow to generate content variations
            Log::debug("[COVER-LETTER]: Starting multi-step workflow for content variations", [
                'job_post_id' => $jobPost->id,
                'type' => 'cover_letter',
            ]);
            $variations = $this->promptEngineering->generateWithMultiStepWorkflow($jobPost, $user, 'cover_letter');
            Log::debug("[COVER-LETTER]: Generated content variations", [
                'job_post_id' => $jobPost->id,
                'variations_count' => count($variations),
            ]);

            // Step 3: Select the best variation
            Log::debug("[COVER-LETTER]: Selecting best content variation", [
                'job_post_id' => $jobPost->id,
            ]);
            $selectedResult = $this->promptEngineering->selectBestVariation($variations);
            $content = $selectedResult['content'];
            Log::debug("[COVER-LETTER]: Selected best variation", [
                'job_post_id' => $jobPost->id,
                'template' => $selectedResult['template'],
                'score' => $selectedResult['score'],
            ]);

            // Step 4: Start a cover letter generation session with the assistant
            Log::debug("[COVER-LETTER]: Starting cover letter generation session with assistant", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
            ]);
            $session = $this->threadManager->startCoverLetterSession($user, $jobPost);
            Log::debug("[COVER-LETTER]: Cover letter session started", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
                'thread_id' => $session->thread_id,
            ]);

            // Add context from our semantic analysis and variations
            Log::debug("[COVER-LETTER]: Preparing context message for assistant", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
            ]);
            $contextMessage = $this->prepareContextMessage($recommendations, $selectedResult, 'cover_letter');
            Log::debug("[COVER-LETTER]: Adding context message to thread", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
                'message_length' => strlen($contextMessage),
            ]);
            $this->threadManager->addMessage($session->thread_id, $contextMessage);

            // Add content to refine
            Log::debug("[COVER-LETTER]: Adding content to refine to thread", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
                'content_length' => strlen($content),
            ]);
            $this->threadManager->addMessage($session->thread_id, "Here's the content to refine:\n\n" . $content);

            // Generate final content with the assistant
            Log::debug("[COVER-LETTER]: Generating final content with assistant", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
            ]);
            $finalContent = $this->threadManager->generateContent($session);
            Log::debug("[COVER-LETTER]: Final content generated", [
                'job_post_id' => $jobPost->id,
                'session_id' => $session->id,
                'content_length' => strlen($finalContent),
            ]);

            // Count words
            $wordCount = str_word_count(strip_tags($finalContent));
            Log::debug("[COVER-LETTER]: Counted words in final content", [
                'job_post_id' => $jobPost->id,
                'word_count' => $wordCount,
            ]);

            // Create cover letter record
            Log::debug("[COVER-LETTER]: Creating cover letter record", [
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
            ]);
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
            Log::debug("[COVER-LETTER]: Cover letter record created", [
                'job_post_id' => $jobPost->id,
                'cover_letter_id' => $coverLetter->id,
            ]);

            // Generate PDF
            Log::debug("[COVER-LETTER]: Generating PDF for cover letter", [
                'job_post_id' => $jobPost->id,
                'cover_letter_id' => $coverLetter->id,
            ]);
            $this->pdf->generateCoverLetterPDF($coverLetter);
            Log::debug("[COVER-LETTER]: PDF generated for cover letter", [
                'job_post_id' => $jobPost->id,
                'cover_letter_id' => $coverLetter->id,
            ]);

            // Validate the cover letter
            Log::debug("[COVER-LETTER]: Validating cover letter document", [
                'job_post_id' => $jobPost->id,
                'cover_letter_id' => $coverLetter->id,
            ]);
            $validation = $this->threadManager->validateDocument($finalContent, 'cover_letter', $jobPost);
            Log::debug("[COVER-LETTER]: Cover letter validated", [
                'job_post_id' => $jobPost->id,
                'cover_letter_id' => $coverLetter->id,
                'validation_result' => !empty($validation),
            ]);

            // Update cover letter with validation results
            Log::debug("[COVER-LETTER]: Updating cover letter with validation results", [
                'job_post_id' => $jobPost->id,
                'cover_letter_id' => $coverLetter->id,
            ]);
            $coverLetter->update([
                'rule_compliance' => $validation,
            ]);
            Log::debug("[COVER-LETTER]: Cover letter updated with validation results", [
                'job_post_id' => $jobPost->id,
                'cover_letter_id' => $coverLetter->id,
            ]);

            return $coverLetter;

        } catch (Exception $e) {
            Log::error("[COVER-LETTER]: Cover letter generation failed", [
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
            Log::debug("Starting document regeneration with feedback", [
                'document_id' => $document->id,
                'type' => $type,
                'job_post_id' => $jobPost->id,
                'user_id' => $user->id,
            ]);

            // Get the previous session
            $previousSession = $document->threadSession;

            if (!$previousSession) {
                Log::error("Regeneration failed - no previous session found", [
                    'document_id' => $document->id,
                    'type' => $type,
                ]);
                throw new Exception("No previous session found for this document");
            }

            Log::debug("Found previous thread session", [
                'document_id' => $document->id,
                'previous_session_id' => $previousSession->id,
                'previous_thread_id' => $previousSession->thread_id,
            ]);

            // Create a new session of the same type
            Log::debug("Creating new session for regeneration", [
                'document_id' => $document->id,
                'type' => $type,
                'job_post_id' => $jobPost->id,
            ]);

            if ($type === 'resume') {
                $session = $this->threadManager->startResumeSession($user, $jobPost);
            } else {
                $session = $this->threadManager->startCoverLetterSession($user, $jobPost);
            }

            Log::debug("New session created for regeneration", [
                'document_id' => $document->id,
                'new_session_id' => $session->id,
                'new_thread_id' => $session->thread_id,
            ]);

            // Add the feedback to the thread
            $feedbackMessage = "Please improve the previous {$type} based on this feedback:\n\n";
            $feedbackMessage .= $feedback['feedback'] ?? 'Please improve this document.';
            $feedbackMessage .= "\n\nPrevious version:\n\n";
            $feedbackMessage .= $document->content;

            Log::debug("Adding feedback message to thread", [
                'document_id' => $document->id,
                'session_id' => $session->id,
                'feedback_length' => strlen($feedback['feedback'] ?? ''),
                'message_length' => strlen($feedbackMessage),
            ]);

            $this->threadManager->addMessage($session->thread_id, $feedbackMessage);

            Log::debug("Feedback message added to thread", [
                'document_id' => $document->id,
                'session_id' => $session->id,
            ]);

            // Generate new content
            Log::debug("Generating new content with feedback", [
                'document_id' => $document->id,
                'session_id' => $session->id,
            ]);

            $content = $this->threadManager->generateContent($session);

            Log::debug("New content generated", [
                'document_id' => $document->id,
                'session_id' => $session->id,
                'content_length' => strlen($content),
            ]);

            // Count words
            $wordCount = str_word_count(strip_tags($content));

            Log::debug("Counted words in regenerated content", [
                'document_id' => $document->id,
                'word_count' => $wordCount,
            ]);

            // Update the document with new content
            Log::debug("Updating document with regenerated content", [
                'document_id' => $document->id,
                'type' => $type,
            ]);

            $document->update([
                'thread_session_id' => $session->id,
                'content' => $content,
                'word_count' => $wordCount,
                'file_path' => null, // Clear file path so it will be regenerated
            ]);

            Log::debug("Document updated with regenerated content", [
                'document_id' => $document->id,
                'type' => $type,
            ]);

            // Generate PDF
            Log::debug("Generating PDF for regenerated document", [
                'document_id' => $document->id,
                'type' => $type,
            ]);

            if ($type === 'resume') {
                $this->pdf->generateResumePDF($document);
            } else {
                $this->pdf->generateCoverLetterPDF($document);
            }

            Log::debug("PDF generated for regenerated document", [
                'document_id' => $document->id,
                'type' => $type,
            ]);

            // Validate the new document
            Log::debug("Validating regenerated document", [
                'document_id' => $document->id,
                'type' => $type,
            ]);

            $validation = $this->threadManager->validateDocument($content, $type, $jobPost);

            Log::debug("Regenerated document validated", [
                'document_id' => $document->id,
                'type' => $type,
                'validation_result' => !empty($validation),
            ]);

            // Update document with validation results
            Log::debug("Updating document with validation results", [
                'document_id' => $document->id,
                'type' => $type,
            ]);

            $document->update([
                'rule_compliance' => $validation,
            ]);

            Log::debug("Document updated with validation results", [
                'document_id' => $document->id,
                'type' => $type,
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
