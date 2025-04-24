<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $skillsFromBackup = json_decode(file_get_contents(base_path('notes/table-backups/user-skills-backup.json')), true);
        if(empty($skillsFromBackup) || empty($skillsFromBackup = $skillsFromBackup['skills'])) {
            $this->command->error('No skills found in backup file');
            Log::error('SkillSeeder: No skills found in backup file');
            return;
        }
        $insertSkills = [];
        foreach ($skillsFromBackup as $skill) {
            $insertSkills[] = [
                'skillable_id' => 1,
                'skillable_type' => User::class,

                'name' => $skill['name'],
                'type' => $skill['type'],

                'years_experience' => $skill['years_experience'],

                'proficiency' => $skill['proficiency'],
                'proficiency_reason_type' => Skill::PROFICIENCY_REASON_TYPE_LOCAL_CODE,
                'proficiency_reason' => $skill['proficiency_reason'],

                // since its a backup, we can set the created_at and updated_at to a fixed date in the past
                'created_at' => Carbon::createFromDate(2025, 4, 1),
                'updated_at' => Carbon::createFromDate(2025, 4, 1),
            ];
        }

        DB::table('skills')->insert($insertSkills);

        Log::info('SkillSeeder: skills seeded successfully with new polymorphic structure');
    }
}
