<?php

namespace Tests\Unit;

use App\Models\CoverLetter;
use App\Models\JobPost;
use App\Models\Resume;
use App\Models\User;
use App\Services\PDFService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class PDFServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $pdfService;
    protected $user;
    protected $jobPost;
    protected $resume;
    protected $coverLetter;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->pdfService = new PDFService();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test job post
        $this->jobPost = JobPost::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        // Create test resume
        $this->resume = Resume::factory()->create([
            'user_id' => $this->user->id,
            'job_post_id' => $this->jobPost->id,
            'content' => 'This is a test resume content'
        ]);
        
        // Create test cover letter
        $this->coverLetter = CoverLetter::factory()->create([
            'user_id' => $this->user->id,
            'job_post_id' => $this->jobPost->id,
            'content' => 'This is a test cover letter content'
        ]);
        
        // Mock the storage
        Storage::fake('public');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_generate_resume_pdf()
    {
        // Mock PDF facade
        $mockPdf = Mockery::mock();
        $mockPdf->shouldReceive('output')->andReturn('PDF Content');
        Pdf::shouldReceive('loadView')->once()->andReturn($mockPdf);
        
        $path = $this->pdfService->generateResumePDF($this->resume);
        
        // Check the PDF was stored
        $this->assertTrue(Storage::disk('public')->exists($path));
        
        // Check resume was updated
        $this->resume->refresh();
        $this->assertEquals($path, $this->resume->file_path);
    }
    
    public function test_can_generate_cover_letter_pdf()
    {
        // Mock PDF facade
        $mockPdf = Mockery::mock();
        $mockPdf->shouldReceive('output')->andReturn('PDF Content');
        Pdf::shouldReceive('loadView')->once()->andReturn($mockPdf);
        
        $path = $this->pdfService->generateCoverLetterPDF($this->coverLetter);
        
        // Check the PDF was stored
        $this->assertTrue(Storage::disk('public')->exists($path));
        
        // Check cover letter was updated
        $this->coverLetter->refresh();
        $this->assertEquals($path, $this->coverLetter->file_path);
    }
    
    public function test_can_detect_markdown_content()
    {
        $markdownContent = "# Heading\n\n- List item 1\n- List item 2\n\n**Bold text**";
        $regularContent = "This is just regular text without any markdown.";
        
        // Use reflection to access private method
        $reflection = new \ReflectionClass($this->pdfService);
        $method = $reflection->getMethod('isMarkdown');
        $method->setAccessible(true);
        
        $this->assertTrue($method->invoke($this->pdfService, $markdownContent));
        $this->assertFalse($method->invoke($this->pdfService, $regularContent));
    }
}
