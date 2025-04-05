<?php

namespace Database\Factories;

use App\Models\CoverLetter;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoverLetterFactory extends Factory
{
    protected $model = CoverLetter::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_post_id' => JobPost::factory(),
            'content' => fake()->paragraphs(3, true),
            'file_path' => null,
            'word_count' => fake()->numberBetween(400, 750),
            'rule_compliance' => json_encode(['rule1' => ['passed' => true]]),
            'generation_metadata' => json_encode(['model' => 'gpt-4', 'usage' => ['total_tokens' => 400]])
        ];
    }
}
