<?php

namespace App\Nova\Actions;

use App\Models\Project;
use App\Models\Skill;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use OpenAI\Laravel\Facades\OpenAI;

class ImportGitHubData extends Action
{
    use InteractsWithQueue, Queueable;

    public $standalone = true;

    public $name = 'Import GitHub Data';

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        try {
            $username = $fields->github_username;

            // Fetch repositories
            $repos = $this->fetchGitHubRepos($username, $fields->github_token);

            // Import repository data as projects
            $projects = $this->importProjects($repos);

            // Extract skills from repository languages
            $skills = $this->extractSkills($repos);

            return Action::message("Successfully imported {$skills} skills and {$projects} projects from GitHub!");

        } catch (\Exception $e) {
            return Action::danger('Error importing GitHub data: ' . $e->getMessage());
        }
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('GitHub Username', 'github_username')
                ->rules('required')
                ->default('gluebag'),

            Text::make('GitHub Token (optional)', 'github_token')
                ->help('Personal access token to increase API rate limits and access private repos'),
        ];
    }

    /**
     * Fetch repositories from GitHub
     */
    private function fetchGitHubRepos(string $username, ?string $token = null): array
    {
        $headers = ['Accept' => 'application/vnd.github.v3+json'];

        if ($token) {
            $headers['Authorization'] = "token {$token}";
        }

        $response = Http::withHeaders($headers)
            ->get("https://api.github.com/users/{$username}/repos", [
                'type' => 'all',
                'per_page' => 100,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch GitHub repositories: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Import GitHub repositories as projects
     */
    private function importProjects(array $repos): int
    {
        $userId = Auth::id();
        $count = 0;

        foreach ($repos as $repo) {
            // Skip forks by default
            if ($repo['fork']) continue;

            // Get more details about the repo
            $details = $this->fetchRepoDetails($repo['full_name']);

            // Map languages to a format we can store
            $languages = isset($details['languages']) ? array_fill_keys(array_keys($details['languages']), '') : [];

            Project::create([
                'user_id' => $userId,
                'name' => $repo['name'],
                'description' => $repo['description'] ?? "Project from GitHub: {$repo['name']}",
                'start_date' => $repo['created_at'] ? date('Y-m-d', strtotime($repo['created_at'])) : null,
                'end_date' => null, // Assuming projects are ongoing
                'url' => $repo['html_url'],
                'technologies_used' => $languages,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Fetch additional repository details
     */
    private function fetchRepoDetails(string $fullName): array
    {
        $details = [];

        // Get languages
        $languagesResponse = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
            ])
            ->get("https://api.github.com/repos/{$fullName}/languages");

        if ($languagesResponse->successful()) {
            $details['languages'] = $languagesResponse->json();
        }

        return $details;
    }

    /**
     * Extract skills from repository languages and analyze with OpenAI
     */
    private function extractSkills(array $repos): int
    {
        $userId = Auth::id();
        $allLanguages = [];
        $skills = [];

        // Collect all languages from repos
        foreach ($repos as $repo) {
            $details = $this->fetchRepoDetails($repo['full_name']);
            if (isset($details['languages'])) {
                foreach (array_keys($details['languages']) as $language) {
                    $allLanguages[$language] = ($allLanguages[$language] ?? 0) + 1;
                }
            }
        }

        // Convert languages to skills
        foreach ($allLanguages as $language => $count) {
            // Skip already existing skills
            if (Skill::where('user_id', $userId)->where('name', $language)->exists()) {
                continue;
            }

            // Estimate proficiency based on frequency
            $proficiency = min(10, max(1, intval($count / 3) + 3));

            Skill::create([
                'user_id' => $userId,
                'name' => $language,
                'type' => 'technical',
                'proficiency' => $proficiency,
                'years_experience' => 1, // Default value
            ]);

            $skills[] = $language;
        }

        // Use OpenAI to suggest additional skills based on repository technologies
        if (!empty($allLanguages)) {
            $this->suggestAdditionalSkills(array_keys($allLanguages), $userId);
        }

        return count($skills);
    }

    /**
     * Suggest additional skills based on known technologies
     */
    private function suggestAdditionalSkills(array $technologies, int $userId): void
    {
        // Skip if we have too few technologies to analyze
        if (count($technologies) < 3) return;

        $techString = implode(', ', $technologies);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'temperature' => 0.3,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a technical skills analyst who can infer additional skills from a developer\'s technology stack.'
                ],
                [
                    'role' => 'user',
                    'content' => "Based on these technologies a developer uses: {$techString},
                    identify 5-10 additional technical skills they likely have and rate them on proficiency (1-10).

                    Format your response as a JSON array with objects containing:
                    - name: skill name
                    - type: \"technical\", \"soft\", or \"language\"
                    - proficiency: estimated proficiency 1-10
                    - years_experience: estimated years (1-5)

                    Only include skills not already in the technology list. Be realistic with estimates."
                ]
            ]
        ]);

        $jsonContent = $response->choices[0]->message->content;

        // Clean up potential markdown code blocks
        $jsonContent = preg_replace('/```json\s*|\s*```/', '', $jsonContent);

        try {
            $suggestedSkills = json_decode($jsonContent, true);

            if (is_array($suggestedSkills)) {
                foreach ($suggestedSkills as $skill) {
                    // Avoid duplicates
                    if (Skill::where('user_id', $userId)->where('name', $skill['name'])->exists()) {
                        continue;
                    }

                    Skill::create([
                        'user_id' => $userId,
                        'name' => $skill['name'],
                        'type' => $skill['type'] ?? 'technical',
                        'proficiency' => $skill['proficiency'] ?? 5,
                        'years_experience' => $skill['years_experience'] ?? 1,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Just log the error, don't interrupt the process
            \Log::error('Failed to parse suggested skills: ' . $e->getMessage());
        }
    }
}
