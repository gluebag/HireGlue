<?php

namespace Tests\Unit;

use App\Models\JobPost;
use App\Models\OpenAIPrompt;
use App\Models\User;
use App\Services\OpenAIService;
use App\Services\RulesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use OpenAI\Laravel\Facades\OpenAI as OpenAIFacade;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Responses\Meta\MetaInformation;
use Tests\TestCase;

class OpenAIServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $openAIService;
    protected $rulesServiceMock;
    protected $user;
    protected $jobPost;
    protected $prompt;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the RulesService
        $this->rulesServiceMock = Mockery::mock(RulesService::class);
        $this->rulesServiceMock->shouldReceive('getAllRules')
            ->andReturn(collect([]));
        
        $this->openAIService = new OpenAIService($this->rulesServiceMock);
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test job post
        $this->jobPost = JobPost::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        // Create test prompt
        $this->prompt = OpenAIPrompt::create([
            'name' => 'resume_generation',
            'type' => 'resume',
            'prompt_template' => 'Generate a resume for {{job_data}} with user {{user_data}}',
            'parameters' => json_encode(['job_data', 'user_data']),
            'model' => 'gpt-4o',
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'active' => true
        ]);
        
        OpenAIPrompt::create([
            'name' => 'cover_letter_generation',
            'type' => 'cover_letter',
            'prompt_template' => 'Generate a cover letter for {{job_data}} with user {{user_data}}',
            'parameters' => json_encode(['job_data', 'user_data']),
            'model' => 'gpt-4o',
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'active' => true
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_generate_resume()
    {
        // Mock the OpenAI response
        $mockResponse = $this->createMockOpenAIResponse(
            'This is a generated resume content',
            $this->prompt->model,
            ['prompt_tokens' => 100, 'completion_tokens' => 200, 'total_tokens' => 300]
        );
        
        Mockery::mock('alias:OpenAI\Laravel\Facades\OpenAI')
            ->shouldReceive('chat->create')
            ->once()
            ->andReturn($mockResponse);
        
        $result = $this->openAIService->generateResume($this->jobPost, $this->user);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertEquals('This is a generated resume content', $result['content']);
        $this->assertEquals($this->prompt->model, $result['metadata']['model']);
    }
    
    public function test_can_generate_cover_letter()
    {
        // Mock the OpenAI response
        $mockResponse = $this->createMockOpenAIResponse(
            'This is a generated cover letter content',
            'gpt-4o',
            ['prompt_tokens' => 100, 'completion_tokens' => 200, 'total_tokens' => 300]
        );
        
        Mockery::mock('alias:OpenAI\Laravel\Facades\OpenAI')
            ->shouldReceive('chat->create')
            ->once()
            ->andReturn($mockResponse);
        
        $result = $this->openAIService->generateCoverLetter($this->jobPost, $this->user);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertEquals('This is a generated cover letter content', $result['content']);
    }
    
    public function test_can_generate_with_feedback()
    {
        // Mock the OpenAI response
        $mockResponse = $this->createMockOpenAIResponse(
            'This is a regenerated content with feedback',
            'gpt-4o',
            ['prompt_tokens' => 150, 'completion_tokens' => 250, 'total_tokens' => 400]
        );
        
        Mockery::mock('alias:OpenAI\Laravel\Facades\OpenAI')
            ->shouldReceive('chat->create')
            ->once()
            ->andReturn($mockResponse);
        
        $result = $this->openAIService->generateResume(
            $this->jobPost, 
            $this->user, 
            null, 
            ['feedback' => 'Please make it more professional']
        );
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertEquals('This is a regenerated content with feedback', $result['content']);
        $this->assertEquals(['feedback' => 'Please make it more professional'], $result['metadata']['extra_context']);
    }
    
    /**
     * Create a mock OpenAI API response
     */
    protected function createMockOpenAIResponse(string $content, string $model, array $usage)
    {
        $mockUsage = Mockery::mock();
        $mockUsage->shouldReceive('toArray')->andReturn($usage);
        
        $mockChoice = Mockery::mock();
        $mockChoice->message = Mockery::mock();
        $mockChoice->message->content = $content;
        
        $mockResponse = Mockery::mock();
        $mockResponse->choices = [$mockChoice];
        $mockResponse->usage = $mockUsage;
        
        return $mockResponse;
    }
}
