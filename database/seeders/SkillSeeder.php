<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $skillsFromBackup = json_decode(file_get_contents(base_path('notes/table-backups/user-skills-backup.json')), true);

        // add user_id 1 and created_at/updated_at timestamps
        foreach ($skillsFromBackup as &$skill) {
            $skill['user_id'] = 1;
            $skill['created_at'] = now();
            $skill['updated_at'] = now();
        }

        // todo: convert skills to new polymorphic structure
        $this->command->error('SkillSeeder: skills seeder needs converted to new polymorphic structure');
        \Log::error('SkillSeeder: skills seeder needs converted to new polymorphic structure');
        dd('NOT IMPLEMENTED YET, TODO!!!');


        DB::table('skills')->insert($skillsFromBackup);
    }
}
