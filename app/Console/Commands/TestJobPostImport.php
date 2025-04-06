<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Nova\Actions\ImportJobPostFromUrl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Collection;

class TestJobPostImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-job-post-import {url?} {--markdown=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the ImportJobPostFromUrl action';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the first user to use as the authenticated user
        $user = User::first();
        if (!$user) {
            $this->error('No users found in the database.');
            return 1;
        }

        Auth::login($user);
        $this->info("Logged in as user: {$user->name}");

        $url = $this->argument('url');
        $markdownPath = $this->option('markdown');
        
        if (!$url && !$markdownPath) {
            $this->error('Please provide either a URL or a markdown file path.');
            return 1;
        }

        $action = new ImportJobPostFromUrl;
        $fields = new ActionFields(collect([
            'import_type' => 'google_jobs',
        ]), collect());

        if ($url) {
            $this->info("Importing from URL: {$url}");
            $fields->url = $url;
        } elseif ($markdownPath) {
            if (!file_exists($markdownPath)) {
                $this->error("Markdown file not found: {$markdownPath}");
                return 1;
            }
            
            $this->info("Importing from markdown file: {$markdownPath}");
            $fields->markdown_content = file_get_contents($markdownPath);
        }

        $result = $action->handle($fields, new Collection());
        // dd($result);

        // use reflection to get the private property $message
        $reflection = new \ReflectionClass($result);
        $property = $reflection->getProperty('message');
        $property->setAccessible(true);
        $message = $property->getValue($result);


        if (method_exists($result, 'type') && $result->type === 'danger') {
            $this->error($message);
            return 1;
        } else {
            $this->info($message);
            return 0;
        }
    }
} 