<?php

namespace App\Services;

use App\Models\JobPost;
use App\Models\Resume;
use App\Models\CoverLetter;
use Illuminate\Support\Str;

class GenerationService
{
    protected $openAI;
    protected $rules;
    protected $pdf;
    
    /**
     * Create a new service instance.
     * 
     * @param OpenAIService $openAI
     * @param RulesService $rules
     * @param PDFService $pdf
     * @return void
     */
    public function __construct(
        OpenAIService $openAI,
        RulesService $rules,
        PDFService $pdf
    ) {
        $this->openAI = $openAI;
        $this->rules = $rules;
        $this->pdf = $pdf;
    }
    
    /**
     * Generate a resume for a job post
     *
     * @param JobPost $jobPost
     * @return Resume
     */
    public function generateResume(JobPost $jobPost)
    {
        $user = $jobPost->user;
        
        // Generate content with OpenAI
        $result = $this->openAI->generateResume($jobPost, $user, null, []);
        
        // Validate against rules
        $compliance = $this->rules->validateContent(
            $result['content'],
            'resume',
            ['job_post' => $jobPost->toArray(), 'user' => $user->toArray()]
        );
        
        // Create resume record
        $resume = Resume::create([
            'user_id' => $user->id,
            'job_post_id' => $jobPost->id,
            'content' => $result['content'],
            'word_count' => str_word_count(strip_tags($result['content'])),
            'rule_compliance' => json_encode($compliance),
            'generation_metadata' => json_encode($result['metadata']),
        ]);
        
        // Generate PDF
        $this->pdf->generateResumePDF($resume);
        
        return $resume;
    }
    
    /**
     * Generate a cover letter for a job post
     *
     * @param JobPost $jobPost
     * @return CoverLetter
     */
    public function generateCoverLetter(JobPost $jobPost)
    {
        $user = $jobPost->user;
        
        // Generate content with OpenAI
        $result = $this->openAI->generateCoverLetter($jobPost, $user);
        
        // Validate against rules
        $compliance = $this->rules->validateContent(
            $result['content'],
            'cover_letter',
            ['job_post' => $jobPost->toArray(), 'user' => $user->toArray()]
        );
        
        // Create cover letter record
        $coverLetter = CoverLetter::create([
            'user_id' => $user->id,
            'job_post_id' => $jobPost->id,
            'content' => $result['content'],
            'word_count' => str_word_count(strip_tags($result['content'])),
            'rule_compliance' => json_encode($compliance),
            'generation_metadata' => json_encode($result['metadata']),
        ]);
        
        // Generate PDF
        $this->pdf->generateCoverLetterPDF($coverLetter);
        
        return $coverLetter;
    }
    
    /**
     * Regenerate document with feedback
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
        
        // Prepare feedback for the OpenAI API
        $feedbackText = $feedback['feedback'] ?? 'Please improve this document.';
        
        // Determine which API method to call
        $methodName = 'generate' . Str::studly($type);
        
        // Add feedback to the prompt
        $extraContext = [
            'feedback' => $feedbackText,
            'previous_content' => $document->content
        ];
        
        // Generate new content
        $result = $this->openAI->$methodName($jobPost, $user, null, $extraContext);
        
        // Validate against rules
        $compliance = $this->rules->validateContent(
            $result['content'],
            $type,
            [
                'job_post' => $jobPost->toArray(), 
                'user' => $user->toArray(),
                'feedback' => $feedbackText
            ]
        );
        
        // Update the document
        $document->update([
            'content' => $result['content'],
            'word_count' => str_word_count(strip_tags($result['content'])),
            'rule_compliance' => json_encode($compliance),
            'generation_metadata' => json_encode(array_merge(
                $result['metadata'],
                ['feedback' => $feedbackText]
            )),
            'file_path' => null, // Clear file path so it will be regenerated
        ]);
        
        // Generate PDF
        if ($type === 'resume') {
            $this->pdf->generateResumePDF($document);
        } else {
            $this->pdf->generateCoverLetterPDF($document);
        }
        
        return $document;
    }
}
