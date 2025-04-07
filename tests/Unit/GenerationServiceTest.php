<?php

namespace Tests\Unit;

use App\Models\CoverLetter;
use App\Models\JobPost;
use App\Models\Resume;
use App\Models\User;
use App\Services\GenerationService;
use App\Services\OpenAIService;
use App\Services\PDFService;
use App\Services\RulesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GenerationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $openAIServiceMock;
    protected $rulesServiceMock;
    protected $pdfServiceMock;
    protected $generationService;
    protected $user;
    protected $jobPost;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mocks
        $this->openAIServiceMock = Mockery::mock(OpenAIService::class);
        $this->rulesServiceMock = Mockery::mock(RulesService::class);
        $this->pdfServiceMock = Mockery::mock(PDFService::class);

        // Fix: Create the service with correct property names
        $this->generationService = new GenerationService(
            $this->openAIServiceMock,
            $this->rulesServiceMock,
            $this->pdfServiceMock
        );

        // Inject the mocks into the service with reflection to match property names
        $reflection = new \ReflectionClass($this->generationService);

        $openAIProp = $reflection->getProperty('openAI');
        $openAIProp->setAccessible(true);
        $openAIProp->setValue($this->generationService, $this->openAIServiceMock);

        $rulesProp = $reflection->getProperty('rules');
        $rulesProp->setAccessible(true);
        $rulesProp->setValue($this->generationService, $this->rulesServiceMock);

        $pdfProp = $reflection->getProperty('pdf');
        $pdfProp->setAccessible(true);
        $pdfProp->setValue($this->generationService, $this->pdfServiceMock);

        // Create test user
        $this->user = User::factory()->create();

        // Create test job post
        $this->jobPost = JobPost::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_generate_resume()
    {
        // Set up expectations for the mocks
        $this->openAIServiceMock->shouldReceive('generateResumeLegacy')
            ->once()
            ->with(Mockery::type(JobPost::class), Mockery::type(User::class), null, [])
            ->andReturn([
                'content' => 'Generated resume content',
                'metadata' => ['model' => 'gpt-4', 'usage' => ['total_tokens' => 500]]
            ]);

        $this->rulesServiceMock->shouldReceive('validateContent')
            ->once()
            ->andReturn(['rule1' => ['passed' => true]]);

        $this->pdfServiceMock->shouldReceive('generateResumePDF')
            ->once()
            ->andReturn('pdfs/resume_1_12345.pdf');

        $resume = $this->generationService->generateResume($this->jobPost);

        $this->assertInstanceOf(Resume::class, $resume);
        $this->assertEquals('Generated resume content', $resume->content);
        $this->assertEquals($this->user->id, $resume->user_id);
        $this->assertEquals($this->jobPost->id, $resume->job_post_id);
    }

    public function test_can_generate_cover_letter()
    {
        // Set up expectations for the mocks
        $this->openAIServiceMock->shouldReceive('generateCoverLetterLegacy')
            ->once()
            ->with(Mockery::on(function ($job) {
                return $job instanceof JobPost;
            }), Mockery::on(function ($user) {
                return $user instanceof User;
            }))
            ->andReturn([
                'content' => 'Generated cover letter content',
                'metadata' => ['model' => 'gpt-4', 'usage' => ['total_tokens' => 400]]
            ]);

        $this->rulesServiceMock->shouldReceive('validateContent')
            ->once()
            ->andReturn(['rule1' => ['passed' => true]]);

        $this->pdfServiceMock->shouldReceive('generateCoverLetterPDF')
            ->once()
            ->andReturn('pdfs/cover_letter_1_12345.pdf');

        $coverLetter = $this->generationService->generateCoverLetter($this->jobPost);

        $this->assertInstanceOf(CoverLetter::class, $coverLetter);
        $this->assertEquals('Generated cover letter content', $coverLetter->content);
        $this->assertEquals($this->user->id, $coverLetter->user_id);
        $this->assertEquals($this->jobPost->id, $coverLetter->job_post_id);
    }

    public function test_can_regenerate_with_feedback()
    {
        // Create test resume
        $resume = Resume::factory()->create([
            'user_id' => $this->user->id,
            'job_post_id' => $this->jobPost->id,
            'content' => 'Original resume content'
        ]);

        // Set up expectations for the mocks
        $this->openAIServiceMock->shouldReceive('generateResumeLegacy')
            ->once()
            ->withArgs(function ($jobPost, $user, $promptName, $extraContext) {
                return $jobPost instanceof JobPost &&
                       $user instanceof User &&
                       $extraContext['feedback'] === 'Make it better' &&
                       $extraContext['previous_content'] === 'Original resume content';
            })
            ->andReturn([
                'content' => 'Improved resume content',
                'metadata' => ['model' => 'gpt-4', 'usage' => ['total_tokens' => 600]]
            ]);

        $this->rulesServiceMock->shouldReceive('validateContent')
            ->once()
            ->andReturn(['rule1' => ['passed' => true]]);

        $this->pdfServiceMock->shouldReceive('generateResumePDF')
            ->once()
            ->andReturn('pdfs/resume_1_67890.pdf');

        $regenerated = $this->generationService->regenerateWithFeedback($resume, [
            'feedback' => 'Make it better'
        ]);

        $this->assertInstanceOf(Resume::class, $regenerated);
        $this->assertEquals('Improved resume content', $regenerated->content);
        $this->assertEquals($this->user->id, $regenerated->user_id);
        $this->assertEquals($this->jobPost->id, $regenerated->job_post_id);
        $this->assertNull($regenerated->file_path); // Should be reset so PDF is regenerated
    }
}
