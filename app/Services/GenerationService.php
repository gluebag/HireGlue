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
    protected $pdf;

    /**
     * Create a new service instance.
     *
     * @param ThreadManagementService $threadManager
     * @param PDFService $pdf
     * @return void
     */
    public function __construct(
        ThreadManagementService $threadManager,
        PDFService $pdf
    ) {
        $this->threadManager = $threadManager;
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
            // Start a resume generation session
            $session = $this->threadManager->startResumeSession($user, $jobPost);

            // Generate content
            $content = $this->threadManager->generateContent($session);

            // Count words
            $wordCount = str_word_count(strip_tags($content));

        // Create resume record
        $resume = Resume::create([
            'user_id' => $user->id,
            'job_post_id' => $jobPost->id,
                'thread_session_id' => $session->id,
                'content' => $content,
                'word_count' => $wordCount,
        ]);

        // Generate PDF
        $this->pdf->generateResumePDF($resume);

            // Validate the resume
            $validation = $this->threadManager->validateDocument($content, 'resume', $jobPost);

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
            // Start a cover letter generation session
            $session = $this->threadManager->startCoverLetterSession($user, $jobPost);

            // Generate content
            $content = $this->threadManager->generateContent($session);

            // Count words
            $wordCount = str_word_count(strip_tags($content));

        // Create cover letter record
        $coverLetter = CoverLetter::create([
            'user_id' => $user->id,
            'job_post_id' => $jobPost->id,
                'thread_session_id' => $session->id,
                'content' => $content,
                'word_count' => $wordCount,
        ]);

        // Generate PDF
        $this->pdf->generateCoverLetterPDF($coverLetter);

            // Validate the cover letter
            $validation = $this->threadManager->validateDocument($content, 'cover_letter', $jobPost);

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
