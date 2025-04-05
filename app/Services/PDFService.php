<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Resume;
use App\Models\CoverLetter;
use Illuminate\Support\Facades\Storage;

class PDFService
{
    /**
     * Generate a PDF file for a resume
     * 
     * @param Resume $resume
     * @return string The file path
     */
    public function generateResumePDF(Resume $resume)
    {
        $pdf = PDF::loadView('pdfs.resume', [
            'resume' => $resume,
            'user' => $resume->user,
            'jobPost' => $resume->jobPost,
            'content' => $this->formatContentForPDF($resume->content)
        ]);
        
        $filename = 'resume_' . $resume->id . '_' . time() . '.pdf';
        $path = 'pdfs/' . $filename;
        
        // Save to storage
        Storage::put('public/' . $path, $pdf->output());
        
        // Update the resume
        $resume->update([
            'file_path' => $path,
        ]);
        
        return $path;
    }
    
    /**
     * Generate a PDF file for a cover letter
     * 
     * @param CoverLetter $coverLetter
     * @return string The file path
     */
    public function generateCoverLetterPDF(CoverLetter $coverLetter)
    {
        $pdf = PDF::loadView('pdfs.cover_letter', [
            'coverLetter' => $coverLetter,
            'user' => $coverLetter->user,
            'jobPost' => $coverLetter->jobPost,
            'content' => $this->formatContentForPDF($coverLetter->content)
        ]);
        
        $filename = 'cover_letter_' . $coverLetter->id . '_' . time() . '.pdf';
        $path = 'pdfs/' . $filename;
        
        // Save to storage
        Storage::put('public/' . $path, $pdf->output());
        
        // Update the cover letter
        $coverLetter->update([
            'file_path' => $path,
        ]);
        
        return $path;
    }
    
    /**
     * Format content for PDF rendering
     * 
     * @param string $content
     * @return string
     */
    protected function formatContentForPDF(string $content)
    {
        // Convert markdown to HTML if needed
        if ($this->isMarkdown($content)) {
            return $this->markdownToHtml($content);
        }
        
        // Format line breaks
        $content = nl2br($content);
        
        return $content;
    }
    
    /**
     * Check if content appears to be in markdown format
     * 
     * @param string $content
     * @return bool
     */
    protected function isMarkdown(string $content)
    {
        $markdownIndicators = [
            '# ', '## ', '### ', '#### ', '##### ', '###### ', // Headers
            '- ', '* ', '+ ', '1. ', '2. ', // Lists
            '```', '~~~', // Code blocks
            '[', '![', // Links and images
            '|', '---', // Tables and horizontal rules
            '_', '**', '~~', '`' // Emphasis and inline code
        ];
        
        foreach ($markdownIndicators as $indicator) {
            if (strpos($content, $indicator) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Convert markdown to HTML
     * 
     * @param string $markdown
     * @return string
     */
    protected function markdownToHtml(string $markdown)
    {
        // Use Laravel's built-in Str::markdown helper
        return \Illuminate\Support\Str::markdown($markdown);
    }
}
