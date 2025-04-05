<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OpenAIPrompt;

class OpenAIPromptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prompts = [
            [
                'name' => 'resume_generation',
                'type' => 'resume',
                'prompt_template' => "You are an expert resume writer. Create a tailored resume for the following job posting based on the user's information. Follow all the rules provided.

Job Details:
{{job_data}}

User Information:
{{user_data}}

Resume Rules:
{{rules}}

The resume should be formatted in a clean, professional way. Use bullet points for achievements and responsibilities. Quantify achievements wherever possible. Tailor the content to match keywords from the job description. The resume should be between {{job_post.resume_min_words}} and {{job_post.resume_max_words}} words.",
                'parameters' => json_encode(['job_data', 'user_data', 'rules', 'job_post']),
                'model' => 'gpt-4o',
                // 'max_tokens' => 2000,
                'max_tokens' => 6000,
                'temperature' => 0.7,
                'active' => true
            ],
            [
                'name' => 'cover_letter_generation',
                'type' => 'cover_letter',
                'prompt_template' => "You are an expert cover letter writer. Create a compelling cover letter for the following job posting based on the user's information. Follow all the rules provided.

Job Details:
{{job_data}}

User Information:
{{user_data}}

Cover Letter Rules:
{{rules}}

The cover letter should be one page maximum, demonstrate enthusiasm for the company, and clearly connect the user's experience to the job requirements. Begin with a strong hook and address the hiring manager by name if available. The cover letter should be between {{job_post.cover_letter_min_words}} and {{job_post.cover_letter_max_words}} words.",
                'parameters' => json_encode(['job_data', 'user_data', 'rules', 'job_post']),
                'model' => 'gpt-4o',
                // 'max_tokens' => 2000,
                'max_tokens' => 6000,
                'temperature' => 0.7,
                'active' => true
            ],
            [
                'name' => 'rule_compliance_check',
                'type' => 'rule_check',
                'prompt_template' => "You are an expert at evaluating resumes and cover letters. Review the following document and determine if it complies with the provided rules. For each rule, provide a yes/no answer and a brief explanation.

Document:
{{document_content}}

Document Type: {{document_type}}

Rules to Check:
{{rules}}

For each rule, provide an assessment in the following format:
Rule: [Rule Name]
Compliant: [Yes/No]
Explanation: [Brief explanation]",
                'parameters' => json_encode(['document_content', 'document_type', 'rules']),
                'model' => 'gpt-4o',
                // 'max_tokens' => 1000,
                'max_tokens' => 6000,
                'temperature' => 0.3,
                'active' => true
            ],
        ];

        foreach ($prompts as $prompt) {
            OpenAIPrompt::create($prompt);
        }
    }
}
