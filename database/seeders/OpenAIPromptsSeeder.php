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
                'parameters' => ['job_data', 'user_data-*', 'rules', 'job_post'],
                'model' => 'gpt-4o',
                'max_tokens' => 0,
                'temperature' => 0.7,
                'active' => true,
                'system_message' => 'You are a career coach assistant with over 20 years of experience helping job seekers land roles in the tech industry. You specialize at evaluating resumes and cover letters.',
                'prompt_template' => <<<PROMPT
You are an expert resume writer. Create a tailored resume for the following job posting based on the user's information. Follow all the rules provided.

Job Details:
{{job_data}}

User Information:
{{user_data}}

Resume Rules:
{{rules}}

The resume should be formatted in a clean, professional way. Use bullet points for achievements and responsibilities. Quantify achievements wherever possible. Tailor the content to match keywords from the job description. The resume should be between {{job_post.resume_min_words}} and {{job_post.resume_max_words}} words.
PROMPT,
            ],
            [
                'name' => 'cover_letter_generation',
                'type' => 'cover_letter',
                'parameters' => ['job_data', 'user_data', 'rules', 'job_post'],
                'model' => 'gpt-4o',
                'max_tokens' => 0,
                'temperature' => 0.7,
                'active' => true,
                'system_message' => 'You are a career coach assistant with over 20 years of experience helping job seekers land roles in the tech industry. You specialize at evaluating resumes and cover letters.',
                'prompt_template' => <<<PROMPT
You are an expert cover letter writer. Create a compelling cover letter for the following job posting based on the user's information. Follow all the rules provided.

Job Details:
{{job_data}}

User Information:
{{user_data}}

Cover Letter Rules:
{{rules}}

The cover letter should be one page maximum, demonstrate enthusiasm for the company, and clearly connect the user's experience to the job requirements. Begin with a strong hook and address the hiring manager by name if available. The cover letter should be between {{job_post.cover_letter_min_words}} and {{job_post.cover_letter_max_words}} words.
PROMPT,
            ],
            [
                'name' => 'rule_compliance_check',
                'type' => 'rule_check',
                'parameters' => ['document_content', 'document_type', 'rules'],
                'model' => 'gpt-4o',
                'max_tokens' => 0,
                'temperature' => 0.7,
                'active' => true,
                'system_message' => 'You are a career coach assistant with over 20 years of experience helping job seekers land roles in the tech industry. You specialize at evaluating resumes and cover letters.',
                'prompt_template' => <<<PROMPT
"You are an expert at evaluating resumes and cover letters. Review the following document and determine if it complies with the provided rules. For each rule, provide a yes/no answer and a brief explanation.

Document:
{{document_content}}

Document Type: {{document_type}}

Rules to Check:
{{rules}}

For each rule, provide an assessment in the following format:
Rule: [Rule Name]
Compliant: [Yes/No]
Explanation: [Brief explanation]
PROMPT,
            ],
            [
                // Job Post Analysis Prompt
                'name' => 'job_post_analysis_20250409',
                'type' => 'analysis',

                'model' => 'gpt-4o',
                'max_tokens' => 0,
                'temperature' => 0.7,
                'active' => true,
                'system_message' => 'You are a career coach assistant with over 20 years of experience helping job seekers land roles in the tech industry. You are an expert at analyzing, parsing, and extracting structured data from job postings ',
                'prompt_template' => <<<EOT
I'm a job seeker applying for a {{target_role}} in {{company}}

I will share the job description/content for this role.

Based on this job description, your task is to anaylze, extract, refine and format the following required information from this job posting in the exact JSON structure provided below:

1. Basic information: job title, company name, job description
2. Job details: location type, position level, job type, posted date (if available)
3. Hiring manager information (if available)
4. Recruiter names (if available)
5. Internal job codes (if available)
6. Biggest challenge: What is the biggest challenge someone in this position would face day-to-day? Give me the root cause of this issue.
7. Skills (both required and preferred). Add any additional determined skills that apply to the job position that may not have been explicity mentioned.
8. Ideal Skills: A sub-set of skill names from the overall list of skills extracted that are required to perform exceptionally well and be a rockstar canidate in this position.
9. Experience requirements
10. Education requirements
11. Salary information if available

Be thorough in your analysis. For biggest challenge, skills, ideal skills, required experience, and education, use a format compatible with our database:

For skills & ideal skills
- Each skill needs: name (Typically short and commonly used in the industry. e.g.: "Javascript" or "Front-End Development"), type (technical, soft, domain, tool, language, other), and determined proficiency level (1-10 scale) and infer/proficiency reason (How and why you inferred the skill and why you scored it that level. keep it short and concise)
- Infer skill types from context
- Set appropriate skill levels (1=Novice, 10=expert) based on job requirements following this proficiency scale:
  - 1-2 (Novice): Basic theoretical knowledge, little to no practical experience.
  - 3-4 (Beginner): Some hands-on experience, can handle simple tasks with guidance.
  - 5-6 (Intermediate): Solid working knowledge, can work independently on moderately complex tasks.
  - 7-8 (Advanced): Expert-level skills, can lead projects or solve complex problems.
  - 9-10 (Master/Expert): World-class expertise, can innovate or teach others

For experience:
- Each experience needs: title, years (as a number), level (Novice, beginner, intermediate, advanced, master, expert), and description
- Extract years from text (e.g., "3 years experience" → 3)
- Infer experience level and reason (factoring in years if possible) following same proficiency scale as skills.

For education:
- Each education item needs: level (high_school, associate, bachelor, master, doctorate, certificate, other), field, is_required_strict (boolean; true if mentioned and determined that it is absolutely necessary to apply OR false if preferred but sufficient work experience can suffice), min_gpa (if mentioned), description

Use the exact JSON format below:

```json
{
  "metadata": {
     "job_id": "", // (string or null) e.g. "1234567890" (if available)
     "team": "", // (string or null) e.g. "Global Partner Engineering - Data, Analytics and Databases"
     "locations": [], // (array of strings) e.g. ["Los Angeles, CA", "Remote"],
     "hiring_manager": "", // (string or null) e.g. "J. Doe, Senior Manager of Marketing" (if available)
     "recruiter": "", //  (string or null)  e.g. "Jane S., Talent Acquisition Specialist" (if available)
     "posted_date": "", // (string or null) e.g. "2023-10-01" (if available)
  },
  "job_title": "",
  "job_description": "",
  "apply_url": "", // (string or null) e.g. "https://www.netflix.com/jobs/job/1234567890",
  "job_location_type": "", // "remote", "in-office", "hybrid", or "unknown"
  "position_level": "", // "entry-level", "mid-level", "senior", "lead", "manager", "director", "executive", or "unknown"
  "job_type": "", // "full-time", "part-time", "contract", "internship", "freelance", or "unknown"
  "biggest_challenge": {
  	"summary": "",  // e.g. "The biggest challenge for someone in the marketing manager role at Netflix would be defining and executing the marketing strategy for the external games portfolio, given the need to position, budget, innovate, and identify the right opportunities for the games.",
  	"root_cause": "", // e.g. "The dynamic and competitive nature of the gaming industry, coupled with the unique demands of marketing games within a streaming entertainment platform."
  },
  "required_skills": [
    {
      "name": "",
      "type": "", // "technical", "soft", "domain", "tool", "language", "other"
      "proficiency": 0, // 1-10 proficiency scale
      "reason": "", // e.g. "Job mentions '[infer_reason_phrase]' which typically involves [x] in [skill_name_here]"
    }
  ],
  "preferred_skills": [
    {
      "name": "",
      "type": "", // "technical", "soft", "domain", "tool", "language", "other"
      "proficiency": 0, // 1-10 proficiency scale
      "reason": "", // e.g. "Job involves '[infer_reason_phrase]' which typically involves [x] in [skill_name_here]"
    }
  ],
  "ideal_skills": [
    {
      "name": "",
      "type": "", // "technical", "soft", "domain", "tool", "language", "other"
      "proficiency": 0, // 1-10 proficiency scale (usually high number)
      "reason": "", // e.g. "'[infer_reason_phrase]' perfectly compliments [biggest_challenge_and_or_root_cause] especially pertaining to this role/team at [company_name_here]."
    }
  ],
  "required_experience": [
    {
      "title": "",
      "description": "",
      "skill_names": [], // array of skill names that are relevant to this experience (if applicable) e.g. ["Python", "Data Analysis"]
      "years": null, // (number or null) how many years experience required (if mentioned)
      "proficiency": "", // "novice", "beginner", "intermediate", "advanced", "master", "expert",
      "reason": "", // e.g. "Job mentions '[infer_reason_phrase]' ' therefore [short_sentence_or_sentences_why]"
    }
  ],
  "required_education": [
    {
      "level": "", // "high_school", "associate", "bachelor", "master", "doctorate", "certificate", "other"
      "field": "",
      "is_required_strict": true,
      "min_gpa": null, // number or null
      "description": ""
    }
  ],
  "salary_range_min": null, // number or null
  "salary_range_max": null // number or null
}
```

Output ONLY valid JSON that matches the structure above. Nothing else.

Here's an example list of my own skills ive scored and gave reasons for:

{{my_skills}}

Here's the job post description/content for the {{target_role}}:

{{job_content}}
EOT,
                'parameters' => [
                    'job_content',
                    'target_role',
                    'my_skills',
                    'company_name', // e.g. "Netflix"
                ]
            ]
        ];

        foreach ($prompts as $prompt) {
            OpenAIPrompt::create($prompt);
        }
    }
}
