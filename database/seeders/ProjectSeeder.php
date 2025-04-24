<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run()
    {

        // todo: convert skills to new polymorphic structure
        $this->command->error('ProjectSeeder: skills seeder needs converted to new polymorphic structure');
        \Log::error('ProjectSeeder: skills seeder needs converted to new polymorphic structure');
        dd('NOT IMPLEMENTED YET, TODO!!!');

        DB::table('projects')->insert([
            [
                'user_id' => 1,
                'name' => 'PopSheets.com and Marketing Automation',
                'description' => 'Built PopSheets.com, a website for hosting free piano sheet music PDFs, and developed a PHP script for marketing automation.',
                'start_date' => null,
                'end_date' => null,
                'url' => null,
                'technologies_used' => json_encode(['PHP', 'HTML', 'CSS', 'Javascript']),
                'achievements' => json_encode([
                    'Successfully built and launched my first website at age 14, learning multiple programming languages.',
                    'Developed an early interest in automation and digital marketing by creating a PHP script to boost site traffic via YouTube.',
                    'Earned my first $17 from Google Ads, fueling my lifelong pursuit of technology.'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
