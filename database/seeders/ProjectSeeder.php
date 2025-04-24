<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Skill;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projectData = [
            'user_id' => 1,
            'name' => 'PopSheets.com and Marketing Automation',
            'description' => 'Built PopSheets.com, a website for hosting free piano sheet music PDFs, and developed a PHP script for marketing automation.',
            'start_date' => '2008-08-01',
            'end_date' => '2010-01-31',
            'url' => null,
            'achievements' => [
                'Successfully built and launched my first website at age 14, learning multiple programming languages.',
                'Developed an early interest in automation and digital marketing by creating a PHP script to boost site traffic via YouTube.',
                'Earned my first $17 from Google Ads, fueling my lifelong pursuit of technology.'
            ],
            'created_at' => Carbon::createFromDate(2025, 4, 1),
            'updated_at' => Carbon::createFromDate(2025, 4, 1),
        ];

        $skillsUsed = [
            'technical-1: PHP',
            'technical-2: HTML',
            'technical-3: CSS',
            'technical-4: JavaScript',
            'soft-1: Self-Learning',
            'soft-2: Problem-Solving',
            'soft-3: Initiative',
        ];

        $project = Project::updateOrCreate([
            'user_id' => $projectData['user_id'],
            'name' => $projectData['name'],
        ], $projectData);

        foreach ($skillsUsed as $skill) {
            [$type, $name] = explode(': ', $skill);
            $skillType = explode('-', $type)[0];

            Skill::updateOrCreate([
                'skillable_id' => $project->id,
                'skillable_type' => Project::class,
                'name' => $name,
            ], [
                'type' => $skillType,
                'years_experience' => 2,
                'proficiency' => null,
                'proficiency_reason_type' => Skill::PROFICIENCY_REASON_TYPE_PROJECT,
                'proficiency_reason' => null,
                'created_at' => Carbon::createFromDate(2025, 4, 1),
                'updated_at' => Carbon::createFromDate(2025, 4, 1),
            ]);
        }

        Log::info('ProjectSeeder: project and associated skills seeded successfully with new polymorphic structure');
    }
}
