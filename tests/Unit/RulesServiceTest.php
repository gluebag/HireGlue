<?php

namespace Tests\Unit;

use App\Models\Rule;
use App\Services\RulesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RulesServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var RulesService
     */
    protected $rulesService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->rulesService = new RulesService();
        
        // Create some test rules
        Rule::create([
            'name' => 'Word Count Test',
            'description' => 'Test rule for word count',
            'type' => 'resume',
            'source' => 'Tests',
            'importance' => 10,
            'validation_logic' => json_encode(['type' => 'word_count', 'min' => 100, 'max' => 500])
        ]);
        
        Rule::create([
            'name' => 'Page Count Test',
            'description' => 'Test rule for page count',
            'type' => 'cover_letter',
            'source' => 'Tests',
            'importance' => 8,
            'validation_logic' => json_encode(['type' => 'page_count', 'max' => 1])
        ]);
        
        Rule::create([
            'name' => 'Both Types Test',
            'description' => 'Test rule for both document types',
            'type' => 'both',
            'source' => 'Tests',
            'importance' => 5,
            'validation_logic' => null
        ]);
    }

    public function test_can_get_all_rules()
    {
        $rules = $this->rulesService->getAllRules();
        
        $this->assertCount(3, $rules);
        $this->assertEquals('Word Count Test', $rules->first()->name);
    }
    
    public function test_can_filter_rules_by_type()
    {
        $resumeRules = $this->rulesService->getAllRules('resume');
        
        // Should include 'resume' and 'both' types
        $this->assertCount(2, $resumeRules);
        
        $coverLetterRules = $this->rulesService->getAllRules('cover_letter');
        
        // Should include 'cover_letter' and 'both' types
        $this->assertCount(2, $coverLetterRules);
    }
    
    public function test_can_validate_word_count_rule()
    {
        $shortText = 'This is a short text with only a few words.';
        $appropriateText = str_repeat('Lorem ipsum dolor sit amet. ', 20); // ~100 words
        $longText = str_repeat('Lorem ipsum dolor sit amet. ', 120); // ~600 words
        
        $rule = Rule::where('name', 'Word Count Test')->first();
        
        // Should fail (too short)
        $this->assertFalse($this->rulesService->checkRule($shortText, $rule, []));
        
        // Should pass
        $this->assertTrue($this->rulesService->checkRule($appropriateText, $rule, []));
        
        // Should fail (too long)
        $this->assertFalse($this->rulesService->checkRule($longText, $rule, []));
    }
    
    public function test_validate_content_returns_results()
    {
        $text = str_repeat('Lorem ipsum dolor sit amet. ', 20); // ~100 words
        
        $results = $this->rulesService->validateContent($text, 'resume');
        
        $this->assertIsArray($results);
        $this->assertCount(2, $results); // Should have results for resume rules
        
        foreach ($results as $result) {
            $this->assertArrayHasKey('rule', $result);
            $this->assertArrayHasKey('passed', $result);
            $this->assertArrayHasKey('importance', $result);
        }
    }
}
