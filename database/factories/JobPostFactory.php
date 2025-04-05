<?php

namespace Database\Factories;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobPost::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => fake()->company(),
            'job_title' => fake()->jobTitle(),
            'job_description' => fake()->paragraphs(3, true),
            'job_post_url' => fake()->url(),
            'job_post_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'job_location_type' => fake()->randomElement(['remote', 'in-office', 'hybrid']),
            'required_skills' => json_encode([$this->randomSkill(), $this->randomSkill(), $this->randomSkill()]),
            'preferred_skills' => json_encode([$this->randomSkill(), $this->randomSkill()]),
            'required_experience' => json_encode(['years' => fake()->numberBetween(1, 10), 'description' => fake()->sentence()]),
            'required_education' => json_encode(['level' => fake()->randomElement(['Bachelors', 'Masters', 'PhD']), 'field' => fake()->word()]),
            'resume_min_words' => 450,
            'resume_max_words' => 850,
            'cover_letter_min_words' => 450,
            'cover_letter_max_words' => 750,
            'resume_min_pages' => 1,
            'resume_max_pages' => 2,
            'cover_letter_min_pages' => 1,
            'cover_letter_max_pages' => 1,
            'things_i_like' => fake()->paragraph(),
            'things_i_dislike' => fake()->paragraph(),
            'things_i_like_about_company' => fake()->paragraph(),
            'things_i_dislike_about_company' => fake()->paragraph(),
            'open_to_travel' => fake()->boolean(),
            'salary_range_min' => fake()->numberBetween(50000, 80000),
            'salary_range_max' => fake()->numberBetween(90000, 150000),
            'min_acceptable_salary' => fake()->numberBetween(45000, 70000),
            'position_level' => fake()->randomElement(['entry-level', 'mid-level', 'senior', 'lead', 'manager', 'director', 'executive']),
            'job_type' => fake()->randomElement(['full-time', 'part-time', 'contract', 'internship', 'freelance']),
            'ideal_start_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'position_preference' => fake()->numberBetween(1, 5),
            'first_time_applying' => fake()->boolean(80),
        ];
    }

    /**
     * Generate a random skill name
     */
    private function randomSkill(): string
    {
        return fake()->randomElement([
            'JavaScript', 'PHP', 'Python', 'Java', 'C#',
            'React', 'Vue', 'Angular', 'Laravel', 'Django',
            'Node.js', 'AWS', 'Docker', 'Kubernetes', 'SQL',
            'NoSQL', 'Product Management', 'Agile', 'Scrum', 'UI/UX',
            'SEO', 'Content Marketing', 'Data Analysis', 'Machine Learning'
        ]);
    }
}
