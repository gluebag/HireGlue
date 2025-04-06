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
                'parameters' => ['job_data', 'user_data', 'rules', 'job_post'],
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
                'parameters' => ['job_data', 'user_data', 'rules', 'job_post'],
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
                'parameters' => ['document_content', 'document_type', 'rules'],
                'model' => 'gpt-4o',
                // 'max_tokens' => 1000,
                'max_tokens' => 6000,
                'temperature' => 0.3,
                'active' => true
            ],
            [
                // Job Post Analysis Prompt

                'name' => 'Job Post Analysis',
                'type' => 'analysis',

                'model' => 'gpt-4o',
                'max_tokens' => 16384,

                // 'model' => 'gpt-4o-mini',
                // 'max_tokens' => 16384,

                // 'model' => 'o3-mini',
                // 'max_tokens' => 100000,

                // 'model' => 'o1',
                // 'max_tokens' => 100000,

                // 'model' => 'o1-pro', // SUPER COSTLY BUT SUPER SMART
                // 'max_tokens' => 100000,
//
//                'model' => 'gpt-4.5-preview', // SUPER COSTLY BUT SUPER SMART
//                'max_tokens' => 16384,

                'temperature' => 0.2,

                'active' => true,

                'prompt_template' => <<<EOT
        You are an expert job application assistant that helps parse and structure job postings.
        Analyze the following job posting and extract the required information in the exact JSON structure provided below.

        Job Posting:
        {{job_content}}

        Your task is to extract and format the following information from this job posting:

        1. Basic information: job title, company name, job description
        2. Job details: location type, position level, job type
        3. Skills (both required and preferred)
        4. Experience requirements
        5. Education requirements
        6. Salary information if available

        Be thorough in your analysis. For skills, experiences, and education, use format compatible with our database:

        For skills:
        - Each skill needs: name, type (technical, soft, domain, tool, language, other), and level (1-5)
        - Infer skill types from context
        - Set appropriate skill levels (1=beginner, 5=expert) based on job requirements

        For experience:
        - Each experience needs: title, years (as a number), level (beginner, intermediate, advanced, expert), and description
        - Extract years from text (e.g., "3 years experience" → 3)
        - Infer experience level from years (0-2: beginner, 3-5: intermediate, 6-9: advanced, 10+: expert)

        For education:
        - Each education item needs: level (high_school, associate, bachelor, master, doctorate, certificate, other), field, is_required (boolean), min_gpa (if mentioned), description
        - Determine if education is required or preferred

        Use the exact JSON format below:

        ```json
        {
          "job_title": "",
          "company_name": "",
          "job_description": "",
          "job_location_type": "", // "remote", "in-office", "hybrid", or "unknown"
          "position_level": "", // "entry-level", "mid-level", "senior", "lead", "manager", "director", "executive", or "unknown"
          "job_type": "", // "full-time", "part-time", "contract", "internship", "freelance", or "unknown"
          "required_skills": [
            {
              "name": "",
              "type": "", // "technical", "soft", "domain", "tool", "language", "other"
              "level": 0 // 1-5 scale
            }
          ],
          "preferred_skills": [
            {
              "name": "",
              "type": "", // "technical", "soft", "domain", "tool", "language", "other"
              "level": 0 // 1-5 scale
            }
          ],
          "required_experience": [
            {
              "title": "",
              "years": 0,
              "level": "", // "beginner", "intermediate", "advanced", "expert"
              "description": ""
            }
          ],
          "required_education": [
            {
              "level": "", // "high_school", "associate", "bachelor", "master", "doctorate", "certificate", "other"
              "field": "",
              "is_required": true,
              "min_gpa": null, // number or null
              "description": ""
            }
          ],
          "salary_range_min": null, // number or null
          "salary_range_max": null // number or null
        }
        ```

        Output ONLY valid JSON that matches the structure above. Nothing else.
        EOT,
                'parameters' => [
                    'job_content',
//                    'job_content' => [
//                        'type' => 'string',
//                        'description' => 'HTML or markdown content of the job posting',
//                    ]
                ]
            ]
        ];

        foreach ($prompts as $prompt) {
            OpenAIPrompt::create($prompt);
        }
    }
}
