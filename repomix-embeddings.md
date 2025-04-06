This file is a merged representation of a subset of the codebase, containing specifically included files, combined into a single document by Repomix.
The content has been processed where content has been compressed (code blocks are separated by ⋮---- delimiter), security check has been disabled.

# Directory Structure
```
app/
  Models/
    CoverLetter.php
    Education.php
    JobPost.php
    JobRequirementEmbedding.php
    OpenAIPrompt.php
    Project.php
    Resume.php
    Rule.php
    Skill.php
    SkillEmbedding.php
    ThreadSession.php
    User.php
    WorkExperience.php
  Nova/
    Actions/
      ConvertGoogleJobPost.php
      GenerateApplicationMaterials.php
      GenerateCoverLetter.php
      GenerateResume.php
      ImportJobPostFromContent.php
      RegenerateWithFeedback.php
    Dashboards/
      Main.php
    Repeaters/
      EducationItem.php
      ExperienceItem.php
      SkillItem.php
    CoverLetter.php
    Education.php
    JobPost.php
    OpenAIPrompt.php
    Project.php
    Resource.php
    Resume.php
    Rule.php
    Skill.php
    ThreadSession.php
    User.php
    WorkExperience.php
  Providers/
    AppServiceProvider.php
    HorizonServiceProvider.php
    NovaServiceProvider.php
    TelescopeServiceProvider.php
  Services/
    AssistantsService.php
    EmbeddingsService.php
    GenerationService.php
    JobPostAIService.php
    OpenAIService.php
    PDFService.php
    PromptEngineeringService.php
    RulesService.php
    ThreadManagementService.php
database/
  factories/
    CoverLetterFactory.php
    JobPostFactory.php
    ResumeFactory.php
    UserFactory.php
  migrations/
    0001_01_01_000000_create_users_table.php
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2025_04_05_174213_create_telescope_entries_table.php
    2025_04_05_175254_add_two_factor_columns_to_users_table.php
    2025_04_05_185149_create_job_posts_table.php
    2025_04_05_185524_create_work_experiences_table.php
    2025_04_05_185606_create_education_table.php
    2025_04_05_185713_create_skills_table.php
    2025_04_05_190541_create_projects_table.php
    2025_04_05_190552_create_resumes_table.php
    2025_04_05_190603_create_cover_letters_table.php
    2025_04_05_190617_create_rules_table.php
    2025_04_05_190623_create_openai_prompts_table.php
    2025_04_06_040907_create_thread_sessions_table.php
    2025_04_06_041204_add_thread_session_id_to_resumes_and_cvs.php
    2025_04_06_044443_create_skill_embeddings_table.php
    2025_04_06_044453_create_job_requirement_embeddings_table.php
  seeders/
    DatabaseSeeder.php
    OpenAIPromptsSeeder.php
    RulesSeeder.php
    UserSeeder.php
composer.json
repomix.config.json
```

# Files

## File: app/Models/Education.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class Education extends Model
⋮----
protected $table = 'education';
⋮----
protected $fillable = [
⋮----
protected $casts = [
⋮----
public function user()
⋮----
return $this->belongsTo(User::class);
````

## File: app/Models/JobPost.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class JobPost extends Model
⋮----
protected $fillable = [
⋮----
protected $casts = [
⋮----
public function user()
⋮----
return $this->belongsTo(User::class);
⋮----
public function resumes()
⋮----
return $this->hasMany(Resume::class);
⋮----
public function coverLetters()
⋮----
return $this->hasMany(CoverLetter::class);
````

## File: app/Models/JobRequirementEmbedding.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
⋮----
class JobRequirementEmbedding extends Model
⋮----
protected $fillable = [
⋮----
/**
     * Get the job post that the embedding is for.
     */
public function jobPost()
⋮----
return $this->belongsTo(JobPost::class);
````

## File: app/Models/OpenAIPrompt.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class OpenAIPrompt extends Model
⋮----
protected $table = 'openai_prompts';
⋮----
protected $fillable = [
⋮----
protected $casts = [
````

## File: app/Models/Project.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class Project extends Model
⋮----
protected $fillable = [
⋮----
protected $casts = [
⋮----
public function user()
⋮----
return $this->belongsTo(User::class);
````

## File: app/Models/Rule.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class Rule extends Model
⋮----
protected $fillable = [
⋮----
protected $casts = [
````

## File: app/Models/Skill.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class Skill extends Model
⋮----
protected $fillable = [
⋮----
public function user()
⋮----
return $this->belongsTo(User::class);
````

## File: app/Models/SkillEmbedding.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
⋮----
class SkillEmbedding extends Model
⋮----
protected $fillable = [
⋮----
/**
     * Get the user that owns the skill embedding.
     */
public function user()
⋮----
return $this->belongsTo(User::class);
⋮----
/**
     * Get the skill that the embedding is for.
     */
public function skill()
⋮----
return $this->belongsTo(Skill::class);
````

## File: app/Models/ThreadSession.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class ThreadSession extends Model
⋮----
protected $fillable = [
⋮----
protected $casts = [
⋮----
/**
     * Get the user that owns the session
     */
public function user()
⋮----
return $this->belongsTo(User::class);
⋮----
/**
     * Get the job post this session is for
     */
public function jobPost()
⋮----
return $this->belongsTo(JobPost::class);
⋮----
/**
     * Get the resume associated with this session
     */
public function resume()
⋮----
return $this->hasOne(Resume::class);
⋮----
/**
     * Get the cover letter associated with this session
     */
public function coverLetter()
⋮----
return $this->hasOne(CoverLetter::class);
⋮----
/**
     * Check if the session is for a resume
     */
public function isResumeSession(): bool
⋮----
/**
     * Check if the session is for a cover letter
     */
public function isCoverLetterSession(): bool
⋮----
/**
     * Check if the session is completed
     */
public function isCompleted(): bool
⋮----
/**
     * Check if the session failed
     */
public function isFailed(): bool
⋮----
/**
     * Check if the session is in progress
     */
public function isInProgress(): bool
````

## File: app/Models/WorkExperience.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class WorkExperience extends Model
⋮----
protected $fillable = [
⋮----
protected $casts = [
⋮----
public function user()
⋮----
return $this->belongsTo(User::class);
````

## File: app/Nova/Actions/ConvertGoogleJobPost.php
````php
namespace App\Nova\Actions;
⋮----
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class ConvertGoogleJobPost extends Action //  implements ShouldQueue
⋮----
/**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
public $confirmButtonText = 'Convert Job Post';
⋮----
/**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
public $confirmText = 'Enter the URL of the Google job post you want to import.';
⋮----
/**
     * Indicates if this action is only available on the resource detail view.
     *
     * @var bool
     */
public $onlyOnDetail = false;
⋮----
/**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
Text::make('Job Post URL', 'job_post_url')
->rules('required', 'url')
->help('Enter the URL of the Google job post you want to import'),
⋮----
Text::make('API Key', 'api_key')
->rules('required')
->help('Your html-to-markdown API key')
->default(config('services.html_to_markdown.api_key', '')),
⋮----
/**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
public function handle(ActionFields $fields, Collection $models)
⋮----
// Step 1: Fetch HTML content from Google job post
⋮----
$htmlContent = $this->fetchJobPostHtml($jobPostUrl);
⋮----
Log::error('Failed to fetch job post HTML content', [
⋮----
return Action::danger('Failed to fetch job post HTML content.');
⋮----
// Step 2: Convert HTML to Markdown using html-to-markdown API
Log::debug('Converting HTML to Markdown', [
⋮----
$markdown = $this->convertHtmlToMarkdown($jobPostUrl, $htmlContent, $fields->api_key);
⋮----
return Action::danger('Failed to convert HTML to Markdown.');
⋮----
Log::debug('Markdown content', [
⋮----
// Step 3: Parse Markdown content and extract relevant info
$jobData = $this->parseJobPostMarkdown($markdown);
⋮----
// Step 4: Create or update JobPost model
$this->createOrUpdateJobPost($models->first(), $jobData, $jobPostUrl);
⋮----
return Action::message('Job post data has been successfully imported!');
⋮----
return Action::danger('Error: ' . $e->getMessage());
⋮----
/**
     * Fetch the HTML content from the Google job post URL.
     *
     * @param string $url
     * @return string|null
     */
protected function fetchJobPostHtml(string $url): ?string
⋮----
$response = Http::timeout(30)->get($url);
⋮----
Log::debug('Google Job Post HTML Response', [
⋮----
'response_successful' => $response->successful(),
'status' => $response->status(),
'body' => $response->body(),
⋮----
if ($response->successful()) {
return $response->body();
⋮----
// Handle non-successful response
throw new \RuntimeException('Failed to fetch job post HTML: ' . $response->status());
⋮----
throw new \RuntimeException('Failed to fetch job post HTML: ' . $e->getMessage());
⋮----
/**
     * Convert HTML content to Markdown using html-to-markdown API.
     *
     * @param string $html
     * @param string $apiKey
     * @return string|null
     */
protected function convertHtmlToMarkdown(string $url, string $html, string $apiKey): ?string
⋮----
$response = Http::withHeaders([
⋮----
])->post('https://api.html-to-markdown.com/v1/convert', [
⋮----
// needs to be just scheme and domain, no path
⋮----
return $response->json('markdown');
⋮----
/**
     * Parse Markdown content and extract relevant job information.
     *
     * @param string $markdown
     * @return array
     */
protected function parseJobPostMarkdown(string $markdown): array
⋮----
// Initialize job data with default values
⋮----
// Extract job title using regex
⋮----
// Extract location information
⋮----
// If location indicates multiple locations, mark as hybrid
⋮----
// Extract minimum qualifications
⋮----
$qualifications = $this->extractListItems($minQualText);
⋮----
// If qualification mentions education/degree
⋮----
$jobData['required_education'][] = $this->parseEducationRequirement($qual);
⋮----
// If qualification mentions experience
⋮----
$jobData['required_experience'][] = $this->parseExperienceRequirement($qual, $expMatch[1]);
⋮----
// If qualification mentions programming languages
⋮----
$this->extractSkills($qual, $jobData['required_skills']);
⋮----
// Extract preferred qualifications
⋮----
$preferredQuals = $this->extractListItems($prefQualText);
⋮----
// Check if it's a skill
⋮----
$this->extractSkills($skillMatch[2], $jobData['preferred_skills']);
⋮----
// Extract about the job / job description
⋮----
// Extract responsibilities
⋮----
$responsibilities = $this->extractListItems($respMatches[1]);
⋮----
// Extract salary range
⋮----
// Format job data for Nova repeaters
return $this->formatJobDataForNova($jobData);
⋮----
/**
     * Format job data for Nova repeaters
     *
     * @param array $jobData
     * @return array
     */
protected function formatJobDataForNova(array $jobData): array
⋮----
// Format required skills
⋮----
// Format preferred skills
⋮----
// Format required experience
⋮----
// Format required education
⋮----
/**
     * Extract list items from markdown text.
     *
     * @param string $markdownText
     * @return array
     */
protected function extractListItems(string $markdownText): array
⋮----
/**
     * Parse education requirement from qualification text.
     *
     * @param string $text
     * @return array
     */
protected function parseEducationRequirement(string $text): array
⋮----
/**
     * Parse experience requirement from qualification text.
     *
     * @param string $text
     * @param int $years
     * @return array
     */
protected function parseExperienceRequirement(string $text, int $years): array
⋮----
/**
     * Extract skills from text.
     *
     * @param string $text
     * @param array &$skillsArray
     * @return void
     */
protected function extractSkills(string $text, array &$skillsArray): void
⋮----
'level' => 4 // Assume advanced level for required skills
⋮----
/**
     * Create or update job post model with the extracted data.
     *
     * @param \App\Models\JobPost|null $model
     * @param array $jobData
     * @param string $jobPostUrl
     * @return void
     */
protected function createOrUpdateJobPost($model, array $jobData, string $jobPostUrl): void
⋮----
$model->update($data);
⋮----
$model = \App\Models\JobPost::create(array_merge([
'user_id' => request()->user()->id,
````

## File: app/Nova/Actions/GenerateApplicationMaterials.php
````php
namespace App\Nova\Actions;
⋮----
use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class GenerateApplicationMaterials extends Action
⋮----
/**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
public function handle(ActionFields $fields, Collection $models)
⋮----
$resume = $generationService->generateResume($jobPost);
$coverLetter = $generationService->generateCoverLetter($jobPost);
⋮----
return Action::message("Resume (ID: {$resume->id}) and Cover Letter (ID: {$coverLetter->id}) generated successfully!");
⋮----
return Action::danger("Failed to generate application materials: {$e->getMessage()}");
⋮----
/**
     * Get the displayable name of the action.
     *
     * @return string
     */
public function name()
````

## File: app/Nova/Actions/GenerateCoverLetter.php
````php
namespace App\Nova\Actions;
⋮----
use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class GenerateCoverLetter extends Action
⋮----
/**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
public function handle(ActionFields $fields, Collection $models)
⋮----
$coverLetter = $generationService->generateCoverLetter($jobPost);
⋮----
return Action::message("Cover letter generated successfully! ID: {$coverLetter->id}");
⋮----
return Action::danger("Failed to generate cover letter: {$e->getMessage()}");
⋮----
/**
     * Get the displayable name of the action.
     *
     * @return string
     */
public function name()
````

## File: app/Nova/Actions/GenerateResume.php
````php
namespace App\Nova\Actions;
⋮----
use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class GenerateResume extends Action
⋮----
/**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
public function handle(ActionFields $fields, Collection $models)
⋮----
$resume = $generationService->generateResume($jobPost);
⋮----
return Action::message("Resume generated successfully! ID: {$resume->id}");
⋮----
return Action::danger("Failed to generate resume: {$e->getMessage()}");
⋮----
/**
     * Get the displayable name of the action.
     *
     * @return string
     */
public function name()
````

## File: app/Nova/Actions/ImportJobPostFromContent.php
````php
namespace App\Nova\Actions;
⋮----
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\JobPost;
use App\Services\JobPostAIService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
⋮----
class ImportJobPostFromContent extends Action
⋮----
/**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
public $confirmButtonText = 'Import Job Post';
⋮----
/**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
public $confirmText = 'Import a job post from URL, HTML, or markdown content';
⋮----
/**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
public function handle(ActionFields $fields, Collection $models): mixed
⋮----
// URL is now just for reference and populating the job_post_url field
⋮----
// If we also have HTML or markdown content, use that
⋮----
return $this->importFromHtml($fields->html_content, $url, $fields->api_key);
⋮----
return $this->importFromMarkdown($fields->markdown_content, $url);
⋮----
return $this->importFromUrl($url, $fields->import_type);
⋮----
return $this->importFromHtml($fields->html_content, null, $fields->api_key);
⋮----
return $this->importFromMarkdown($fields->markdown_content);
⋮----
return Action::danger('Please provide a URL, HTML content, or markdown content.');
⋮----
Log::error('Error importing job post: ' . $e->getMessage(), [
⋮----
'fields' => $fields->toArray()
⋮----
return Action::danger('Error importing job post: ' . $e->getMessage());
⋮----
/**
     * Import from URL
     */
protected function importFromUrl($url, $importType)
⋮----
// Validate URL
⋮----
return Action::danger('Invalid URL provided.');
⋮----
// Fetch content from URL
$response = Http::get($url);
⋮----
if (!$response->successful()) {
return Action::danger('Failed to fetch content from URL: ' . $response->status());
⋮----
$content = $response->body();
⋮----
// Parse content based on import type
⋮----
return $this->analyzeJobContent($content, $url);
⋮----
return Action::danger('Unsupported import type.');
⋮----
/**
     * Import from HTML content
     */
protected function importFromHtml($htmlContent, $url = null, $apiKey = null)
⋮----
// Convert HTML to markdown using the html-to-markdown API
$markdown = $this->convertHtmlToMarkdown($htmlContent, $url, $apiKey);
⋮----
return Action::danger('Failed to convert HTML to Markdown.');
⋮----
// Process the markdown content
return $this->importFromMarkdown($markdown, $url);
⋮----
Log::error('Error converting HTML to Markdown: ' . $e->getMessage(), [
⋮----
return Action::danger('Error converting HTML to Markdown: ' . $e->getMessage());
⋮----
/**
     * Import from Markdown content
     */
protected function importFromMarkdown($markdownContent, $url = null)
⋮----
return $this->analyzeJobContent($markdownContent, $url);
⋮----
/**
     * Convert HTML content to Markdown using html-to-markdown API.
     *
     * @param string $html
     * @param string|null $url
     * @param string|null $apiKey
     * @return string|null
     */
protected function convertHtmlToMarkdown(string $html, ?string $url = null, ?string $apiKey = null): ?string
⋮----
// If no API key is provided, try to get it from config
⋮----
// Add domain if available
⋮----
$response = Http::withHeaders([
⋮----
])->post('https://api.html-to-markdown.com/v1/convert', $payload);
⋮----
if ($response->successful()) {
return $response->json('markdown');
⋮----
Log::error('HTML to Markdown API error', [
'status' => $response->status(),
'response' => $response->body()
⋮----
Log::error('Exception in HTML to Markdown conversion', [
'message' => $e->getMessage(),
⋮----
/**
     * Analyze job post content using OpenAI
     */
protected function analyzeJobContent($content, $url = null)
⋮----
// Initialize JobPostAIService
⋮----
// Analyze the job post content
$jobData = $aiService->analyzeJobPost($content);
⋮----
// Add URL and user ID
⋮----
$jobData['user_id'] = Auth::id();
⋮----
// Create the job post
return $this->createJobPost($jobData);
⋮----
Log::error('Error analyzing job post: ' . $e->getMessage(), [
⋮----
return Action::danger('Error analyzing job post: ' . $e->getMessage());
⋮----
/**
     * Create job post from parsed data
     */
protected function createJobPost($data)
⋮----
// Format repeater fields for Nova
$data = $this->formatRepeaterFields($data);
⋮----
$jobPost = JobPost::create($data);
⋮----
return Action::message('Successfully imported job post: ' . $jobPost->job_title);
⋮----
/**
     * Format repeater fields for Nova
     * 
     * @param array $data
     * @return array
     */
protected function formatRepeaterFields(array $data): array
⋮----
// Format required skills
⋮----
// Format preferred skills
⋮----
// Format required experience
⋮----
// Format required education
⋮----
/**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
Select::make('Import Type', 'import_type')
->options([
⋮----
->default('google_jobs')
->rules('required')
->help('Select the type of job listing you are importing'),
⋮----
Text::make('URL', 'url')
->help('Optional: Job listing URL for reference')
->nullable(),
⋮----
Textarea::make('HTML Content', 'html_content')
->help('Paste the HTML content copied from browser devtools')
->rows(10)
⋮----
Text::make('API Key', 'api_key')
->help('HTML-to-Markdown API Key (required for HTML conversion)')
->default(config('services.html_to_markdown.api_key', ''))
⋮----
Textarea::make('Markdown Content', 'markdown_content')
->help('Or paste the markdown content directly')
⋮----
/**
     * Get the displayable name of the action.
     *
     * @return string
     */
public function name()
````

## File: app/Nova/Actions/RegenerateWithFeedback.php
````php
namespace App\Nova\Actions;
⋮----
use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class RegenerateWithFeedback extends Action
⋮----
/**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
public function handle(ActionFields $fields, Collection $models)
⋮----
$regenerated = $generationService->regenerateWithFeedback($document, [
⋮----
return Action::message("Document regenerated successfully!");
⋮----
return Action::danger("Failed to regenerate document: {$e->getMessage()}");
⋮----
/**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
Textarea::make('Feedback', 'feedback')
->rules('required')
->help('Provide feedback on what should be improved in this document.'),
⋮----
/**
     * Get the displayable name of the action.
     *
     * @return string
     */
public function name()
````

## File: app/Nova/Dashboards/Main.php
````php
namespace App\Nova\Dashboards;
⋮----
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;
⋮----
class Main extends Dashboard
⋮----
/**
     * Get the cards for the dashboard.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
public function cards(): array
⋮----
//            new Help,
````

## File: app/Nova/Repeaters/EducationItem.php
````php
namespace App\Nova\Repeaters;
⋮----
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
⋮----
class EducationItem extends Repeatable
⋮----
/**
     * Get the displayable singular label for the repeatable.
     */
public static function singularLabel(): string
⋮----
/**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
public function fields(NovaRequest $request): array
⋮----
Select::make('Degree Level', 'level')
->options([
⋮----
->rules('required')
->default('bachelor'),
⋮----
Text::make('Field of Study', 'field')
->help('e.g., Computer Science, Business, etc.')
->rules('required'),
⋮----
Boolean::make('Is Required', 'is_required')
->help('Must have this exact degree or is it flexible?')
->default(true),
⋮----
Number::make('Minimum GPA', 'min_gpa')
->min(0)
->max(4.0)
->step(0.1)
->nullable()
->help('Leave blank if not applicable'),
⋮----
Textarea::make('Additional Requirements', 'description')
->rows(2)
⋮----
->help('Any specific requirements or notes about this education'),
````

## File: app/Nova/Repeaters/ExperienceItem.php
````php
namespace App\Nova\Repeaters;
⋮----
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
⋮----
class ExperienceItem extends Repeatable
⋮----
/**
     * Get the displayable singular label for the repeatable.
     */
public static function singularLabel(): string
⋮----
/**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
public function fields(NovaRequest $request): array
⋮----
Text::make('Title', 'title')
->rules('required'),
⋮----
Number::make('Years Required', 'years')
->min(0)
->step(0.5)
->default(1),
⋮----
Select::make('Level', 'level')
->options([
⋮----
->default('intermediate'),
⋮----
Textarea::make('Description', 'description')
->rows(2)
->nullable(),
````

## File: app/Nova/Repeaters/SkillItem.php
````php
namespace App\Nova\Repeaters;
⋮----
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
⋮----
class SkillItem extends Repeatable
⋮----
/**
     * Get the displayable singular label for the repeatable.
     */
public static function singularLabel(): string
⋮----
/**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
public function fields(NovaRequest $request): array
⋮----
Text::make('Name', 'name')
->rules('required'),
⋮----
Select::make('Type', 'type')
->options([
⋮----
->default('technical'),
⋮----
Number::make('Proficiency Level', 'level')
->min(1)
->max(5)
->default(3)
->help('1 = Beginner, 5 = Expert'),
````

## File: app/Nova/OpenAIPrompt.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class OpenAIPrompt extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\OpenAIPrompt>
     */
public static $model = \App\Models\OpenAIPrompt::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'name';
⋮----
/**
     * Get the displayable label of the resource.
     *
     * @return string
     */
public static function label()
⋮----
/**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
public static function singularLabel()
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
Text::make('Name')
->sortable()
->rules('required', 'max:255'),
⋮----
Boolean::make('Active')
->default(true),
⋮----
// Form field
Select::make('Type')
->options([
⋮----
->displayUsingLabels()
->rules('required')
->onlyOnForms(),
⋮----
// Display field
Badge::make('Type')
->map([
⋮----
->addTypes([
⋮----
->icons([
⋮----
->exceptOnForms(),
⋮----
Textarea::make('Prompt Template')
->alwaysShow()
->rules('required'),
⋮----
Code::make('Parameters')
->language('json')
->nullable()
->hideFromIndex(),
⋮----
Text::make('Model')
->default('gpt-4o')
⋮----
// Display field - different colors based on model pricing tiers
Badge::make('Model')
⋮----
'gpt-4o' => 'generic', // high intelligence
'gpt-4o-mini' => 'generic', // high intelligence
'chatgpt-4o-latest' => 'success', // high intelligence
'o1' => 'warning', // high intelligence
'o3-mini' => 'warning', // high intelligence
'o1-pro' => 'danger', // high intelligence
'gpt-4.5-preview' => 'danger', // high intelligence
⋮----
->withIcons()
⋮----
//     'success' => 'check-circle',
//     'info' => 'information-circle',
//     'danger' => 'exclamation-circle',
//     'warning' => 'exclamation-circle',
⋮----
Number::make('Temperature')
->step(0.1)
->min(0)
->max(1.0)
->default(0.7)
⋮----
Badge::make('Temperature')
->resolveUsing(function ($value) {
// Categorize temperature into ranges
⋮----
->label(function ($value) {
// Format the label to show the temperature
⋮----
Number::make('Max Tokens')
->min(100)
->default(2000)
⋮----
Badge::make('Max Tokens')
⋮----
// Categorize token usage into ranges
⋮----
// Format the label to show the number of tokens
````

## File: app/Nova/Resource.php
````php
namespace App\Nova;
⋮----
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Scout\Builder as ScoutBuilder;
⋮----
abstract class Resource extends NovaResource
⋮----
/**
     * Build an "index" query for the given resource.
     */
public static function indexQuery(NovaRequest $request, Builder $query): Builder
⋮----
/**
     * Build a Scout search query for the given resource.
     */
public static function scoutQuery(NovaRequest $request, ScoutBuilder $query): ScoutBuilder
⋮----
/**
     * Build a "detail" query for the given resource.
     */
public static function detailQuery(NovaRequest $request, Builder $query): Builder
⋮----
return parent::detailQuery($request, $query);
⋮----
/**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     */
public static function relatableQuery(NovaRequest $request, Builder $query): Builder
⋮----
return parent::relatableQuery($request, $query);
````

## File: app/Nova/Rule.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class Rule extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Rule>
     */
public static $model = \App\Models\Rule::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'name';
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
Text::make('Name')
->sortable()
->rules('required', 'max:255'),
⋮----
Textarea::make('Description')
->rules('required')
->hideFromIndex(),
⋮----
// Form field
Select::make('Type')
->options([
⋮----
->displayUsingLabels()
->default('both')
⋮----
->onlyOnForms(),
⋮----
// Display field
Badge::make('Type')
->map([
⋮----
->icons([
⋮----
->exceptOnForms(),
⋮----
Text::make('Source')
->nullable(),
⋮----
Number::make('Importance')
->min(1)
->max(10)
->default(5)
->help('Rate importance from 1-10')
⋮----
Badge::make('Importance')
⋮----
Code::make('Validation Logic')
->language('json')
->nullable()
````

## File: app/Nova/ThreadSession.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class ThreadSession extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ThreadSession>
     */
public static $model = \App\Models\ThreadSession::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'id';
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
BelongsTo::make('User'),
⋮----
BelongsTo::make('Job Post', 'jobPost', JobPost::class),
⋮----
Badge::make('Type')
->map([
⋮----
Badge::make('Status')
⋮----
Text::make('Assistant ID', 'assistant_id')
->hideFromIndex(),
⋮----
Text::make('Thread ID', 'thread_id')
⋮----
DateTime::make('Completed At')
⋮----
Textarea::make('Error')
->hideFromIndex()
->onlyOnDetail(),
⋮----
Code::make('Content')
->language('markdown')
⋮----
Code::make('Metrics')
->language('json')
````

## File: app/Providers/HorizonServiceProvider.php
````php
namespace App\Providers;
⋮----
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;
⋮----
class HorizonServiceProvider extends HorizonApplicationServiceProvider
⋮----
/**
     * Bootstrap any application services.
     */
public function boot(): void
⋮----
parent::boot();
⋮----
// Horizon::routeSmsNotificationsTo('15556667777');
// Horizon::routeMailNotificationsTo('example@example.com');
// Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');
⋮----
/**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
protected function gate(): void
⋮----
Gate::define('viewHorizon', function ($user) {
````

## File: app/Providers/TelescopeServiceProvider.php
````php
namespace App\Providers;
⋮----
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;
⋮----
class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
⋮----
/**
     * Register any application services.
     */
public function register(): void
⋮----
// Telescope::night();
⋮----
$this->hideSensitiveRequestDetails();
⋮----
$isLocal = $this->app->environment('local');
⋮----
Telescope::filter(function (IncomingEntry $entry) use ($isLocal) {
⋮----
$entry->isReportableException() ||
$entry->isFailedRequest() ||
$entry->isFailedJob() ||
$entry->isScheduledTask() ||
$entry->hasMonitoredTag();
⋮----
/**
     * Prevent sensitive request details from being logged by Telescope.
     */
protected function hideSensitiveRequestDetails(): void
⋮----
if ($this->app->environment('local')) {
⋮----
Telescope::hideRequestParameters(['_token']);
⋮----
Telescope::hideRequestHeaders([
⋮----
/**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
protected function gate(): void
⋮----
Gate::define('viewTelescope', function ($user) {
````

## File: app/Services/AssistantsService.php
````php
namespace App\Services;
⋮----
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;
⋮----
class AssistantsService
⋮----
/**
     * The available assistant types
     */
⋮----
/**
     * Cache keys for storing assistant IDs
     */
⋮----
/**
     * Get or create an assistant by type
     *
     * @param string $type
     * @return string The assistant ID
     */
public function getOrCreateAssistant(string $type): string
⋮----
$cacheKey = $this->getCacheKeyForType($type);
⋮----
return Cache::remember($cacheKey, now()->addDays(30), function () use ($type) {
⋮----
$assistantId = $this->createAssistant($type);
Log::info("Created new OpenAI assistant", ['type' => $type, 'id' => $assistantId]);
⋮----
Log::error("Failed to create OpenAI assistant", [
⋮----
'error' => $e->getMessage()
⋮----
/**
     * Create a new assistant with the appropriate configuration
     *
     * @param string $type
     * @return string The assistant ID
     */
private function createAssistant(string $type): string
⋮----
$config = $this->getAssistantConfig($type);
⋮----
$response = OpenAI::assistants()->create([
⋮----
/**
     * Get the configuration for a specific assistant type
     *
     * @param string $type
     * @return array
     */
private function getAssistantConfig(string $type): array
⋮----
'instructions' => $this->getResumeInstructions(),
⋮----
'instructions' => $this->getCoverLetterInstructions(),
⋮----
'instructions' => $this->getValidatorInstructions(),
⋮----
/**
     * Get cache key for the assistant type
     */
private function getCacheKeyForType(string $type): string
⋮----
/**
     * Get detailed instructions for the resume assistant
     */
private function getResumeInstructions(): string
⋮----
/**
     * Get detailed instructions for the cover letter assistant
     */
private function getCoverLetterInstructions(): string
⋮----
/**
     * Get detailed instructions for the validator assistant
     */
private function getValidatorInstructions(): string
⋮----
/**
     * Create a new thread for an assistant interaction
     *
     * @return string The thread ID
     */
public function createThread(): string
⋮----
$response = OpenAI::threads()->create([]);
⋮----
/**
     * Add a message to a thread
     *
     * @param string $threadId
     * @param string $content
     * @param array $files Optional file IDs to attach
     * @return string The message ID
     */
public function addMessage(string $threadId, string $content, array $files = []): string
⋮----
$response = OpenAI::threads()->messages()->create($threadId, $params);
⋮----
/**
     * Run an assistant on a thread and wait for completion
     *
     * @param string $threadId
     * @param string $assistantId
     * @param array $instructions Optional additional instructions
     * @return array The assistant's response messages
     */
public function runAssistant(string $threadId, string $assistantId, array $instructions = []): array
⋮----
// Create the run
$run = OpenAI::threads()->runs()->create($threadId, [
⋮----
// Poll for completion (in production, you'd use a background job)
$maxAttempts = 60; // 10 minutes max (10s intervals)
⋮----
sleep(10); // Wait 10 seconds between checks
$run = OpenAI::threads()->runs()->retrieve($threadId, $run->id);
⋮----
// Handle required actions if needed (function calling, etc.)
⋮----
// Process required actions (would need implementation based on your functions)
// This is a simplified example
$this->handleRequiredAction($threadId, $run);
⋮----
// Get the messages (newest first)
$messages = OpenAI::threads()->messages()->list($threadId, ['limit' => 10]);
⋮----
// Filter to only get assistant messages from this run
⋮----
/**
     * Handle required actions for function calling
     *
     * @param string $threadId
     * @param object $run
     * @return void
     */
private function handleRequiredAction(string $threadId, object $run): void
⋮----
// Example implementation - would need to be adapted to your specific functions
⋮----
// Call your function handling logic here
$result = $this->callFunction($function, $arguments);
⋮----
// Submit the tool outputs
OpenAI::threads()->runs()->submitToolOutputs($threadId, $run->id, [
⋮----
/**
     * Call a function based on name and arguments
     *
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
private function callFunction(string $function, array $arguments): mixed
⋮----
// Implementation would depend on your specific function needs
// This is just a placeholder
````

## File: app/Services/EmbeddingsService.php
````php
namespace App\Services;
⋮----
use App\Models\JobPost;
use App\Models\User;
use App\Models\Skill;
use App\Models\WorkExperience;
use App\Models\SkillEmbedding;
use App\Models\JobRequirementEmbedding;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Exception;
⋮----
class EmbeddingsService
⋮----
/**
     * Generate embeddings for a text
     *
     * @param string $text
     * @return array
     */
public function generateEmbedding(string $text): array
⋮----
$response = OpenAI::embeddings()->create([
⋮----
Log::error("Failed to generate embedding", [
'error' => $e->getMessage(),
⋮----
/**
     * Generate and store embeddings for a user's skills
     *
     * @param User $user
     * @return array
     */
public function generateUserSkillEmbeddings(User $user): array
⋮----
// Create a rich description of the skill
$skillDescription = $this->createSkillDescription($skill);
⋮----
// Generate embedding
$embedding = $this->generateEmbedding($skillDescription);
⋮----
// Store the embedding
$skillEmbedding = SkillEmbedding::updateOrCreate(
⋮----
Log::error("Failed to generate skill embedding", [
⋮----
'message' => $e->getMessage(),
⋮----
/**
     * Generate and store embeddings for job requirements
     *
     * @param JobPost $jobPost
     * @return array
     */
public function generateJobRequirementEmbeddings(JobPost $jobPost): array
⋮----
// Extract requirements from job post
⋮----
// Handle both array of strings and array of arrays with 'fields'
⋮----
// Add description if available
⋮----
// Create combined text for this requirement type
⋮----
$embedding = $this->generateEmbedding($requirementText);
⋮----
$requirementEmbedding = JobRequirementEmbedding::updateOrCreate(
⋮----
Log::error("Failed to generate job requirement embedding", [
⋮----
// Also generate an embedding for the entire job description
⋮----
$embedding = $this->generateEmbedding($jobPost->job_description);
⋮----
Log::error("Failed to generate job description embedding", [
⋮----
/**
     * Create a rich description of a skill
     *
     * @param Skill $skill
     * @return string
     */
private function createSkillDescription(Skill $skill): string
⋮----
// Add type
⋮----
// Add proficiency
⋮----
// Add years of experience
⋮----
// Find work experiences that might be related to this skill
$relatedExperiences = WorkExperience::where('user_id', $skill->user_id)
->where(function ($query) use ($skill) {
$query->whereJsonContains('skills_used', $skill->name)
->orWhere('description', 'like', "%{$skill->name}%");
⋮----
->get();
⋮----
if ($relatedExperiences->isNotEmpty()) {
⋮----
// Add achievements related to this skill
⋮----
/**
     * Find skill matches for job requirements
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return array
     */
public function findSkillMatches(User $user, JobPost $jobPost): array
⋮----
// Make sure embeddings exist
$this->generateUserSkillEmbeddings($user);
$this->generateJobRequirementEmbeddings($jobPost);
⋮----
// Get job requirement embeddings
$jobRequirementEmbeddings = JobRequirementEmbedding::where('job_post_id', $jobPost->id)->get();
⋮----
// Get user skill embeddings
$userSkillEmbeddings = SkillEmbedding::where('user_id', $user->id)->get();
⋮----
// Calculate cosine similarity
$similarity = $this->cosineSimilarity($requirementVector, $skillVector);
⋮----
// Get the skill
$skill = Skill::find($skillEmbedding->skill_id);
⋮----
// Sort by similarity (highest first)
⋮----
// Keep only top matches (threshold: 0.75)
⋮----
/**
     * Calculate cosine similarity between two vectors
     *
     * @param array $a
     * @param array $b
     * @return float
     */
private function cosineSimilarity(array $a, array $b): float
⋮----
/**
     * Generate job-specific recommendations based on embeddings analysis
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return array
     */
public function generateRecommendations(User $user, JobPost $jobPost): array
⋮----
// Find skill matches
$matches = $this->findSkillMatches($user, $jobPost);
⋮----
// Prepare data for OpenAI
⋮----
$response = OpenAI::chat()->create([
⋮----
// Parse JSON response
⋮----
// If the response isn't valid JSON, try to extract it
⋮----
Log::error("Failed to generate recommendations", [
⋮----
// Return basic recommendations if generation fails
````

## File: app/Services/JobPostAIService.php
````php
namespace App\Services;
⋮----
use App\Models\OpenAIPrompt;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
⋮----
class JobPostAIService
⋮----
/**
     * The OpenAI API key
     *
     * @var string
     */
protected $apiKey;
⋮----
/**
     * The OpenAI API URL
     *
     * @var string
     */
protected $apiUrl = 'https://api.openai.com/v1/chat/completions';
⋮----
/**
     * Constructor
     */
public function __construct()
⋮----
/**
     * Analyze a job post using OpenAI
     *
     * @param string $content The job post content (HTML or markdown)
     * @return array The structured job data
     * @throws Exception If there is an error
     */
public function analyzeJobPost(string $content): array
⋮----
// Find the Job Post Analysis prompt
$prompt = OpenAIPrompt::where('name', 'Job Post Analysis')
->where('type', 'analysis')
->where('active', true)
->first();
⋮----
// Generate completion and parse result
$result = $this->generateCompletion($prompt, ['job_content' => $content]);
⋮----
Log::error('Invalid JSON response from OpenAI', [
⋮----
/**
     * Generate a completion using OpenAI API
     *
     * @param OpenAIPrompt $prompt The prompt to use
     * @param array $parameters The parameters to replace in the prompt
     * @return string The generated text
     * @throws Exception If there is an error
     */
protected function generateCompletion(OpenAIPrompt $prompt, array $parameters): string
⋮----
set_time_limit(0); // Disable time limit for long requests
⋮----
// Replace placeholders in the prompt template
$promptText = $this->replacePlaceholders($prompt->prompt_template, $parameters);
⋮----
// Prepare the request payload
⋮----
// Log the request (omit the API key for security)
Log::info('OpenAI Request', [
⋮----
// Make the API request
$response = Http::withHeaders([
⋮----
])->timeout(120)->post($this->apiUrl, $payload);
⋮----
// Check if the request was successful
if (!$response->successful()) {
$error = $response->json();
Log::error('OpenAI API Error', [
'status' => $response->status(),
⋮----
// Extract and return the response text
$responseData = $response->json();
⋮----
// Remove any JSON code block markers if present (```json and ```)
⋮----
/**
     * Replace placeholders in the prompt template
     *
     * @param string $template
     * @param array $parameters
     * @return string
     */
protected function replacePlaceholders(string $template, array $parameters): string
````

## File: app/Services/PDFService.php
````php
namespace App\Services;
⋮----
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Resume;
use App\Models\CoverLetter;
use Illuminate\Support\Facades\Storage;
⋮----
class PDFService
⋮----
/**
     * Generate a PDF file for a resume
     * 
     * @param Resume $resume
     * @return string The file path
     */
public function generateResumePDF(Resume $resume)
⋮----
$pdf = PDF::loadView('pdfs.resume', [
⋮----
'content' => $this->formatContentForPDF($resume->content)
⋮----
// Save to storage
Storage::put('public/' . $path, $pdf->output());
⋮----
// Update the resume
$resume->update([
⋮----
/**
     * Generate a PDF file for a cover letter
     * 
     * @param CoverLetter $coverLetter
     * @return string The file path
     */
public function generateCoverLetterPDF(CoverLetter $coverLetter)
⋮----
$pdf = PDF::loadView('pdfs.cover_letter', [
⋮----
'content' => $this->formatContentForPDF($coverLetter->content)
⋮----
// Update the cover letter
$coverLetter->update([
⋮----
/**
     * Format content for PDF rendering
     * 
     * @param string $content
     * @return string
     */
protected function formatContentForPDF(string $content)
⋮----
// Convert markdown to HTML if needed
if ($this->isMarkdown($content)) {
return $this->markdownToHtml($content);
⋮----
// Format line breaks
⋮----
/**
     * Check if content appears to be in markdown format
     * 
     * @param string $content
     * @return bool
     */
protected function isMarkdown(string $content)
⋮----
'# ', '## ', '### ', '#### ', '##### ', '###### ', // Headers
'- ', '* ', '+ ', '1. ', '2. ', // Lists
'```', '~~~', // Code blocks
'[', '![', // Links and images
'|', '---', // Tables and horizontal rules
'_', '**', '~~', '`' // Emphasis and inline code
⋮----
/**
     * Convert markdown to HTML
     * 
     * @param string $markdown
     * @return string
     */
protected function markdownToHtml(string $markdown)
⋮----
// Use Laravel's built-in Str::markdown helper
return \Illuminate\Support\Str::markdown($markdown);
````

## File: app/Services/ThreadManagementService.php
````php
namespace App\Services;
⋮----
use App\Models\User;
use App\Models\JobPost;
use App\Models\ThreadSession;
use App\Services\AssistantsService;
use Illuminate\Support\Facades\Log;
use Exception;
⋮----
class ThreadManagementService
⋮----
protected $assistantsService;
⋮----
public function __construct(AssistantsService $assistantsService)
⋮----
/**
     * Start a new generation session for a resume
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return ThreadSession
     */
public function startResumeSession(User $user, JobPost $jobPost): ThreadSession
⋮----
// Get or create the resume assistant
$assistantId = $this->assistantsService->getOrCreateAssistant(AssistantsService::TYPE_RESUME);
⋮----
// Create a new thread
$threadId = $this->assistantsService->createThread();
⋮----
// Create a ThreadSession record
$session = ThreadSession::create([
⋮----
// Prepare initial message with job details and user profile
$initialMessage = $this->prepareResumeInitialMessage($user, $jobPost);
⋮----
// Add the message to the thread
$this->assistantsService->addMessage($threadId, $initialMessage);
⋮----
/**
     * Start a new generation session for a cover letter
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return ThreadSession
     */
public function startCoverLetterSession(User $user, JobPost $jobPost): ThreadSession
⋮----
// Get or create the cover letter assistant
$assistantId = $this->assistantsService->getOrCreateAssistant(AssistantsService::TYPE_COVER_LETTER);
⋮----
$initialMessage = $this->prepareCoverLetterInitialMessage($user, $jobPost);
⋮----
/**
     * Run a session and get the generated content
     *
     * @param ThreadSession $session
     * @return string The generated content
     */
public function generateContent(ThreadSession $session): string
⋮----
// Update session status
$session->update(['status' => 'processing']);
⋮----
// Run the assistant
$messages = $this->assistantsService->runAssistant(
⋮----
// Get the content from the first (most recent) message
⋮----
// Update session status and content
$session->update([
⋮----
// Update session status to failed
⋮----
'error' => $e->getMessage(),
⋮----
Log::error("Generation failed", [
⋮----
'trace' => $e->getTraceAsString(),
⋮----
/**
     * Prepare the initial message for resume generation
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return string
     */
private function prepareResumeInitialMessage(User $user, JobPost $jobPost): string
⋮----
$userProfile = $this->formatUserProfile($user);
$jobDetails = $this->formatJobDetails($jobPost);
⋮----
/**
     * Prepare the initial message for cover letter generation
     *
     * @param User $user
     * @param JobPost $jobPost
     * @return string
     */
private function prepareCoverLetterInitialMessage(User $user, JobPost $jobPost): string
⋮----
/**
     * Format the user profile for messages
     *
     * @param User $user
     * @return string
     */
private function formatUserProfile(User $user): string
⋮----
// Add work experience
⋮----
foreach ($user->workExperiences()->orderBy('start_date', 'desc')->get() as $experience) {
$endDate = $experience->current_job ? "Present" : $experience->end_date->format('M Y');
$profile .= "- **{$experience->position}** at {$experience->company_name} ({$experience->start_date->format('M Y')} - {$endDate})\n";
⋮----
// Add education
⋮----
foreach ($user->education()->orderBy('start_date', 'desc')->get() as $education) {
$endDate = $education->current ? "Present" : $education->end_date->format('M Y');
⋮----
$profile .= "- **{$education->degree}{$fieldOfStudy}** from {$education->institution} ({$education->start_date->format('M Y')} - {$endDate})\n";
⋮----
// Add skills
⋮----
foreach ($user->skills()->orderBy('proficiency', 'desc')->get() as $skill) {
⋮----
// Add projects
⋮----
/**
     * Format the job details for messages
     *
     * @param JobPost $jobPost
     * @return string
     */
private function formatJobDetails(JobPost $jobPost): string
⋮----
// Add required skills
⋮----
// Format for complex skill objects
⋮----
// Simple string format
⋮----
// Add preferred skills
⋮----
// Add required experience
⋮----
// Format for complex experience objects
⋮----
// Simple format
⋮----
// Add required education
⋮----
// Format for complex education objects
⋮----
/**
     * Validate a generated document
     *
     * @param string $content The document content
     * @param string $type The document type (resume or cover_letter)
     * @param JobPost $jobPost The job post
     * @return array Validation results with scores and feedback
     */
public function validateDocument(string $content, string $type, JobPost $jobPost): array
⋮----
// Get the validator assistant
$assistantId = $this->assistantsService->getOrCreateAssistant(AssistantsService::TYPE_VALIDATOR);
⋮----
// Prepare the validation message
$message = $this->prepareValidationMessage($content, $type, $jobPost);
⋮----
$this->assistantsService->addMessage($threadId, $message);
⋮----
$messages = $this->assistantsService->runAssistant($threadId, $assistantId);
⋮----
// Parse the validation results
⋮----
// Parse the validation text into a structured format
// This is a simplified parsing - in production you might want more robust parsing
⋮----
// Try to extract overall score
⋮----
// Try to extract criterion
⋮----
// Add to current criterion feedback
⋮----
// Try to extract suggestions
⋮----
// Try to extract summary
⋮----
/**
     * Prepare a message for document validation
     *
     * @param string $content The document content
     * @param string $type The document type
     * @param JobPost $jobPost The job post
     * @return string
     */
private function prepareValidationMessage(string $content, string $type, JobPost $jobPost): string
````

## File: database/factories/CoverLetterFactory.php
````php
namespace Database\Factories;
⋮----
use App\Models\CoverLetter;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
⋮----
class CoverLetterFactory extends Factory
⋮----
protected $model = CoverLetter::class;
⋮----
public function definition(): array
⋮----
'user_id' => User::factory(),
'job_post_id' => JobPost::factory(),
'content' => fake()->paragraphs(3, true),
⋮----
'word_count' => fake()->numberBetween(400, 750),
````

## File: database/factories/JobPostFactory.php
````php
namespace Database\Factories;
⋮----
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
⋮----
class JobPostFactory extends Factory
⋮----
/**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
protected $model = JobPost::class;
⋮----
/**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
⋮----
'user_id' => User::factory(),
'company_name' => fake()->company(),
'job_title' => fake()->jobTitle(),
'job_description' => fake()->paragraphs(3, true),
'job_post_url' => fake()->url(),
'job_post_date' => fake()->dateTimeBetween('-30 days', 'now'),
'job_location_type' => fake()->randomElement(['remote', 'in-office', 'hybrid']),
'required_skills' => json_encode([$this->randomSkill(), $this->randomSkill(), $this->randomSkill()]),
'preferred_skills' => json_encode([$this->randomSkill(), $this->randomSkill()]),
'required_experience' => json_encode(['years' => fake()->numberBetween(1, 10), 'description' => fake()->sentence()]),
'required_education' => json_encode(['level' => fake()->randomElement(['Bachelors', 'Masters', 'PhD']), 'field' => fake()->word()]),
⋮----
'things_i_like' => fake()->paragraph(),
'things_i_dislike' => fake()->paragraph(),
'things_i_like_about_company' => fake()->paragraph(),
'things_i_dislike_about_company' => fake()->paragraph(),
'open_to_travel' => fake()->boolean(),
'salary_range_min' => fake()->numberBetween(50000, 80000),
'salary_range_max' => fake()->numberBetween(90000, 150000),
'min_acceptable_salary' => fake()->numberBetween(45000, 70000),
'position_level' => fake()->randomElement(['entry-level', 'mid-level', 'senior', 'lead', 'manager', 'director', 'executive']),
'job_type' => fake()->randomElement(['full-time', 'part-time', 'contract', 'internship', 'freelance']),
'ideal_start_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
'position_preference' => fake()->numberBetween(1, 5),
'first_time_applying' => fake()->boolean(80),
⋮----
/**
     * Generate a random skill name
     */
private function randomSkill(): string
⋮----
return fake()->randomElement([
````

## File: database/factories/ResumeFactory.php
````php
namespace Database\Factories;
⋮----
use App\Models\Resume;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
⋮----
class ResumeFactory extends Factory
⋮----
protected $model = Resume::class;
⋮----
public function definition(): array
⋮----
'user_id' => User::factory(),
'job_post_id' => JobPost::factory(),
'content' => fake()->paragraphs(5, true),
⋮----
'word_count' => fake()->numberBetween(400, 900),
````

## File: database/migrations/0001_01_01_000001_create_cache_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('cache', function (Blueprint $table) {
$table->string('key')->primary();
$table->mediumText('value');
$table->integer('expiration');
⋮----
Schema::create('cache_locks', function (Blueprint $table) {
⋮----
$table->string('owner');
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('cache');
Schema::dropIfExists('cache_locks');
````

## File: database/migrations/0001_01_01_000002_create_jobs_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('jobs', function (Blueprint $table) {
$table->id();
$table->string('queue')->index();
$table->longText('payload');
$table->unsignedTinyInteger('attempts');
$table->unsignedInteger('reserved_at')->nullable();
$table->unsignedInteger('available_at');
$table->unsignedInteger('created_at');
⋮----
Schema::create('job_batches', function (Blueprint $table) {
$table->string('id')->primary();
$table->string('name');
$table->integer('total_jobs');
$table->integer('pending_jobs');
$table->integer('failed_jobs');
$table->longText('failed_job_ids');
$table->mediumText('options')->nullable();
$table->integer('cancelled_at')->nullable();
$table->integer('created_at');
$table->integer('finished_at')->nullable();
⋮----
Schema::create('failed_jobs', function (Blueprint $table) {
⋮----
$table->string('uuid')->unique();
$table->text('connection');
$table->text('queue');
⋮----
$table->longText('exception');
$table->timestamp('failed_at')->useCurrent();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('jobs');
Schema::dropIfExists('job_batches');
Schema::dropIfExists('failed_jobs');
````

## File: database/migrations/2025_04_05_174213_create_telescope_entries_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Get the migration connection name.
     */
public function getConnection(): ?string
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
$schema = Schema::connection($this->getConnection());
⋮----
$schema->create('telescope_entries', function (Blueprint $table) {
$table->bigIncrements('sequence');
$table->uuid('uuid');
$table->uuid('batch_id');
$table->string('family_hash')->nullable();
$table->boolean('should_display_on_index')->default(true);
$table->string('type', 20);
$table->longText('content');
$table->dateTime('created_at')->nullable();
⋮----
$table->unique('uuid');
$table->index('batch_id');
$table->index('family_hash');
$table->index('created_at');
$table->index(['type', 'should_display_on_index']);
⋮----
$schema->create('telescope_entries_tags', function (Blueprint $table) {
$table->uuid('entry_uuid');
$table->string('tag');
⋮----
$table->primary(['entry_uuid', 'tag']);
$table->index('tag');
⋮----
$table->foreign('entry_uuid')
->references('uuid')
->on('telescope_entries')
->onDelete('cascade');
⋮----
$schema->create('telescope_monitoring', function (Blueprint $table) {
$table->string('tag')->primary();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
$schema->dropIfExists('telescope_entries_tags');
$schema->dropIfExists('telescope_entries');
$schema->dropIfExists('telescope_monitoring');
````

## File: database/migrations/2025_04_05_175254_add_two_factor_columns_to_users_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::table('users', function (Blueprint $table) {
$table->text('two_factor_secret')
->after('password')
->nullable();
⋮----
$table->text('two_factor_recovery_codes')
->after('two_factor_secret')
⋮----
$table->timestamp('two_factor_confirmed_at')
->after('two_factor_recovery_codes')
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
$table->dropColumn([
````

## File: database/migrations/2025_04_05_185149_create_job_posts_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('job_posts', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->string('company_name');
$table->string('job_title');
$table->text('job_description');
$table->string('job_post_url')->nullable();
$table->date('job_post_date')->nullable();
$table->enum('job_location_type', ['remote', 'in-office', 'hybrid'])->default('remote');
$table->json('required_skills')->nullable();
$table->json('preferred_skills')->nullable();
$table->json('required_experience')->nullable();
$table->json('required_education')->nullable();
$table->integer('resume_min_words')->default(450);
$table->integer('resume_max_words')->default(850);
$table->integer('cover_letter_min_words')->default(450);
$table->integer('cover_letter_max_words')->default(750);
$table->integer('resume_min_pages')->default(1);
$table->integer('resume_max_pages')->default(2);
$table->integer('cover_letter_min_pages')->default(1);
$table->integer('cover_letter_max_pages')->default(1);
$table->text('things_i_like')->nullable();
$table->text('things_i_dislike')->nullable();
$table->text('things_i_like_about_company')->nullable();
$table->text('things_i_dislike_about_company')->nullable();
$table->boolean('open_to_travel')->default(true);
$table->decimal('salary_range_min', 10, 2)->nullable();
$table->decimal('salary_range_max', 10, 2)->nullable();
$table->decimal('min_acceptable_salary', 10, 2)->nullable();
$table->enum('position_level', ['entry-level', 'mid-level', 'senior', 'lead', 'manager', 'director', 'executive'])->default('mid-level');
$table->enum('job_type', ['full-time', 'part-time', 'contract', 'internship', 'freelance'])->default('full-time');
$table->date('ideal_start_date')->nullable();
$table->integer('position_preference')->default(1); // 1 = top choice, 2 = second choice, etc.
$table->boolean('first_time_applying')->default(true);
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('job_posts');
````

## File: database/migrations/2025_04_05_185524_create_work_experiences_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('work_experiences', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->string('company_name');
$table->string('position');
$table->date('start_date');
$table->date('end_date')->nullable();
$table->boolean('current_job')->default(false);
$table->text('description');
$table->json('skills_used')->nullable();
$table->json('achievements')->nullable();
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('work_experiences');
````

## File: database/migrations/2025_04_05_185606_create_education_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('education', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->string('institution');
$table->string('degree');
$table->string('field_of_study')->nullable();
$table->date('start_date');
$table->date('end_date')->nullable();
$table->boolean('current')->default(false);
$table->decimal('gpa', 3, 2)->nullable();
$table->text('achievements')->nullable();
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('education');
````

## File: database/migrations/2025_04_05_185713_create_skills_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('skills', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->string('name');
$table->enum('type', ['technical', 'soft', 'language', 'other'])->default('technical');
$table->integer('proficiency')->default(0); // 1-10 scale
$table->integer('years_experience')->default(0);
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('skills');
````

## File: database/migrations/2025_04_05_190541_create_projects_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('projects', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->string('name');
$table->text('description');
$table->date('start_date')->nullable();
$table->date('end_date')->nullable();
$table->string('url')->nullable();
$table->json('technologies_used')->nullable();
$table->json('achievements')->nullable();
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('projects');
````

## File: database/migrations/2025_04_05_190552_create_resumes_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('resumes', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->foreignId('job_post_id')->constrained();
$table->text('content');
$table->string('file_path')->nullable();
$table->integer('word_count')->nullable();
$table->json('skills_included')->nullable();
$table->json('experiences_included')->nullable();
$table->json('education_included')->nullable();
$table->json('projects_included')->nullable();
$table->json('rule_compliance')->nullable(); // Track which rules were followed
$table->json('generation_metadata')->nullable(); // Store OpenAI generation details
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('resumes');
````

## File: database/migrations/2025_04_05_190603_create_cover_letters_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('cover_letters', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->foreignId('job_post_id')->constrained();
$table->text('content');
$table->string('file_path')->nullable();
$table->integer('word_count')->nullable();
$table->json('rule_compliance')->nullable(); // Track which rules were followed
$table->json('generation_metadata')->nullable(); // Store OpenAI generation details
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('cover_letters');
````

## File: database/migrations/2025_04_05_190617_create_rules_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('rules', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->text('description');
$table->enum('type', ['resume', 'cover_letter', 'both'])->default('both');
$table->string('source')->nullable(); // Where the rule came from
$table->integer('importance')->default(5); // 1-10 scale
$table->json('validation_logic')->nullable(); // Store logic to validate rule
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('rules');
````

## File: database/migrations/2025_04_05_190623_create_openai_prompts_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('openai_prompts', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->enum('type', ['resume', 'cover_letter', 'analysis', 'rule_check']);
$table->text('prompt_template');
$table->json('parameters')->nullable();
$table->string('model')->default('gpt-4o');
$table->integer('max_tokens')->default(2000);
$table->decimal('temperature', 2, 1)->default(0.7);
$table->boolean('active')->default(true);
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('openai_prompts');
````

## File: database/migrations/2025_04_06_040907_create_thread_sessions_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('thread_sessions', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->foreignId('job_post_id')->constrained();
$table->string('assistant_id');
$table->string('thread_id');
$table->enum('type', ['resume', 'cover_letter', 'validation']);
$table->enum('status', ['created', 'processing', 'completed', 'failed']);
$table->longText('content')->nullable();
$table->text('error')->nullable();
$table->timestamp('completed_at')->nullable();
$table->json('metrics')->nullable();
$table->timestamps();
$table->softDeletes();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('thread_sessions');
````

## File: database/migrations/2025_04_06_041204_add_thread_session_id_to_resumes_and_cvs.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::table('resumes', function (Blueprint $table) {
$table->foreignId('thread_session_id')->nullable()->after('job_post_id')->constrained();
⋮----
Schema::table('cover_letters', function (Blueprint $table) {
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
$table->dropForeign(['thread_session_id']);
$table->dropColumn('thread_session_id');
````

## File: database/migrations/2025_04_06_044443_create_skill_embeddings_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('skill_embeddings', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained();
$table->foreignId('skill_id')->constrained();
$table->json('embedding');
$table->text('skill_description');
$table->timestamps();
⋮----
$table->unique(['skill_id']);
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('skill_embeddings');
````

## File: database/migrations/2025_04_06_044453_create_job_requirement_embeddings_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('job_requirement_embeddings', function (Blueprint $table) {
$table->id();
$table->foreignId('job_post_id')->constrained();
$table->enum('requirement_type', ['skills', 'experience', 'education', 'full_description']);
$table->json('embedding');
$table->text('requirement_text');
$table->timestamps();
⋮----
$table->unique(['job_post_id', 'requirement_type']);
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('job_requirement_embeddings');
````

## File: database/seeders/RulesSeeder.php
````php
namespace Database\Seeders;
⋮----
use Illuminate\Database\Seeder;
use App\Models\Rule;
⋮----
class RulesSeeder extends Seeder
⋮----
/**
     * Run the database seeds.
     */
public function run(): void
⋮----
// Resume Rules
⋮----
// Cover Letter Rules
⋮----
// General Rules for Both
⋮----
Rule::create($rule);
````

## File: repomix.config.json
````json
{
    "output": {
        "filePath": "repomix-embeddings.md",
        "style": "markdown",
        "parsableStyle": false,
        "fileSummary": false,
        "directoryStructure": true,
        "removeComments": false,
        "removeEmptyLines": false,
        "compress": true,
        "topFilesLength": 0,

        "showLineNumbers": false,
        "copyToClipboard": true,
        "git": {
            "sortByChanges": true,
            "sortByChangesMaxCommits": 100
        }
    },
    "include": [
        "**/app/Models/**",
        "**/app/Nova/**",
        "**/app/Providers/**",
        "**/app/Services/**",
        "**/database/factories/**",
        "**/database/migrations/**",
        "**/database/seeders/**",
        "**/composer.json",
        "**/repomix.config.json"
    ],
    "ignore": {
        "useGitignore": true,
        "useDefaultPatterns": true,
        "customPatterns": [
        ]
    },
    "security": {
        "enableSecurityCheck": false
    },
    "tokenCount": {
        "encoding": "o200k_base"
    }
}
````

## File: app/Models/CoverLetter.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class CoverLetter extends Model
⋮----
protected $fillable = [
⋮----
protected $casts = [
⋮----
public function user()
⋮----
return $this->belongsTo(User::class);
⋮----
public function jobPost()
⋮----
return $this->belongsTo(JobPost::class);
⋮----
/**
     * Get the thread session that generated this cover letter
     */
public function threadSession()
⋮----
return $this->belongsTo(ThreadSession::class);
````

## File: app/Models/Resume.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class Resume extends Model
⋮----
protected $fillable = [
⋮----
protected $casts = [
⋮----
public function user()
⋮----
return $this->belongsTo(User::class);
⋮----
public function jobPost()
⋮----
return $this->belongsTo(JobPost::class);
⋮----
/**
     * Get the thread session that generated this resume
     */
public function threadSession()
⋮----
return $this->belongsTo(ThreadSession::class);
````

## File: app/Models/User.php
````php
namespace App\Models;
⋮----
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
⋮----
class User extends Authenticatable
⋮----
/** @use HasFactory<\Database\Factories\UserFactory> */
⋮----
/**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
protected $fillable = [
⋮----
/**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
protected $hidden = [
⋮----
/**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
protected function casts(): array
⋮----
public function workExperiences()
⋮----
return $this->hasMany(WorkExperience::class);
⋮----
public function education()
⋮----
return $this->hasMany(Education::class);
⋮----
public function skills()
⋮----
return $this->hasMany(Skill::class);
⋮----
public function projects()
⋮----
return $this->hasMany(Project::class);
⋮----
public function jobPosts()
⋮----
return $this->hasMany(JobPost::class);
⋮----
public function resumes()
⋮----
return $this->hasMany(Resume::class);
⋮----
public function coverLetters()
⋮----
return $this->hasMany(CoverLetter::class);
````

## File: app/Nova/CoverLetter.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class CoverLetter extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\CoverLetter>
     */
public static $model = \App\Models\CoverLetter::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'id';
⋮----
/**
     * Get the displayable label of the resource.
     *
     * @return string
     */
public static function label()
⋮----
/**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
public static function singularLabel()
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
BelongsTo::make('User')
->default($request->user()->id)
->withoutTrashed()
->searchable(),
⋮----
BelongsTo::make('Job Post', 'jobPost', JobPost::class),
⋮----
Textarea::make('Content')
->alwaysShow()
->hideFromIndex(),
⋮----
Text::make('File Path')
->nullable(),
⋮----
Number::make('Word Count')
⋮----
Code::make('Rule Compliance')
->language('json')
->nullable()
⋮----
Code::make('Generation Metadata')
````

## File: app/Nova/Education.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class Education extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Education>
     */
public static $model = \App\Models\Education::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'institution';
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
BelongsTo::make('User')
->default($request->user()->id)
->withoutTrashed()
->searchable(),
⋮----
Text::make('Institution')
->sortable()
->rules('required', 'max:255'),
⋮----
Text::make('Degree')
⋮----
Text::make('Field of Study')
->nullable(),
⋮----
Date::make('Start Date')
->rules('required'),
⋮----
Date::make('End Date')
->nullable()
->hideFromIndex()
->help('Leave blank if currently attending'),
⋮----
Boolean::make('Current')
->default(false),
⋮----
Number::make('GPA')
->step(0.01)
->min(0)
->max(4.0)
⋮----
->hideFromIndex(),
⋮----
KeyValue::make('Achievements')
->keyLabel('Achievement')
->valueLabel('Description')
````

## File: app/Nova/Project.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class Project extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Project>
     */
public static $model = \App\Models\Project::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'name';
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
BelongsTo::make('User')
->default($request->user()->id)
->withoutTrashed()
->searchable(),
⋮----
Text::make('Name')
->sortable()
->rules('required', 'max:255'),
⋮----
Textarea::make('Description')
->rules('required')
->hideFromIndex(),
⋮----
Date::make('Start Date')
->nullable(),
⋮----
Date::make('End Date')
⋮----
Text::make('URL')
->hideFromIndex()
⋮----
KeyValue::make('Technologies Used')
->keyLabel('Technology')
->valueLabel('Details')
->nullable()
⋮----
KeyValue::make('Achievements')
->keyLabel('Achievement')
->valueLabel('Description')
````

## File: app/Nova/Resume.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class Resume extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Resume>
     */
public static $model = \App\Models\Resume::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'id';
⋮----
/**
     * Get the displayable label of the resource.
     *
     * @return string
     */
public static function label()
⋮----
/**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
public static function singularLabel()
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
BelongsTo::make('User')
->default($request->user()->id)
->withoutTrashed()
->searchable(),
⋮----
BelongsTo::make('Job Post', 'jobPost', JobPost::class),
⋮----
Textarea::make('Content')
->alwaysShow()
->hideFromIndex(),
⋮----
Text::make('File Path')
->nullable(),
⋮----
Number::make('Word Count')
⋮----
KeyValue::make('Skills Included', 'skills_included')
->keyLabel('Skill')
->valueLabel('Relevance')
->nullable()
⋮----
KeyValue::make('Experiences Included', 'experiences_included')
->keyLabel('Experience')
⋮----
KeyValue::make('Education Included', 'education_included')
->keyLabel('Education')
⋮----
KeyValue::make('Projects Included', 'projects_included')
->keyLabel('Project')
⋮----
Code::make('Rule Compliance')
->language('json')
⋮----
Code::make('Generation Metadata')
````

## File: app/Nova/Skill.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class Skill extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Skill>
     */
public static $model = \App\Models\Skill::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'name';
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
BelongsTo::make('User')
->default($request->user()->id)
->withoutTrashed()
->searchable(),
⋮----
Text::make('Name')
->sortable()
->rules('required', 'max:255'),
⋮----
Select::make('Type')
->options([
⋮----
->default('technical')
->rules('required'),
⋮----
Number::make('Proficiency')
->min(1)
->max(10)
->default(0)
->help('Rate your proficiency from 1-10'),
⋮----
Number::make('Years Experience')
->min(0)
->default(0),
````

## File: app/Nova/WorkExperience.php
````php
namespace App\Nova;
⋮----
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class WorkExperience extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\WorkExperience>
     */
public static $model = \App\Models\WorkExperience::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'company_name';
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
BelongsTo::make('User')
->default($request->user()->id)
->withoutTrashed()
->searchable(),
⋮----
Text::make('Company Name')
->sortable()
->rules('required', 'max:255'),
⋮----
Text::make('Position')
⋮----
Date::make('Start Date')
->rules('required'),
⋮----
Date::make('End Date')
->nullable()
->hideFromIndex()
->help('Leave blank if this is your current job'),
⋮----
Boolean::make('Current Job')
->default(false),
⋮----
Textarea::make('Description')
->rules('required')
->hideFromIndex(),
⋮----
KeyValue::make('Skills Used')
->keyLabel('Skill')
->valueLabel('Description')
⋮----
KeyValue::make('Achievements')
->keyLabel('Achievement')
````

## File: app/Providers/NovaServiceProvider.php
````php
namespace App\Providers;
⋮----
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
⋮----
class NovaServiceProvider extends NovaApplicationServiceProvider
⋮----
/**
     * Bootstrap any application services.
     */
public function boot(): void
⋮----
parent::boot();
⋮----
Nova::withBreadcrumbs();
⋮----
Nova::footer(function (Request $request) {
return Blade::render('
⋮----
/**
     * Register the configurations for Laravel Fortify.
     */
protected function fortify(): void
⋮----
Nova::fortify()
->features([
Features::updatePasswords(),
// Features::emailVerification(),
// Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
⋮----
->register();
⋮----
/**
     * Register the Nova routes.
     */
protected function routes(): void
⋮----
Nova::routes()
->withAuthenticationRoutes(default: true)
->withPasswordResetRoutes()
->withoutEmailVerificationRoutes()
⋮----
/**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
protected function gate(): void
⋮----
Gate::define('viewNova', function (User $user) {
⋮----
//            return in_array($user->email, [
//                'nathaniel@attentiv.dev',
//            ]);
⋮----
/**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Dashboard>
     */
protected function dashboards(): array
⋮----
new \App\Nova\Dashboards\Main,
⋮----
/**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Tool>
     */
public function tools(): array
⋮----
/**
     * Register any application services.
     */
public function register(): void
⋮----
parent::register();
⋮----
Nova::initialPath('/resources/users');
⋮----
Nova::report(function ($exception) {
Log::error($exception->getMessage(), [
⋮----
'stack' => $exception->getTraceAsString(),
````

## File: app/Services/PromptEngineeringService.php
````php
namespace App\Services;
⋮----
use App\Models\JobPost;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Exception;
⋮----
class PromptEngineeringService
⋮----
/**
     * Implement the multi-step workflow for generation
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param string $type
     * @return array
     */
public function generateWithMultiStepWorkflow(JobPost $jobPost, User $user, string $type): array
⋮----
// Step 1: Extract key requirements from job description
$requirements = $this->extractKeyRequirements($jobPost);
⋮----
// Step 2: Match user skills to requirements
$matchedSkills = $this->matchUserSkillsToRequirements($user, $requirements);
⋮----
// Step 3: Generate content with tailored emphasis
$variations = $this->generateContentVariations($jobPost, $user, $matchedSkills, $type);
⋮----
// Step 4: Validate against rules and refine
$validatedResults = $this->validateAndRefine($variations, $jobPost, $type);
⋮----
/**
     * Extract key requirements from job description
     *
     * @param JobPost $jobPost
     * @return array
     */
private function extractKeyRequirements(JobPost $jobPost): array
⋮----
$response = OpenAI::chat()->create([
⋮----
// Parse JSON response
⋮----
// If the response isn't valid JSON, try to extract it
⋮----
Log::error("Failed to extract requirements", [
⋮----
'error' => $e->getMessage(),
⋮----
// Return a basic structure if extraction fails
⋮----
/**
     * Match user skills to job requirements
     *
     * @param User $user
     * @param array $requirements
     * @return array
     */
private function matchUserSkillsToRequirements(User $user, array $requirements): array
⋮----
// Gather user skills
$userSkills = $user->skills()->get()->map(function ($skill) {
⋮----
})->toArray();
⋮----
// Get user experience
$userExperience = $user->workExperiences()->get()->map(function ($exp) {
⋮----
now()->diffInYears($exp->start_date) :
$exp->end_date->diffInYears($exp->start_date),
⋮----
// Get user education
$userEducation = $user->education()->get()->map(function ($edu) {
⋮----
Log::error("Failed to match skills to requirements", [
⋮----
// Return a basic structure if matching fails
⋮----
/**
     * Generate multiple content variations (Power of Three technique)
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param array $matchedSkills
     * @param string $type
     * @return array
     */
private function generateContentVariations(JobPost $jobPost, User $user, array $matchedSkills, string $type): array
⋮----
$templates = $this->getTemplatesForType($type);
⋮----
// Prepare base prompt
$basePrompt = $this->prepareBasePrompt($jobPost, $user, $matchedSkills, $type);
⋮----
// Generate one variation for each template style
⋮----
'temperature' => 0.7, // Slightly higher temperature for variation
⋮----
Log::error("Failed to generate content variation", [
⋮----
/**
     * Get templates for the specified document type
     *
     * @param string $type
     * @return array
     */
private function getTemplatesForType(string $type): array
⋮----
} else { // cover_letter
⋮----
/**
     * Prepare the base prompt for generation
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param array $matchedSkills
     * @param string $type
     * @return string
     */
private function prepareBasePrompt(JobPost $jobPost, User $user, array $matchedSkills, string $type): string
⋮----
/**
     * Validate and refine generated content variations
     *
     * @param array $variations
     * @param JobPost $jobPost
     * @param string $type
     * @return array
     */
private function validateAndRefine(array $variations, JobPost $jobPost, string $type): array
⋮----
// Analyze content against rules
$validationResponse = OpenAI::chat()->create([
⋮----
// Parse JSON validation
⋮----
// If score is low, refine the content
⋮----
$refinedContent = $this->refineContent($variation['content'], $validation, $jobPost, $type);
⋮----
Log::error("Failed to validate and refine content", [
⋮----
/**
     * Refine content based on validation feedback
     *
     * @param string $content
     * @param array $validation
     * @param JobPost $jobPost
     * @param string $type
     * @return string
     */
private function refineContent(string $content, array $validation, JobPost $jobPost, string $type): string
⋮----
// Prepare feedback for refinement
⋮----
Log::error("Failed to refine content", [
⋮----
// Return original content if refinement fails
⋮----
/**
     * Select the best variation based on validation scores
     *
     * @param array $results
     * @return array
     */
public function selectBestVariation(array $results): array
⋮----
// If it was refined, use the refined content
⋮----
// Fallback if no best template found
````

## File: app/Services/RulesService.php
````php
namespace App\Services;
⋮----
use App\Models\Rule;
⋮----
class RulesService
⋮----
/**
     * Get all rules or filter by type
     *
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
public function getAllRules(string $type = null)
⋮----
return Rule::where('type', $type)
->orWhere('type', 'both')
->orderBy('importance', 'desc')
->get();
⋮----
return Rule::orderBy('importance', 'desc')->get();
⋮----
/**
     * Validate content against applicable rules
     *
     * @param string $content
     * @param string $type
     * @param array $metadata
     * @return array
     */
public function validateContent(string $content, string $type, array $metadata = [])
⋮----
$rules = $this->getAllRules($type);
⋮----
'passed' => $this->checkRule($content, $rule, $metadata),
⋮----
/**
     * Check content against a specific rule
     *
     * @param string $content
     * @param Rule $rule
     * @param array $metadata
     * @return bool
     */
public function checkRule(string $content, Rule $rule, array $metadata)
⋮----
// If no validation logic, default to pass
⋮----
return $this->checkWordCount($content, $logic);
⋮----
return $this->checkPageCount($content, $logic, $metadata);
⋮----
return $this->checkKeywordInclusion($content, $logic, $metadata);
⋮----
return $this->checkContactInfo($content, $metadata);
⋮----
return $this->checkPassiveLanguage($content);
⋮----
return $this->checkPersonalPronouns($content);
⋮----
return $this->checkQuantifiableResults($content);
⋮----
return $this->checkGrammarSpelling($content);
⋮----
return $this->checkFormattingConsistency($content);
⋮----
/**
     * Check if content meets word count requirements
     */
private function checkWordCount(string $content, array $logic): bool
⋮----
/**
     * Estimate if content meets page count requirements
     */
private function checkPageCount(string $content, array $logic, array $metadata): bool
⋮----
// Estimate page count based on word count
// Assuming ~450 words per page for resume/cover letter
⋮----
/**
     * Check if content includes required keywords
     */
private function checkKeywordInclusion(string $content, array $logic, array $metadata): bool
⋮----
// Extract keywords from job description if available
⋮----
// If job description is available, extract keywords from it
⋮----
// Simple keyword extraction - extract nouns and skills
// This is simplified - in production, you'd want more robust extraction
$requiredKeywords = $this->extractKeywordsFromJobDescription($jobDescription);
⋮----
// Require at least 70% of keywords to match
⋮----
/**
     * Extract keywords from job description
     */
private function extractKeywordsFromJobDescription(string $jobDescription): array
⋮----
// This is a simplified implementation
// In production, use NLP libraries for better extraction
⋮----
/**
     * Check if content includes contact information
     */
private function checkContactInfo(string $content, array $metadata): bool
⋮----
// Check if basic contact info is included
⋮----
// Require at least 2 contact methods
⋮----
/**
     * Check for passive language
     */
private function checkPassiveLanguage(string $content): bool
⋮----
// Passive voice constructions to look for
⋮----
// For a resume/cover letter, passive voice should be minimal
// Allow up to 3 instances in the entire document
⋮----
/**
     * Check for personal pronouns usage
     */
private function checkPersonalPronouns(string $content): bool
⋮----
// Check for whole word matches only
⋮----
// For a resume, personal pronouns should be minimal or absent
// For a cover letter, some pronouns are acceptable
⋮----
/**
     * Check for quantifiable results
     */
private function checkQuantifiableResults(string $content): bool
⋮----
// Look for numbers and percentages as indicators of quantified achievements
⋮----
// Should have at least 3 quantified achievements
⋮----
/**
     * Basic check for potential grammar/spelling issues
     */
private function checkGrammarSpelling(string $content): bool
⋮----
// In production, you'd integrate with a grammar/spell checking API
⋮----
// Common grammar errors to check
⋮----
'alot', // should be "a lot"
'seperate', // should be "separate"
'definately', // should be "definitely"
'recieve', // should be "receive"
⋮----
// For a professional document, there should be no obvious errors
⋮----
/**
     * Check for consistent formatting
     */
private function checkFormattingConsistency(string $content): bool
⋮----
// This is challenging to implement without parsing the actual document
// For a basic implementation, we'll check for consistent use of bullet points
⋮----
// Remove zero counts
⋮----
// If multiple bullet styles are used, it's inconsistent
````

## File: database/factories/UserFactory.php
````php
namespace Database\Factories;
⋮----
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
⋮----
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
⋮----
/**
     * The current password being used by the factory.
     */
protected static ?string $password;
⋮----
/**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
⋮----
'first_name' => fake()->firstName(),
'last_name' => fake()->lastName(),
'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years'),
'profile_photo_url' => fake()->imageUrl(200, 200, 'people'),
'email' => fake()->unique()->safeEmail(),
'phone_number' => fake()->phoneNumber(),
'location' => fake()->city() . ', ' . fake()->stateAbbr() . ', USA',
'linkedin_url' => 'https://linkedin.com/in/' . fake()->userName(),
'github_url' => 'https://github.com/' . fake()->userName(),
'personal_website_url' => 'https://' . fake()->domainName(),
⋮----
'password' => static::$password ??= Hash::make('password'),
'remember_token' => Str::random(10),
⋮----
/**
     * Indicate that the model's email address should be unverified.
     */
public function unverified(): static
⋮----
return $this->state(fn(array $attributes) => [
````

## File: database/migrations/0001_01_01_000000_create_users_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('users', function (Blueprint $table) {
$table->id();
$table->string('first_name');
$table->string('last_name');
$table->date('date_of_birth')->nullable();
$table->string('profile_photo_url')->nullable();
$table->string('email')->unique();
$table->string('phone_number')->nullable();
$table->string('location')->nullable(); // City, State, Country
$table->string('linkedin_url')->nullable();
$table->string('github_url')->nullable();
$table->string('personal_website_url')->nullable();
$table->string('portfolio_url')->nullable();
$table->timestamp('email_verified_at')->nullable();
$table->string('password');
$table->rememberToken();
$table->timestamps();
$table->softDeletes();
⋮----
Schema::create('password_reset_tokens', function (Blueprint $table) {
$table->string('email')->primary();
$table->string('token');
$table->timestamp('created_at')->nullable();
⋮----
Schema::create('sessions', function (Blueprint $table) {
$table->string('id')->primary();
$table->foreignId('user_id')->nullable()->index();
$table->string('ip_address', 45)->nullable();
$table->text('user_agent')->nullable();
$table->longText('payload');
$table->integer('last_activity')->index();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('users');
Schema::dropIfExists('password_reset_tokens');
Schema::dropIfExists('sessions');
````

## File: database/seeders/OpenAIPromptsSeeder.php
````php
namespace Database\Seeders;
⋮----
use Illuminate\Database\Seeder;
use App\Models\OpenAIPrompt;
⋮----
class OpenAIPromptsSeeder extends Seeder
⋮----
/**
     * Run the database seeds.
     */
public function run(): void
⋮----
// 'max_tokens' => 2000,
⋮----
// 'max_tokens' => 1000,
⋮----
// Job Post Analysis Prompt
⋮----
// 'model' => 'gpt-4o-mini',
// 'max_tokens' => 16384,
⋮----
// 'model' => 'o3-mini',
// 'max_tokens' => 100000,
⋮----
// 'model' => 'o1',
⋮----
// 'model' => 'o1-pro', // SUPER COSTLY BUT SUPER SMART
⋮----
//
//                'model' => 'gpt-4.5-preview', // SUPER COSTLY BUT SUPER SMART
//                'max_tokens' => 16384,
⋮----
//                    'job_content' => [
//                        'type' => 'string',
//                        'description' => 'HTML or markdown content of the job posting',
//                    ]
⋮----
OpenAIPrompt::create($prompt);
````

## File: database/seeders/UserSeeder.php
````php
namespace Database\Seeders;
⋮----
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
⋮----
class UserSeeder extends Seeder
⋮----
/**
     * Run the database seeds.
     */
public function run(): void
⋮----
User::create([
⋮----
'password' => Hash::make('propel42'),
⋮----
// You can add more users if needed
````

## File: app/Nova/User.php
````php
namespace App\Nova;
⋮----
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class User extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
public static $model = \App\Models\User::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'id';
⋮----
/**
     * Get the value that should be displayed to represent the resource.
     *
     * @return \Stringable|string
     */
public function title()
⋮----
// return full name aka first name + last name
⋮----
public function subtitle()
⋮----
/**
     * Get the displayable label of the resource.
     *
     * @return string
     */
public static function label()
⋮----
/**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
public static function singularLabel()
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
public function fields(NovaRequest $request): array
⋮----
ID::make()->sortable(),
⋮----
Gravatar::make()
->maxWidth(50),
⋮----
Text::make('First Name')
->sortable()
->rules('required', 'max:255'),
⋮----
Text::make('Last Name')
⋮----
Text::make('Name', function () {
⋮----
})->onlyOnIndex(),
⋮----
Text::make('Email')
⋮----
->rules('required', 'email', 'max:254')
->creationRules('unique:users,email')
->updateRules('unique:users,email,{{resourceId}}'),
⋮----
Password::make('Password')
->onlyOnForms()
->creationRules($this->passwordRules())
->updateRules($this->optionalPasswordRules()),
⋮----
Date::make('Date of Birth')->nullable(),
⋮----
Text::make('Phone Number')->nullable(),
⋮----
Text::make('Location')->nullable(),
⋮----
Text::make('LinkedIn', 'linkedin_url')
// ->hideFromIndex()
->displayUsing(function ($value) {
$username = Str::after($value, 'linkedin.com/in/');
$username = Str::before($username, '/');
⋮----
->asHtml()
->nullable(),
⋮----
Text::make('GitHub', 'github_url')
⋮----
$username = Str::after($value, 'github.com/');
⋮----
Text::make('Personal Website URL')
->hideFromIndex()
->copyable()
⋮----
Text::make('Portfolio URL')
⋮----
HasMany::make('Work Experiences', 'workExperiences', WorkExperience::class),
HasMany::make('Education', 'education', Education::class),
HasMany::make('Skills', 'skills', Skill::class),
HasMany::make('Projects', 'projects', Project::class),
HasMany::make('Job Posts', 'jobPosts', JobPost::class),
HasMany::make('Resumes', 'resumes', Resume::class),
HasMany::make('Cover Letters', 'coverLetters', CoverLetter::class),
⋮----
/**
     * Get the cards available for the request.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
public function cards(NovaRequest $request): array
⋮----
/**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
public function filters(NovaRequest $request): array
⋮----
/**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
public function lenses(NovaRequest $request): array
⋮----
/**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
public function actions(NovaRequest $request): array
````

## File: app/Services/OpenAIService.php
````php
namespace App\Services;
⋮----
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\OpenAIPrompt;
use App\Models\User;
use App\Models\JobPost;
use App\Services\RulesService;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
⋮----
class OpenAIService
⋮----
protected $rulesService;
⋮----
/**
     * The OpenAI API key
     *
     * @var string
     */
protected $apiKey;
⋮----
/**
     * The OpenAI API URL
     *
     * @var string
     */
protected $apiUrl = 'https://api.openai.com/v1/chat/completions';
⋮----
public function __construct(RulesService $rulesService)
⋮----
/**
     * Generate a resume based on job post and user data
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param string|null $promptName
     * @param array $extraContext Additional context for the prompt (like feedback)
     * @return array
     * @throws Exception
     */
public function generateResume(JobPost $jobPost, User $user, ?string $promptName = null, array $extraContext = [])
⋮----
$prompt = $this->getPrompt($promptName);
⋮----
// Prepare context data
$jobData = $this->prepareJobData($jobPost);
$userData = $this->prepareUserData($user);
$rules = $this->rulesService->getAllRules('resume');
$rulesText = $this->prepareRulesText($rules);
⋮----
// Replace placeholders in the prompt template
$finalPrompt = $this->replacePlaceholders($prompt->prompt_template, [
⋮----
'job_post' => $jobPost->toArray()
⋮----
// Add feedback for regeneration if available
⋮----
// Call OpenAI API
$result = $this->callOpenAI($prompt->model, $finalPrompt, $prompt->max_tokens, $prompt->temperature);
⋮----
// Return generated content
⋮----
'usage' => $result->usage->toArray(),
⋮----
/**
     * Generate a cover letter based on job post and user data
     *
     * @param JobPost $jobPost
     * @param User $user
     * @param string|null $promptName
     * @param array $extraContext Additional context for the prompt (like feedback)
     * @return array
     * @throws Exception
     */
public function generateCoverLetter(JobPost $jobPost, User $user, ?string $promptName = null, array $extraContext = [])
⋮----
$rules = $this->rulesService->getAllRules('cover_letter');
⋮----
/**
     * Check if content follows specific rules using OpenAI
     *
     * @param string $content
     * @param string $type
     * @param array $rules
     * @return array
     * @throws Exception
     */
public function checkRuleCompliance(string $content, string $type, $rules)
⋮----
$prompt = $this->getPrompt('rule_compliance_check');
⋮----
// Call OpenAI API with lower temperature for more deterministic response
$result = $this->callOpenAI($prompt->model, $finalPrompt, $prompt->max_tokens, 0.3);
⋮----
// Parse the response to extract rule compliance results
⋮----
/**
     * Get a prompt by name
     *
     * @param string $name
     * @return OpenAIPrompt|null
     */
protected function getPrompt(string $name)
⋮----
return OpenAIPrompt::where('name', $name)
->where('active', true)
->first();
⋮----
/**
     * Prepare job data for the prompt
     *
     * @param JobPost $jobPost
     * @return string
     */
protected function prepareJobData(JobPost $jobPost)
⋮----
// Add required skills if available
⋮----
// Add preferred skills if available
⋮----
// Add required experience if available
⋮----
// Add required education if available
⋮----
/**
     * Prepare user data for the prompt
     *
     * @param User $user
     * @return string
     */
protected function prepareUserData(User $user)
⋮----
// Add LinkedIn URL if available
⋮----
// Add GitHub URL if available
⋮----
// Add personal website URL if available
⋮----
// Add portfolio URL if available
⋮----
// Add work experiences
⋮----
$workExperiences = $user->workExperiences()->orderBy('start_date', 'desc')->get();
⋮----
$endDate = $exp->current_job ? "Present" : $exp->end_date->format('M Y');
$data[] = "- {$exp->position} at {$exp->company_name} ({$exp->start_date->format('M Y')} - {$endDate})";
⋮----
// Add education
⋮----
$education = $user->education()->orderBy('start_date', 'desc')->get();
⋮----
$endDate = $edu->current ? "Present" : $edu->end_date->format('M Y');
⋮----
$data[] = "- {$edu->degree}{$fieldOfStudy} from {$edu->institution} ({$edu->start_date->format('M Y')} - {$endDate})";
⋮----
// Add skills
⋮----
$skills = $user->skills()->orderBy('proficiency', 'desc')->get();
⋮----
// Add projects
⋮----
$projects = $user->projects()->get();
⋮----
/**
     * Prepare rules text for the prompt
     *
     * @param \Illuminate\Database\Eloquent\Collection $rules
     * @return string
     */
protected function prepareRulesText($rules)
⋮----
$rulesText[] = ""; // Empty line between rules
⋮----
/**
     * Replace placeholders in prompt template
     *
     * @param string $template
     * @param array $replacements
     * @return string
     */
protected function replacePlaceholders(string $template, array $replacements)
⋮----
// Handle nested arrays by replacing dot notation placeholders
$this->replaceArrayPlaceholders($result, $key, $value);
⋮----
/**
     * Replace array placeholders in dot notation
     *
     * @param string &$template
     * @param string $prefix
     * @param array $array
     */
protected function replaceArrayPlaceholders(string &$template, string $prefix, array $array)
⋮----
$this->replaceArrayPlaceholders($template, $prefix . "." . $key, $value);
⋮----
/**
     * Call OpenAI API
     *
     * @param string $model
     * @param string $prompt
     * @param int $maxTokens
     * @param float $temperature
     * @return mixed
     */
protected function callOpenAI(string $model, string $prompt, int $maxTokens, float $temperature)
⋮----
return OpenAI::chat()->create([
⋮----
/**
     * Generate a completion using OpenAI API
     *
     * @param OpenAIPrompt $prompt The prompt to use
     * @param array $parameters The parameters to replace in the prompt
     * @return string The generated text
     * @throws Exception If there is an error
     */
public function generateCompletion(OpenAIPrompt $prompt, array $parameters): string
⋮----
set_time_limit(0); // Disable time limit for long requests
⋮----
$promptText = $this->replacePlaceholders($prompt->prompt_template, $parameters);
⋮----
// Prepare the request payload
⋮----
// Log the request (omit the API key for security)
Log::info('OpenAI Request', [
⋮----
// Make the API request
$response = Http::withHeaders([
⋮----
])->timeout(120)->post($this->apiUrl, $payload);
⋮----
// Check if the request was successful
if (!$response->successful()) {
$error = $response->json();
Log::error('OpenAI API Error', [
'status' => $response->status(),
⋮----
// Extract and return the response text
$responseData = $response->json();
⋮----
// Remove any JSON code block markers if present (```json and ```)
````

## File: database/seeders/DatabaseSeeder.php
````php
namespace Database\Seeders;
⋮----
use Illuminate\Database\Seeder;
⋮----
class DatabaseSeeder extends Seeder
⋮----
/**
     * Seed the application's database.
     */
public function run(): void
⋮----
$this->call([
````

## File: app/Nova/JobPost.php
````php
namespace App\Nova;
⋮----
use App\Nova\Actions\ConvertGoogleJobPost;
use App\Nova\Actions\ImportJobPostFromContent;
use App\Nova\Repeaters\EducationItem;
use App\Nova\Repeaters\ExperienceItem;
use App\Nova\Repeaters\SkillItem;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;
⋮----
class JobPost extends Resource
⋮----
/**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\JobPost>
     */
public static $model = \App\Models\JobPost::class;
⋮----
/**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
public static $title = 'job_title';
⋮----
/**
     * The columns that should be searched.
     *
     * @var array
     */
public static $search = [
⋮----
/**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
public function fields(NovaRequest $request)
⋮----
ID::make()->sortable(),
⋮----
BelongsTo::make('User')
->default($request->user()->id)
->withoutTrashed()
->searchable(),
⋮----
Text::make('Company Name')
->sortable()
->rules('required', 'max:255'),
⋮----
Text::make('Job Title')
⋮----
Textarea::make('Job Description')
->rules('required')
->hideFromIndex(),
⋮----
Repeater::make('Required Education')
->repeatables([
EducationItem::make()
⋮----
->asJson()
->nullable()
⋮----
Repeater::make('Required Experience')
⋮----
ExperienceItem::make()
⋮----
Repeater::make('Required Skills')
⋮----
SkillItem::make()
⋮----
Repeater::make('Preferred Skills')
⋮----
Text::make('Job Post URL')
->hideFromIndex()
->nullable(),
⋮----
Date::make('Job Post Date')
⋮----
Select::make('Job Location Type')
->options([
⋮----
->default('remote'),
⋮----
Select::make('Position Level')
⋮----
->default('mid-level'),
⋮----
Select::make('Job Type')
⋮----
->default('full-time'),
⋮----
Number::make('Resume Min Words')
->default(450)
⋮----
Number::make('Resume Max Words')
->default(850)
⋮----
Number::make('Cover Letter Min Words')
⋮----
Number::make('Cover Letter Max Words')
->default(750)
⋮----
Number::make('Resume Min Pages')
->default(1)
⋮----
Number::make('Resume Max Pages')
->default(2)
⋮----
Number::make('Cover Letter Min Pages')
⋮----
Number::make('Cover Letter Max Pages')
⋮----
Currency::make('Salary Range Min')
⋮----
Currency::make('Salary Range Max')
⋮----
Currency::make('Min Acceptable Salary')
⋮----
Date::make('Ideal Start Date')
⋮----
Number::make('Position Preference')
⋮----
->help('1 = top choice, 2 = second choice, etc.')
⋮----
Boolean::make('Open To Travel')
->default(true)
⋮----
Boolean::make('First Time Applying')
⋮----
Textarea::make('Things I Like')
⋮----
Textarea::make('Things I Dislike')
⋮----
Textarea::make('Things I Like About Company')
⋮----
Textarea::make('Things I Dislike About Company')
⋮----
HasMany::make('Resumes'),
HasMany::make('Cover Letters', 'coverLetters'),
⋮----
/**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
public function actions(NovaRequest $request)
⋮----
ConvertGoogleJobPost::make()->standalone(),
ImportJobPostFromContent::make()->standalone(),
⋮----
new \App\Nova\Actions\GenerateResume,
new \App\Nova\Actions\GenerateCoverLetter,
new \App\Nova\Actions\GenerateApplicationMaterials,
new \App\Nova\Actions\RegenerateWithFeedback,
````

## File: app/Services/GenerationService.php
````php
namespace App\Services;
⋮----
use App\Models\JobPost;
use App\Models\Resume;
use App\Models\CoverLetter;
use App\Models\ThreadSession;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;
⋮----
class GenerationService
⋮----
protected $threadManager;
protected $promptEngineering;
protected $embeddings;
protected $pdf;
⋮----
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
⋮----
/**
     * Generate a resume for a job post
     *
     * @param JobPost $jobPost
     * @return Resume
     */
public function generateResume(JobPost $jobPost): Resume
⋮----
// Step 1: Generate embeddings and find matches
$matches = $this->embeddings->findSkillMatches($user, $jobPost);
$recommendations = $this->embeddings->generateRecommendations($user, $jobPost);
⋮----
// Step 2: Use multi-step workflow to generate content variations
$variations = $this->promptEngineering->generateWithMultiStepWorkflow($jobPost, $user, 'resume');
⋮----
// Step 3: Select the best variation
$selectedResult = $this->promptEngineering->selectBestVariation($variations);
⋮----
// Step 4: Start a resume generation session with the assistant
$session = $this->threadManager->startResumeSession($user, $jobPost);
⋮----
// Add context from our semantic analysis and variations
$contextMessage = $this->prepareContextMessage($recommendations, $selectedResult, 'resume');
$this->threadManager->addMessage($session->thread_id, $contextMessage);
⋮----
// Add content to refine
$this->threadManager->addMessage($session->thread_id, "Here's the content to refine:\n\n" . $content);
⋮----
// Generate final content with the assistant
$finalContent = $this->threadManager->generateContent($session);
⋮----
// Count words
⋮----
// Create resume record
$resume = Resume::create([
⋮----
'top_matches' => $this->getTopMatches($matches),
⋮----
// Generate PDF
$this->pdf->generateResumePDF($resume);
⋮----
// Validate the resume
$validation = $this->threadManager->validateDocument($finalContent, 'resume', $jobPost);
⋮----
// Update resume with validation results
$resume->update([
⋮----
Log::error("Resume generation failed", [
⋮----
'error' => $e->getMessage(),
'trace' => $e->getTraceAsString(),
⋮----
/**
     * Generate a cover letter for a job post
     *
     * @param JobPost $jobPost
     * @return CoverLetter
     */
public function generateCoverLetter(JobPost $jobPost): CoverLetter
⋮----
$variations = $this->promptEngineering->generateWithMultiStepWorkflow($jobPost, $user, 'cover_letter');
⋮----
// Step 4: Start a cover letter generation session with the assistant
$session = $this->threadManager->startCoverLetterSession($user, $jobPost);
⋮----
$contextMessage = $this->prepareContextMessage($recommendations, $selectedResult, 'cover_letter');
⋮----
// Create cover letter record
$coverLetter = CoverLetter::create([
⋮----
$this->pdf->generateCoverLetterPDF($coverLetter);
⋮----
// Validate the cover letter
$validation = $this->threadManager->validateDocument($finalContent, 'cover_letter', $jobPost);
⋮----
// Update cover letter with validation results
$coverLetter->update([
⋮----
Log::error("Cover letter generation failed", [
⋮----
/**
     * Prepare context message for the assistant
     *
     * @param array $recommendations
     * @param array $selectedResult
     * @param string $type
     * @return string
     */
private function prepareContextMessage(array $recommendations, array $selectedResult, string $type): string
⋮----
/**
     * Get top matches from semantic matching
     *
     * @param array $matches
     * @return array
     */
private function getTopMatches(array $matches): array
⋮----
// Sort by similarity (highest first)
⋮----
// Take top 5 matches
⋮----
/**
     * Regenerate a document with feedback
     *
     * @param Resume|CoverLetter $document
     * @param array $feedback
     * @return Resume|CoverLetter
     */
public function regenerateWithFeedback($document, array $feedback)
⋮----
// Get the previous session
⋮----
// Create a new session of the same type
⋮----
// Add the feedback to the thread
⋮----
$this->threadManager->addMessage($session->thread_id, $feedbackMessage);
⋮----
// Generate new content
$content = $this->threadManager->generateContent($session);
⋮----
// Update the document with new content
$document->update([
⋮----
'file_path' => null, // Clear file path so it will be regenerated
⋮----
$this->pdf->generateResumePDF($document);
⋮----
$this->pdf->generateCoverLetterPDF($document);
⋮----
// Validate the new document
$validation = $this->threadManager->validateDocument($content, $type, $jobPost);
⋮----
// Update document with validation results
⋮----
Log::error("Document regeneration failed", [
````

## File: app/Providers/AppServiceProvider.php
````php
namespace App\Providers;
⋮----
use Illuminate\Support\ServiceProvider;
use App\Services\AssistantsService;
use App\Services\ThreadManagementService;
use App\Services\PromptEngineeringService;
use App\Services\EmbeddingsService;
use App\Services\GenerationService;
use App\Services\PDFService;
⋮----
class AppServiceProvider extends ServiceProvider
⋮----
/**
     * Register any application services.
     */
public function register(): void
⋮----
$this->app->singleton(AssistantsService::class, function ($app) {
⋮----
$this->app->singleton(ThreadManagementService::class, function ($app) {
⋮----
$app->make(AssistantsService::class)
⋮----
$this->app->singleton(PromptEngineeringService::class, function ($app) {
⋮----
$this->app->singleton(EmbeddingsService::class, function ($app) {
⋮----
$this->app->singleton(GenerationService::class, function ($app) {
⋮----
$app->make(ThreadManagementService::class),
$app->make(PromptEngineeringService::class),
$app->make(EmbeddingsService::class),
$app->make(PDFService::class)
⋮----
/**
     * Bootstrap any application services.
     */
public function boot(): void
⋮----
//
````

## File: composer.json
````json
{
    "$schema": "https://getcomposer.org/schema.json",
    "name": "laravel/laravel",
    "type": "project",
    "description": "The skeleton application for the Laravel framework.",
    "keywords": [
        "laravel",
        "framework"
    ],
    "license": "MIT",
    "require": {
        "php": "^8.2",
        "barryvdh/laravel-dompdf": "^3.1",
        "laravel/framework": "^12.0",
        "laravel/horizon": "^5.31",
        "laravel/nova": "^5.0",
        "laravel/sanctum": "^4.0",
        "laravel/telescope": "^5.7",
        "laravel/tinker": "^2.10.1",
        "openai-php/client": "^0.10.3",
        "openai-php/laravel": "^0.11.0",
        "spatie/laravel-backup": "^9.2",
        "ext-dom": "*"
    },
    "require-dev": {
        "brianium/paratest": "^7.8",
        "fakerphp/faker": "^1.23",
        "laravel/nova-devtool": "^1.8",
        "laravel/pail": "^1.2.2",
        "laravel/pint": "^1.13",
        "laravel/sail": "^1.41",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "phpunit/phpunit": "^11.5.3"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://nova.laravel.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
            "@php artisan nova:publish --ansi"
        ],
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php artisan migrate --graceful --ansi"
        ],
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite"
        ]
    },
    "extra": {
        "laravel": {
            "dont-discover": []
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "php-http/discovery": true
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}
````
