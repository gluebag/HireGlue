<?php

namespace App\Services;

use App\Models\Rule;

class RulesService
{
    /**
     * Get all rules or filter by type
     * 
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRules(string $type = null)
    {
        if ($type) {
            return Rule::where('type', $type)
                ->orWhere('type', 'both')
                ->orderBy('importance', 'desc')
                ->get();
        }
        
        return Rule::orderBy('importance', 'desc')->get();
    }
    
    /**
     * Validate content against applicable rules
     * 
     * @param string $content
     * @param string $type
     * @param array $metadata
     * @return array
     */
    public function validateContent(string $content, string $type, array $metadata = [])
    {
        $rules = $this->getAllRules($type);
        $results = [];
        
        foreach ($rules as $rule) {
            $results[$rule->id] = [
                'rule' => $rule->name,
                'passed' => $this->checkRule($content, $rule, $metadata),
                'importance' => $rule->importance,
            ];
        }
        
        return $results;
    }
    
    /**
     * Check content against a specific rule
     * 
     * @param string $content
     * @param Rule $rule
     * @param array $metadata
     * @return bool
     */
    protected function checkRule(string $content, Rule $rule, array $metadata)
    {
        // If no validation logic, default to pass
        if (empty($rule->validation_logic)) {
            return true;
        }
        
        $logic = $rule->validation_logic;
        if (!$logic || !isset($logic['type'])) {
            return true;
        }
        
        switch ($logic['type']) {
            case 'word_count':
                return $this->checkWordCount($content, $logic);
                
            case 'page_count':
                return $this->checkPageCount($content, $logic, $metadata);
                
            case 'keyword_inclusion':
                return $this->checkKeywordInclusion($content, $logic, $metadata);
                
            case 'contact_info':
                return $this->checkContactInfo($content, $metadata);
                
            case 'passive_language':
                return $this->checkPassiveLanguage($content);
                
            case 'personal_pronouns':
                return $this->checkPersonalPronouns($content);
                
            case 'quantifiable_results':
                return $this->checkQuantifiableResults($content);
                
            case 'grammar_spelling':
                return $this->checkGrammarSpelling($content);
                
            case 'formatting_consistency':
                return $this->checkFormattingConsistency($content);
                
            default:
                return true;
        }
    }
    
    /**
     * Check if content meets word count requirements
     */
    private function checkWordCount(string $content, array $logic): bool
    {
        $wordCount = str_word_count($content);
        $min = $logic['min'] ?? 0;
        $max = $logic['max'] ?? PHP_INT_MAX;
        
        return $wordCount >= $min && $wordCount <= $max;
    }
    
    /**
     * Estimate if content meets page count requirements
     */
    private function checkPageCount(string $content, array $logic, array $metadata): bool
    {
        // Estimate page count based on word count
        // Assuming ~450 words per page for resume/cover letter
        $wordCount = str_word_count($content);
        $estimatedPages = ceil($wordCount / 450);
        
        $min = $logic['min'] ?? 1;
        $max = $logic['max'] ?? PHP_INT_MAX;
        
        return $estimatedPages >= $min && $estimatedPages <= $max;
    }
    
    /**
     * Check if content includes required keywords
     */
    private function checkKeywordInclusion(string $content, array $logic, array $metadata): bool
    {
        if (!isset($logic['keywords']) || !is_array($logic['keywords'])) {
            return true;
        }
        
        // Extract keywords from job description if available
        $jobDescription = $metadata['job_post']['job_description'] ?? '';
        $requiredKeywords = $logic['keywords'] ?? [];
        
        // If job description is available, extract keywords from it
        if (!empty($jobDescription) && empty($requiredKeywords)) {
            // Simple keyword extraction - extract nouns and skills
            // This is simplified - in production, you'd want more robust extraction
            $requiredKeywords = $this->extractKeywordsFromJobDescription($jobDescription);
        }
        
        $contentLowercase = strtolower($content);
        $matchCount = 0;
        
        foreach ($requiredKeywords as $keyword) {
            if (strpos($contentLowercase, strtolower($keyword)) !== false) {
                $matchCount++;
            }
        }
        
        // Require at least 70% of keywords to match
        $requiredMatches = max(1, round(count($requiredKeywords) * 0.7));
        return $matchCount >= $requiredMatches;
    }
    
    /**
     * Extract keywords from job description
     */
    private function extractKeywordsFromJobDescription(string $jobDescription): array
    {
        // This is a simplified implementation
        // In production, use NLP libraries for better extraction
        $words = str_word_count($jobDescription, 1);
        $stopWords = ['the', 'and', 'a', 'to', 'of', 'in', 'for', 'with', 'on', 'at'];
        
        return array_diff($words, $stopWords);
    }
    
    /**
     * Check if content includes contact information
     */
    private function checkContactInfo(string $content, array $metadata): bool
    {
        $user = $metadata['user'] ?? null;
        if (!$user) {
            return true;
        }
        
        // Check if basic contact info is included
        $contactInfoPresent = 0;
        
        if (strpos($content, $user['email']) !== false) {
            $contactInfoPresent++;
        }
        
        if (!empty($user['phone_number']) && strpos($content, $user['phone_number']) !== false) {
            $contactInfoPresent++;
        }
        
        if (!empty($user['location']) && strpos($content, $user['location']) !== false) {
            $contactInfoPresent++;
        }
        
        // Require at least 2 contact methods
        return $contactInfoPresent >= 2;
    }
    
    /**
     * Check for passive language
     */
    private function checkPassiveLanguage(string $content): bool
    {
        // Passive voice constructions to look for
        $passiveConstructions = [
            'is done', 'are done', 'was done', 'were done',
            'has been', 'have been', 'had been',
            'is being', 'are being', 'was being', 'were being',
            'will be', 'would be'
        ];
        
        $contentLowercase = strtolower($content);
        $passiveCount = 0;
        
        foreach ($passiveConstructions as $construction) {
            $passiveCount += substr_count($contentLowercase, $construction);
        }
        
        // For a resume/cover letter, passive voice should be minimal
        // Allow up to 3 instances in the entire document
        return $passiveCount <= 3;
    }
    
    /**
     * Check for personal pronouns usage
     */
    private function checkPersonalPronouns(string $content): bool
    {
        $contentLowercase = strtolower($content);
        $pronouns = ['i', 'me', 'my', 'mine', 'myself'];
        
        $pronounCount = 0;
        foreach ($pronouns as $pronoun) {
            // Check for whole word matches only
            preg_match_all('/\b' . $pronoun . '\b/', $contentLowercase, $matches);
            $pronounCount += count($matches[0]);
        }
        
        // For a resume, personal pronouns should be minimal or absent
        // For a cover letter, some pronouns are acceptable
        return $pronounCount <= 5;
    }
    
    /**
     * Check for quantifiable results
     */
    private function checkQuantifiableResults(string $content): bool
    {
        // Look for numbers and percentages as indicators of quantified achievements
        preg_match_all('/\d+%|\d+\s*percent|\$\d+|\d+\s*million|\d+\s*billion|\d+\s*thousand/i', $content, $matches);
        
        // Should have at least 3 quantified achievements
        return count($matches[0]) >= 3;
    }
    
    /**
     * Basic check for potential grammar/spelling issues
     */
    private function checkGrammarSpelling(string $content): bool
    {
        // This is a simplified implementation
        // In production, you'd integrate with a grammar/spell checking API
        
        // Common grammar errors to check
        $commonErrors = [
            'their/there/they\'re',
            'you\'re/your',
            'its/it\'s',
            'affect/effect',
            'then/than',
            'to/too/two',
            'alot', // should be "a lot"
            'seperate', // should be "separate"
            'definately', // should be "definitely"
            'recieve', // should be "receive"
        ];
        
        $contentLowercase = strtolower($content);
        $errorCount = 0;
        
        foreach ($commonErrors as $error) {
            if (strpos($contentLowercase, $error) !== false) {
                $errorCount++;
            }
        }
        
        // For a professional document, there should be no obvious errors
        return $errorCount === 0;
    }
    
    /**
     * Check for consistent formatting
     */
    private function checkFormattingConsistency(string $content): bool
    {
        // This is challenging to implement without parsing the actual document
        // For a basic implementation, we'll check for consistent use of bullet points
        
        $bulletPoints = [
            '• ', '- ', '* ', '· ',
            '→ ', '✓ ', '✔ ', '→ ',
            '○ ', '◆ ', '□ ', '■ '
        ];
        
        $bulletCounts = [];
        foreach ($bulletPoints as $bullet) {
            $bulletCounts[$bullet] = substr_count($content, $bullet);
        }
        
        // Remove zero counts
        $bulletCounts = array_filter($bulletCounts);
        
        // If multiple bullet styles are used, it's inconsistent
        return count($bulletCounts) <= 1;
    }
}
