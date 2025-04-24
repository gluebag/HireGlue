<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\WorkExperience;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WorkExperienceSeeder extends Seeder
{
    public function run()
    {
        $workHistory = [
            [
                'user_id' => 1,
                'company_name' => 'Attentiv Development (Consulting)',
                'position' => 'Lead Full-Stack Software Engineer Architect',
                'start_date' => '2012-06-01',
                'end_date' => null,
                'current_job' => 1,
                'description' => 'Launched in 2012 as a software engineering consultancy and contracting firm, delivering end-to-end solutions for startups and established businesses alike. Over the years, I\'ve spearheaded projects ranging from building web applications from scratch for early-stage companies to untangling and modernizing complex, legacy codebases—often transforming spaghetti code into scalable, maintainable systems. A standout achievement is co-founding QuoteVelocity, where I designed, architected, and coded the foundational CRM and front-facing CMS. My work has consistently added value through features like Bugsnag error tracking, auto-deployment pipelines, Pingdom alerts, and CI/CD workflows.',
                'skills_used' => [
                    'technical-1: Software Architecture',
                    'technical-2: Legacy Code Refactoring',
                    'technical-3: Full-Stack Development',
                    'technical-4: Database Management',
                    'technical-5: Microservices Architecture',
                    'technical-6: API Development',
                    'technical-7: CI/CD Implementation',
                    'technical-8: Cloud Infrastructure (AWS, GCP)',
                    'technical-9: DevOps Practices',
                    'technical-10: Agile Methodologies',
                    'technical-11: Front-End Frameworks (React, Vue)',

                    'soft-1: Technical Leadership',
                    'soft-2: Strategic Problem-Solving',
                    'soft-3: Client Relationship Management',
                    'soft-4: Project Management',
                    'soft-5: Team Collaboration',
                    'soft-6: Remote Work Adaptability',
                    'soft-7: Team Management',
                    'soft-8: Communication Skills',
                    'soft-9: Time Management',
                    'soft-10: Adaptability',
                    'soft-11: Critical Thinking',
                ],
                'achievements' => [
                    'Evolved a teenage venture into Attentiv Development by 2012, growing it into a highly-reviewed business that’s since contributed to the success of over 11 startups and established companies.',
                    'Worked in a variety of industries including telecom, finance, healthcare, and e-commerce, delivering tailored solutions that meet the unique needs of each sector.',
                    'Created hiring and onboarding processes that have been adopted by multiple companies, streamlining the recruitment process and improving team integration.',
                    'Reduced technical debt for clients by recoding complex features into manageable, future-proof solutions.',
                    'Implemented CI/CD pipelines and automated testing frameworks, significantly reducing deployment times and improving code quality.',
                    'Enhanced client platforms with advanced tooling, improving reliability and operational efficiency across multiple projects.',
                    'Successfully led the development of a telecom messaging platform capable of handling 30 million messages per day, significantly enhancing client campaign ROI by 3x.',
                    'Architected QuoteVelocity’s core CRM and CMS, contributing to its $150 million valuation and ability to handle high-volume marketing campaigns.',
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'company_name' => 'QuoteVelocity',
                'position' => 'Lead Full-Stack Software Engineer Architect (Contract & Consultant)',
                'start_date' => '2020-01-00',
                'end_date' => null,
                'current_job' => 1,
                'description' => 'Drove the technical vision and implementation for QuoteVelocity, a lead generation company now valued at $150 million. Designed and coded the core CRM and CMS, integrating advanced lead qualification tools and real-time analytics to power high-stakes marketing campaigns. Used PHP (Laravel), Python, and cloud-native AWS solutions to ensure the platform could manage massive traffic while providing precise high-quality leads to enterprise clients, including billion-dollar health insurance firms. Stay onboard as a consultant to ensure the platform remains cutting-edge and continues to meet the evolving needs of our clients.',
                'skills_used' => [
                    'technical-1: System Architecture',
                    'technical-2: Real-Time Analytics',
                    'technical-3: Data Pipeline Management',
                    'technical-4: Cloud Services (AWS)',
                    'technical-5: API Development',
                    'technical-6: Database Design',
                    'technical-7: Full-Stack Engineering',

                    'soft-1: Business Strategy Alignment',
                    'soft-2: Cross-Functional Collaboration',
                    'soft-3: Strategic Problem-Solving',
                    'soft-4: Client Engagement',
                    'soft-5: Project Management',
                    'soft-6: Team Management',
                    'soft-7: Remote Work Adaptability',
                    'soft-8: Team Collaboration',
                    'soft-9: Communication Skills',
                    'soft-10: Time Management',
                    'soft-11: Adaptability',
                    'soft-12: Critical Thinking',
                    'soft-13: Technical Leadership',
                ],
                'achievements' => [
                    'Architected a platform that confidently tracks and automates $5M+ in monthly marketing spend with consistent performance, which has been used by billion-dollar health insurance companies.',
                    //resulting in to QuoteVelocity’s $150 million valuation.',
                    'Built senior software engineer hiring tests that remain essential to operations and developer recruitment.',
                    'Designed and implemented a custom-built lead qualification system that increased lead conversion rates by 30%.',
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'company_name' => 'Attentiv Development (Project: CNTRL.ai)',
                'position' => 'Lead Full-Stack Software Engineer Architect (Contract & Consultant)',
                'start_date' => '2023-08-01',
                'end_date' => '2023-12-01',
                'current_job' => 0,
                'description' => 'Contracted to lead the full-stack redevelopment of CNTRL.ai, an AI/ML workflow SaaS platform. Designed a novel UI and recoded front-end/back-end systems integrating leading LLM APIs (ChatGPT, Claude, etc.), abstracting complex AI/ML processes into intuitive tools for end-users.',
                'skills_used' => [
                    'technical-1: Full-Stack Development',
                    'technical-2: AI/ML Integration',
                    'technical-3: API Integration',
                    'technical-4: UI/UX Design',
                    'soft-1: Project Management',
                    'soft-2: Team Management',
                    'soft-3: Remote Work Adaptability',
                    'soft-4: Team Collaboration',
                ],
                'achievements' => [
                    'Successfully re-architected the AI/ML workflow platform for improved simplicity and performance.',
                    'Integrated advanced LLM APIs, streamlining complex processes into user-friendly tools.',
                    'Led the complete user interface redesign, enhancing user experience.',
                    'Delivered the project on time and within budget, exceeding client expectations.',
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'company_name' => 'Attentiv Development (Project: BoilData)',
                'position' => 'Lead Full-Stack Engineer',
                'start_date' => '2020-08-01',
                'end_date' => '2025-03-01',
                'current_job' => 0,
                'description' => 'Spearheaded the development of BoilData, a high-volume telecom messaging SaaS platform powered by a custom-built microservices architecture paired with RaspberryPi\'s with custom-built firmware to handle the telecom hardware. The platform was designed to handle 30 million outgoing text messages a day and was built to be highly scalable and fault-tolerant.',
                'skills_used' => [
                    'technical-1: System Architecture',
                    'technical-2: Real-Time Analytics',
                    'technical-3: Full-Stack Development',
                    'technical-4: Database Optimization (MySQL, Redis)',
                    'soft-1: Technical Leadership',
                    'soft-2: Strategic Problem-Solving',
                    'soft-3: Client Relationship Management',
                    'soft-4: Project Management',
                ],
                'achievements' => [
                    'Delivered in under 6 months a highly-scaled MVP with usage based billing model resulting in $7.4M in company revenue its first year, surpassing industry startup benchmarks.',
                    'Enhanced client\'s campaign ROI by 3x across the board by implementing novel AB testing and analytics features. Which resulted in enormous client demand and the company to "waitlist" new clients.',
                    'Was able to reduce the average cost per message from $0.01 to $0.0001 by implementing a custom-built RaspberryPi firmware that allowed the company to send messages over complicated telecom hardware instead of through telecom providers. This resulted in a 10x increase in profit margins.',
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'company_name' => 'Edgelist - Stock-Trading iPhone App',
                'position' => 'Lead Full-Stack Engineer Architect',
                'start_date' => '2023-03-00',
                'end_date' => '2023-08-00',
                'current_job' => 0,
                'description' => 'Designed and built a stock-trading iPhone app that was acquired by a major financial institution. The app was built using Flutter and integrated with various financial APIs to provide real-time stock data and trading capabilities to professional traders. The app was designed to be highly secure and scalable, with a focus on user experience and performance.',
                'skills_used' => [
                    'technical-1: Mobile App Development',
                    'technical-2: API Integration',
                    'technical-3: Security Best Practices',

                    'soft-1: Technical Leadership',
                    'soft-2: Project Management',
                    'soft-3: Team Management',
                    'soft-4: Remote Work Adaptability',
                    'soft-5: Team Collaboration',
                ],
                'achievements' => [
                    'Coded a high-frequency data fetching pipeline that took 6 different financial APIs data sources and combined them into a single source of truth for the app.',
                    'Implemented a custom-built algorithm that helped professional traders maintain a 90% success rate in their trades.',
                    'Followed all company security protocols and best practices to ensure the app was secure and compliant with stock market industry standards.',
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // reverse the order of the work history array so its imported in the correct order
        $workHistory = array_reverse($workHistory);

        foreach ($workHistory as $work) {
            $skills = $work['skills_used'];
            unset($work['skills_used']);

            // build and convert each skill in skills_used to be key => value array where:
            // - the key is the skill name extracted from explode(': ') and the value is the skill type
            // e.g. "technical-1: Software Architecture" => ["Software Architecture" => "technical"]
            // OR
            // e.g. "soft-1: Technical Leadership" => ["Technical Leadership" => "soft"]

            // ok now, insert the work experience into the database and get the ID
            $work = WorkExperience::updateOrCreate([
                'user_id' => $work['user_id'],
                'company_name' => $work['company_name'],
                'position' => $work['position'],
            ], [
                'start_date' => $work['start_date'],
                'end_date' => $work['end_date'],
                'current_job' => $work['current_job'],
                'description' => $work['description'],
                'created_at' => Carbon::createFromDate(2025, 4, 1),
                'updated_at' => Carbon::createFromDate(2025, 4, 1),
            ]);
            $workId = $work->id;

            $inserted = 0;
            collect($skills)
                ->each(function ($skill) use ($work, &$inserted) {
                    $skillParts = explode(': ', $skill);
                    if (count($skillParts) < 2) {
                        Log::error('Invalid skill format: ' . $skill);
                        $this->command->error('Invalid skill format: ' . $skill);
                        return null;
                    }
                    $skillType = explode('-', trim($skillParts[0]))[0];
                    $skillName = trim($skillParts[1]);

                    // calculate $yearsOfExperience based on the start and end date of the work experience
                    $startDate = Carbon::parse($work['start_date']);
                    $endDate = $work['end_date'] ? Carbon::parse($work['end_date']) : Carbon::now();
                    $yearsOfExperience = $startDate->diffInYears($endDate);
                    // round it UP always to the nearest whole number (but if its < 1, set it to 1)
                    $yearsOfExperience = max(1, ceil($yearsOfExperience));

                    $newSkill = [
                        'skillable_id' => $work->id,
                        'skillable_type' => WorkExperience::class,

                        'name' => $skillName,
                        'type' => $skillType,

                        'years_experience' => $yearsOfExperience,

                        'proficiency' => null, // User will fill out manually in Nova
                        'proficiency_reason_type' => Skill::PROFICIENCY_REASON_TYPE_WORK,
                        'proficiency_reason' => null, // user will fill out manually in Nova

                        // since its a backup, we can set the created_at and updated_at to a fixed date in the past
                        'created_at' => Carbon::createFromDate(2025, 4, 1),
                        'updated_at' => Carbon::createFromDate(2025, 4, 1),
                    ];

                    Skill::updateOrCreate([
                        'skillable_id' => $work->id,
                        'skillable_type' => WorkExperience::class,
                        'name' => $skillName,
                    ], $newSkill);

                    $inserted++;
                });


            Log::info('WorkExperienceSeeder: work experience ' . $workId . ' inserted successfully with ' . $inserted . ' skills');
            $this->command->info('WorkExperienceSeeder: work experience ' . $workId . ' inserted successfully with ' . $inserted . ' skills');
        }

        if (count($workHistory) === 0) {
            $this->command->error('No work experience found in backup file');
            Log::error('WorkExperienceSeeder: No work experience found in backup file');
        } else {
            $this->command->info('WorkExperienceSeeder: work experience seeded successfully with new polymorphic structure');
            Log::info('WorkExperienceSeeder: work experience seeded successfully with new polymorphic structure');
        }
    }
}
