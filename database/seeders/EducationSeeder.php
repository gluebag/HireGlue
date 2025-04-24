<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationSeeder extends Seeder
{
    public function run()
    {
        DB::table('education')->insert([
            [
                'user_id' => 1,
                'institution' => 'New Philadelphia High School',
                'degree' => 'High School Diploma',
                'field_of_study' => 'General Studies with a Focus on Technology and Entrepreneurship',
                'start_date' => '2006-08-01',
                'end_date' => '2010-06-01',
                'current' => 0,
                'gpa' => null,
                'achievements' => json_encode([
                    ['type' => 'line-item-achievement', 'fields' => ['description' => 'Began self-teaching HTML, CSS, and PHP at age 14 (2005) with minimal resources.']],
                    ['type' => 'line-item-achievement', 'fields' => ['description' => 'Launched PopSheets.com, a website hosting free piano sheet music PDFs sourced from torrents and download sites.']],
                    ['type' => 'line-item-achievement', 'fields' => ['description' => 'Jumpstarted a passion for digital marketing by experimenting with Google Ads to monetize PopSheets.com.']]
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'institution' => 'Self-Taught Programmer',
                'degree' => 'Continuous Self-Directed Learning',
                'field_of_study' => 'Software Engineering, Cloud Architecture, Security, Scalability, and Database Systems',
                'start_date' => '2005-01-01',
                'end_date' => null,
                'current' => 1,
                'gpa' => null,
                'achievements' => json_encode([
                    ['type' => 'line-item-achievement', 'fields' => ['description' => 'Over 18 years of self-directed learning, mastering programming languages and cloud platforms.']],
                    ['type' => 'line-item-achievement', 'fields' => ['description' => 'Built production-grade software by age 16, earning praise from online developer and marketing communities.']]
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
} 