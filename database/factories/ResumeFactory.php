<?php

namespace Database\Factories;

use App\Models\Resume;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResumeFactory extends Factory
{
    protected $model = Resume::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_post_id' => JobPost::factory(),
            'content' => fake()->paragraphs(5, true),
            'file_path' => null,
            'word_count' => fake()->numberBetween(400, 900),
            'skills_included' => json_encode(['JavaScript', 'PHP', 'React']),
            'experiences_included' => json_encode(['Software Engineer', 'Web Developer']),
            'education_included' => json_encode(['Bachelor of Science']),
            'projects_included' => json_encode(['Portfolio Website', 'E-commerce Platform']),
            'rule_compliance' => json_encode(['rule1' => ['passed' => true]]),
            'generation_metadata' => json_encode(['model' => 'gpt-4', 'usage' => ['total_tokens' => 500]])
        ];
    }
}
