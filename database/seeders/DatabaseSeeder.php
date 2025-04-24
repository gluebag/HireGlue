<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RulesSeeder::class,
            OpenAIPromptsSeeder::class,
            UserSeeder::class,

            SkillSeeder::class,
            EducationSeeder::class,
        ]);
    }
}
