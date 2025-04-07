# HireGlue Project Checklist

## Project Overview

HireGlue is a Laravel 12 + Nova application designed to automate the creation of job-specific resumes and cover letters.
The system leverages OpenAI's API and a rules engine based on best practices to generate optimal application materials
tailored to specific job postings.

## 1. Research and Planning

- [ ] Review all notes and takeaways from YouTube videos:
    - [ ] Ex-Google Recruiter (8 Secrets Recruiters Won't Tell You)
    - [ ] Google's NEW Prompting Guide
    - [ ] Writing Amazing Cover Letters (3 Golden Rules)
    - [ ] Writing Incredible Resumes (5 Golden Rules)
    - [ ] Cover Letter Mistakes to Avoid

- [ ] Define comprehensive rule sets for both resume and cover letter:
    - [ ] Format and structure rules (length, layout, fonts)
    - [ ] Content rules (word count, keywords, quantifiable results)
    - [ ] ATS optimization rules
    - [ ] Engagement and hook rules

- [ ] Research OpenAI API best practices:
    - [ ] Evaluate latest models (GPT-4o, Claude, etc.)
    - [ ] Explore assistants API and threads approach
    - [ ] Determine optimal token usage and context strategies
    - [ ] Identify vector embedding opportunities for job-resume matching

- [ ] Plan the architecture:
    - [ ] Design database schema
    - [ ] Map entity relationships
    - [ ] Define API endpoints
    - [ ] Plan OpenAI integration points

## 2. Environment Setup

- [ ] Set up development environment:
    - [ ] Install PHP 8.2+ and required extensions
    - [ ] Install Composer
    - [ ] Install Node.js and NPM

- [ ] Install Laravel 12:
  ```bash
  composer create-project laravel/laravel HireGlue
  cd HireGlue
  ```

- [ ] Configure Laravel:
    - [ ] Set up .env file
    - [ ] Configure database connection
    - [ ] Set up queue system for background processing

- [ ] Install Laravel Nova:
    - [ ] Add Nova repository to composer.json
    - [ ] Install Nova via composer
    - [ ] Publish Nova assets
    - [ ] Configure Nova

- [ ] Install required packages:
    - [ ] `composer require openai-php/client`
    - [ ] `composer require barryvdh/laravel-dompdf` (for PDF generation)
    - [ ] `composer require spatie/laravel-backup` (for data backup)
    - [ ] `composer require laravel/sanctum` (for API authentication)

## 3. Database Design

- [ ] Create migrations:

    - [ ] Users Table:
      ```php
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
      });
      ```

    - [ ] Job Posts Table:
      ```php
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
      });
      ```

    - [ ] Work Experience Table:
      ```php
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
      });
      ```

    - [ ] Education Table:
      ```php
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
      });
      ```

    - [ ] Skills Table:
      ```php
      Schema::create('skills', function (Blueprint $table) {
          $table->id();
          $table->foreignId('user_id')->constrained();
          $table->string('name');
          $table->enum('type', ['technical', 'soft', 'language', 'other'])->default('technical');
          $table->integer('proficiency')->default(0); // 1-10 scale
          $table->integer('years_experience')->default(0);
          $table->timestamps();
          $table->softDeletes();
      });
      ```

    - [ ] Projects Table:
      ```php
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
      });
      ```

    - [ ] Resumes Table:
      ```php
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
      });
      ```

    - [ ] Cover Letters Table:
      ```php
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
      });
      ```

    - [ ] Rules Table:
      ```php
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
      });
      ```

    - [ ] OpenAI Prompts Table:
      ```php
      Schema::create('openai_prompts', function (Blueprint $table) {
          $table->id();
          $table->string('name');
          $table->enum('type', ['resume', 'cover_letter', 'analysis', 'rule_check']);
          $table->text('prompt_template');
          $table->json('parameters')->nullable();
          $table->string('model')->default('gpt-4');
          $table->integer('max_tokens')->default(2000);
          $table->decimal('temperature', 2, 1)->default(0.7);
          $table->boolean('active')->default(true);
          $table->timestamps();
          $table->softDeletes();
      });
      ```

- [ ] Create seeders:
    - [ ] Create UserSeeder with your personal information
    - [ ] Create RulesSeeder with rules extracted from the YouTube videos
    - [ ] Create OpenAIPromptsSeeder with templates for different generation tasks

## 4. Models & Relationships

- [ ] Create and configure models:
    - [ ] User Model with relationships
    - [ ] JobPost Model with relationships
    - [ ] WorkExperience Model with relationships
    - [ ] Education Model with relationships
    - [ ] Skill Model with relationships
    - [ ] Project Model with relationships
    - [ ] Resume Model with relationships
    - [ ] CoverLetter Model with relationships
    - [ ] Rule Model with relationships
    - [ ] OpenAIPrompt Model with relationships

- [ ] Set up model factories for testing

## 5. Nova Resources

- [ ] Create Nova resources:
    - [ ] User Resource
      ```php
      namespace App\Nova;
      
      use Laravel\Nova\Fields\ID;
      use Laravel\Nova\Fields\Text;
      use Laravel\Nova\Fields\Date;
      use Laravel\Nova\Fields\BelongsToMany;
      use Laravel\Nova\Fields\HasMany;
      
      class User extends Resource
      {
          public static $model = \App\Models\User::class;
          
          public static $title = 'name';
          
          public static $search = [
              'id', 'first_name', 'last_name', 'email',
          ];
          
          public function fields(Request $request)
          {
              return [
                  ID::make()->sortable(),
                  
                  Text::make('First Name')
                      ->sortable()
                      ->rules('required', 'max:255'),
                  
                  Text::make('Last Name')
                      ->sortable()
                      ->rules('required', 'max:255'),
                  
                  Text::make('Email')
                      ->sortable()
                      ->rules('required', 'email', 'max:254')
                      ->creationRules('unique:users,email')
                      ->updateRules('unique:users,email,{{resourceId}}'),
                  
                  Date::make('Date of Birth')->nullable(),
                  
                  Text::make('Phone Number')->nullable(),
                  
                  Text::make('Location')->nullable(),
                  
                  Text::make('LinkedIn URL')
                      ->hideFromIndex()
                      ->nullable(),
                  
                  Text::make('GitHub URL')
                      ->hideFromIndex()
                      ->nullable(),
                  
                  Text::make('Personal Website URL')
                      ->hideFromIndex()
                      ->nullable(),
                  
                  Text::make('Portfolio URL')
                      ->hideFromIndex()
                      ->nullable(),
                  
                  HasMany::make('Work Experiences'),
                  HasMany::make('Education'),
                  HasMany::make('Skills'),
                  HasMany::make('Projects'),
                  HasMany::make('Job Posts'),
                  HasMany::make('Resumes'),
                  HasMany::make('Cover Letters'),
              ];
          }
      }
      ```

    - [ ] JobPost Resource (similar structure)
    - [ ] WorkExperience Resource
    - [ ] Education Resource
    - [ ] Skill Resource
    - [ ] Project Resource
    - [ ] Resume Resource
    - [ ] CoverLetter Resource
    - [ ] Rule Resource
    - [ ] OpenAIPrompt Resource

## 6. Rules Engine Implementation

- [ ] Create a RulesService to manage the rules:
  ```php
  namespace App\Services;
  
  use App\Models\Rule;
  
  class RulesService
  {
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
      
      protected function checkRule(string $content, Rule $rule, array $metadata)
      {
          // Implement rule checking logic based on rule type
          // For example, check word count, keyword inclusion, etc.
          
          // This would be expanded based on each rule's requirements
          if (isset($rule->validation_logic) && $rule->validation_logic) {
              $logic = json_decode($rule->validation_logic, true);
              
              // Example implementation for word count rule
              if (isset($logic['type']) && $logic['type'] === 'word_count') {
                  $wordCount = str_word_count($content);
                  return $wordCount >= ($logic['min'] ?? 0) && $wordCount <= ($logic['max'] ?? PHP_INT_MAX);
              }
              
              // More rule implementations would go here
          }
          
          return true; // Default to passing if no validation logic
      }
  }
  ```

- [ ] Create rules based on research:
    - [ ] Resume format rules
    - [ ] Resume content rules
    - [ ] Cover letter format rules
    - [ ] Cover letter content rules
    - [ ] ATS optimization rules

## 7. OpenAI Integration

- [ ] Create OpenAIService:
  ```php
  namespace App\Services;
  
  use OpenAI;
  use App\Models\OpenAIPrompt;
  
  class OpenAIService
  {
      protected $client;
      
      public function __construct()
      {
          $this->client = OpenAI::client(config('services.openai.api_key'));
      }
      
      public function generateResume($jobPost, $user, $promptName = 'resume_generation')
      {
          $prompt = $this->getPrompt($promptName);
          
          if (!$prompt) {
              throw new \Exception("Prompt not found: {$promptName}");
          }
          
          // Prepare context data
          $jobData = $this->prepareJobData($jobPost);
          $userData = $this->prepareUserData($user);
          $rules = app(RulesService::class)->getAllRules('resume');
          $rulesText = $this->prepareRulesText($rules);
          
          // Replace placeholders in the prompt template
          $finalPrompt = str_replace(
              ['{{job_data}}', '{{user_data}}', '{{rules}}'],
              [$jobData, $userData, $rulesText],
              $prompt->prompt_template
          );
          
          // Call OpenAI API
          $result = $this->client->chat()->create([
              'model' => $prompt->model,
              'messages' => [
                  ['role' => 'system', 'content' => 'You are an expert resume writer who creates perfectly tailored resumes for specific job postings.'],
                  ['role' => 'user', 'content' => $finalPrompt],
              ],
              'max_tokens' => $prompt->max_tokens,
              'temperature' => $prompt->temperature,
          ]);
          
          // Return generated content
          return [
              'content' => $result->choices[0]->message->content,
              'metadata' => [
                  'model' => $prompt->model,
                  'usage' => $result->usage->toArray(),
              ],
          ];
      }
      
      public function generateCoverLetter($jobPost, $user, $promptName = 'cover_letter_generation')
      {
          // Similar to generateResume but with cover letter specific logic
      }
      
      public function checkRuleCompliance($content, $type, $rules)
      {
          // Use OpenAI to check if the content follows specific rules
      }
      
      protected function getPrompt($name)
      {
          return OpenAIPrompt::where('name', $name)
              ->where('active', true)
              ->first();
      }
      
      protected function prepareJobData($jobPost)
      {
          // Format job post data for the prompt
      }
      
      protected function prepareUserData($user)
      {
          // Format user data for the prompt
      }
      
      protected function prepareRulesText($rules)
      {
          // Format rules data for the prompt
      }
  }
  ```

- [ ] Create OpenAI prompts:
    - [ ] Resume generation prompt
    - [ ] Cover letter generation prompt
    - [ ] Rules compliance checking prompt

## 8. PDF Generation

- [ ] Create PDFService:
  ```php
  namespace App\Services;
  
  use Barryvdh\DomPDF\Facade\Pdf;
  
  class PDFService
  {
      public function generateResumePDF($resume)
      {
          $pdf = PDF::loadView('pdfs.resume', [
              'resume' => $resume,
              'user' => $resume->user,
              'jobPost' => $resume->jobPost,
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
      
      public function generateCoverLetterPDF($coverLetter)
      {
          // Similar to generateResumePDF
      }
  }
  ```

- [ ] Create PDF templates:
    - [ ] Resume PDF blade template
    - [ ] Cover letter PDF blade template

## 9. Controllers & Routes (SKIP: since we're using Nova, we'll leverage )

- [ ] Create controllers:
    - [ ] JobPostController
    - [ ] ResumeController
    - [ ] CoverLetterController
    - [ ] GenerationController

- [ ] Define routes in `routes/web.php`

## 10. Generation Process Implementation

- [ ] Create a GenerationService:
  ```php
  namespace App\Services;
  
  use App\Models\JobPost;
  use App\Models\Resume;
  use App\Models\CoverLetter;
  
  class GenerationService
  {
      protected $openAI;
      protected $rules;
      protected $pdf;
      
      public function __construct(
          OpenAIService $openAI,
          RulesService $rules,
          PDFService $pdf
      ) {
          $this->openAI = $openAI;
          $this->rules = $rules;
          $this->pdf = $pdf;
      }
      
      public function generateResume(JobPost $jobPost)
      {
          $user = $jobPost->user;
          
          // Generate content with OpenAI
          $result = $this->openAI->generateResumeLegacy($jobPost, $user);
          
          // Validate against rules
          $compliance = $this->rules->validateContent(
              $result['content'],
              'resume',
              ['job_post' => $jobPost->toArray()]
          );
          
          // Create resume record
          $resume = Resume::create([
              'user_id' => $user->id,
              'job_post_id' => $jobPost->id,
              'content' => $result['content'],
              'word_count' => str_word_count($result['content']),
              'rule_compliance' => json_encode($compliance),
              'generation_metadata' => json_encode($result['metadata']),
          ]);
          
          // Generate PDF
          $pdfPath = $this->pdf->generateResumePDF($resume);
          
          return $resume;
      }
      
      public function generateCoverLetter(JobPost $jobPost)
      {
          // Similar to generateResume but for cover letter
      }
      
      public function regenerateWithFeedback(Resume|CoverLetter $document, array $feedback)
      {
          // Handle regeneration based on feedback
      }
  }
  ```

- [ ] Implement generation workflow in Nova

## 11. Testing

- [ ] Create unit tests:
    - [ ] Test RulesService
    - [ ] Test OpenAIService
    - [ ] Test PDFService
    - [ ] Test GenerationService

- [ ] Create feature tests:
    - [ ] Test resume generation
    - [ ] Test cover letter generation
    - [ ] Test PDF generation

## 12. Deployment

- [ ] Prepare for deployment:
    - [ ] Set up production environment
    - [ ] Configure environment variables
    - [ ] Set up queues for background processing
    - [ ] Configure storage for PDFs

- [ ] Deploy to production server

## 13. Documentation

- [ ] Document the codebase
- [ ] Create user guide
- [ ] Document the rules engine
- [ ] Document the OpenAI prompts and generation process

## 14. Future Enhancements

- [ ] Implement feedback loop to improve generation quality
- [ ] Add A/B testing for different resume and cover letter formats
- [ ] Add support for multiple users
- [ ] Implement analytics to track application success rate
- [ ] Add support for multiple templates
