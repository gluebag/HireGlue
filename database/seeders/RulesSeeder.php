<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rule;

class RulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resume Rules
        $resumeRules = [
            [
                'name' => 'One or Two Pages Maximum',
                'description' => 'Resume should be one page for less than 10 years of experience, two pages maximum for more experience.',
                'type' => 'resume',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 9,
                'validation_logic' => json_encode(['type' => 'page_count', 'min' => 1, 'max' => 2])
            ],
            [
                'name' => 'Quantify Achievements',
                'description' => 'Use numbers and percentages to quantify achievements wherever possible (e.g., "Increased sales by 25%").',
                'type' => 'resume',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 8,
                'validation_logic' => null
            ],
            [
                'name' => 'Tailor Keywords to Job Description',
                'description' => 'Include keywords from the job description to pass ATS screening.',
                'type' => 'resume',
                'source' => 'Ex-Google Recruiter (8 Secrets)',
                'importance' => 10,
                'validation_logic' => null
            ],
            [
                'name' => 'Action Verbs Only',
                'description' => 'Use strong action verbs to start bullet points; avoid passive language.',
                'type' => 'resume',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 7,
                'validation_logic' => null
            ],
            [
                'name' => 'No Personal Pronouns',
                'description' => 'Avoid using "I", "me", or "my" in resume content.',
                'type' => 'resume',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 6,
                'validation_logic' => null
            ],
        ];

        // Cover Letter Rules
        $coverLetterRules = [
            [
                'name' => 'Address Hiring Manager by Name',
                'description' => 'Research and address the hiring manager by name whenever possible.',
                'type' => 'cover_letter',
                'source' => 'Writing Amazing Cover Letters (3 Golden Rules)',
                'importance' => 8,
                'validation_logic' => null
            ],
            [
                'name' => 'Show Enthusiasm for Company',
                'description' => 'Demonstrate knowledge of and enthusiasm for the company\'s mission, values, and work.',
                'type' => 'cover_letter',
                'source' => 'Writing Amazing Cover Letters (3 Golden Rules)',
                'importance' => 9,
                'validation_logic' => null
            ],
            [
                'name' => 'One Page Maximum',
                'description' => 'Cover letter should never exceed one page.',
                'type' => 'cover_letter',
                'source' => 'Cover Letter Mistakes to Avoid',
                'importance' => 10,
                'validation_logic' => json_encode(['type' => 'page_count', 'max' => 1])
            ],
            [
                'name' => 'Connect Experience to Job Requirements',
                'description' => 'Explicitly connect your past experience to the specific requirements in the job posting.',
                'type' => 'cover_letter',
                'source' => 'Writing Amazing Cover Letters (3 Golden Rules)',
                'importance' => 9,
                'validation_logic' => null
            ],
            [
                'name' => 'Strong Opening Hook',
                'description' => 'Begin with a compelling hook that grabs attention and shows your value.',
                'type' => 'cover_letter',
                'source' => 'Writing Amazing Cover Letters (3 Golden Rules)',
                'importance' => 7,
                'validation_logic' => null
            ],
        ];

        // General Rules for Both
        $generalRules = [
            [
                'name' => 'No Spelling or Grammar Errors',
                'description' => 'Documents must be free of all spelling and grammar errors.',
                'type' => 'both',
                'source' => 'Ex-Google Recruiter (8 Secrets)',
                'importance' => 10,
                'validation_logic' => null
            ],
            [
                'name' => 'Consistent Formatting',
                'description' => 'Maintain consistent formatting, fonts, spacing, and bullet styles throughout.',
                'type' => 'both',
                'source' => 'Writing Incredible Resumes (5 Golden Rules)',
                'importance' => 7,
                'validation_logic' => null
            ],
        ];

        foreach (array_merge($resumeRules, $coverLetterRules, $generalRules) as $rule) {
            Rule::create($rule);
        }
    }
}
