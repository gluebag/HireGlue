<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSeedDataCommand extends Command
{
    protected $signature = 'seed:generate {filepath=/Users/gluebag/Code/HireGlue/notes/table-backups/user-profile-backup.txt}';

    protected $description = 'Generate JSON seed data files from user profile data';

    public function handle()
    {
        $filepath = $this->argument('filepath');

        if (!file_exists($filepath)) {
            $this->error("File not found: {$filepath}");
            return;
        }

        $content = file_get_contents($filepath);

        // Extract and generate JSON files
        $this->generateJson('skills', $this->extractSkills($content));

        $this->generateJson('education', $this->extractEducation($content));
        $this->generateJson('work_experiences', $this->extractWorkExperiences($content));
        $this->generateJson('projects', $this->extractProjects($content));

        $this->info('Seed data JSON files generated successfully.');
    }

    private function generateJson($table, $data)
    {
        $filename = "/Users/gluebag/Code/HireGlue/notes/table-backups/{$table}_seed_data.json";
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        $this->info("Generated: {$filename}");
    }

    private function extractEducation($content)
    {
        preg_match_all('/- \*\*(.*?)\*\* from (.*?) \((.*?) - (.*?)\)/', $content, $matches, PREG_SET_ORDER);
        $education = [];

        foreach ($matches as $match) {
            $education[] = [
                'degree' => trim($match[1]),
                'institution' => trim($match[2]),
                'start_date' => date('Y-m-d', strtotime($match[3])),
                'end_date' => $match[4] === 'Present' ? null : date('Y-m-d', strtotime($match[4])),
                'current' => $match[4] === 'Present' ? 1 : 0,
                'field_of_study' => null,
                'gpa' => null,
                'achievements' => []
            ];
        }

        return $education;
    }

    private function extractSkills($content)
    {
        preg_match_all('/- (.*?): (.*?) \((\d+)\)\\n\\s+- \*\*Details:\*\* (.*?)\\n/', $content, $matches, PREG_SET_ORDER);
        $skills = [];

        foreach ($matches as $match) {
            $skills[] = [
                'name' => trim($match[1]),
                'type' => 'technical',
                'proficiency' => (int)$match[3],
                'proficiency_reason' => trim($match[4]),
                'years_experience' => 0
            ];
        }

        return $skills;
    }

    private function extractWorkExperiences($content)
    {
        preg_match_all('/- \*\*(.*?)\*\* at (.*?) \((.*?) - (.*?)\)\\n\\s+(.*?)\\n/', $content, $matches, PREG_SET_ORDER);
        $experiences = [];

        foreach ($matches as $match) {
            $experiences[] = [
                'position' => trim($match[1]),
                'company_name' => trim($match[2]),
                'start_date' => date('Y-m-d', strtotime($match[3])),
                'end_date' => $match[4] === 'Present' ? null : date('Y-m-d', strtotime($match[4])),
                'current_job' => $match[4] === 'Present' ? 1 : 0,
                'description' => trim($match[5]),
                'skills_used' => [],
                'achievements' => []
            ];
        }

        return $experiences;
    }

    private function extractProjects($content)
    {
        preg_match_all('/- \*\*(.*?)\*\*\\n\\s+(.*?)\\n/', $content, $matches, PREG_SET_ORDER);
        $projects = [];

        foreach ($matches as $match) {
            $projects[] = [
                'name' => trim($match[1]),
                'description' => trim($match[2]),
                'start_date' => null,
                'end_date' => null,
                'url' => null,
                'technologies_used' => [],
                'achievements' => []
            ];
        }

        return $projects;
    }
}
