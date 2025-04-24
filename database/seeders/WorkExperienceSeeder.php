<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'description' => 'Launched in 2012 as a software engineering consultancy and contracting firm, delivering end-to-end solutions for startups and established businesses alike. Over the years, I’ve spearheaded projects ranging from building web applications from scratch for early-stage companies to untangling and modernizing complex, legacy codebases—often transforming spaghetti code into scalable, maintainable systems. A standout achievement is co-founding QuoteVelocity, where I designed, architected, and coded the foundational CRM and front-facing CMS. My work has consistently added value through features like Bugsnag error tracking, auto-deployment pipelines, Pingdom alerts, and CI/CD workflows.',
                'skills_used' => [
                    'Hard-1: Software Architecture',
                    'Hard-2: Legacy Code Refactoring',
                    'Hard-3: Full-Stack Development',
                    'Hard-4: Database Management',
                    'Hard-5: Microservices Architecture',
                    'Hard-6: API Development',
                    'Hard-7: CI/CD Implementation',
                    'Hard-8: Cloud Infrastructure (AWS, GCP)',
                    'Hard-9: DevOps Practices',
                    'Hard-10: Agile Methodologies',
                    'Hard-11: Front-End Frameworks (React, Vue)',

                    'Soft-1: Technical Leadership',
                    'Soft-2: Strategic Problem-Solving',
                    'Soft-3: Client Relationship Management',
                    'Soft-4: Project Management',
                    'Soft-5: Team Collaboration',
                    'Soft-6: Remote Work Adaptability',
                    'Soft-7: Team Management',
                    'Soft-8: Communication Skills',
                    'Soft-9: Time Management',
                    'Soft-10: Adaptability',
                    'Soft-11: Critical Thinking',
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
                    'Hard-1: System Architecture',
                    'Hard-2: Real-Time Analytics',
                    'Hard-3: Data Pipeline Management',
                    'Hard-4: Cloud Services (AWS)',
                    'Hard-5: API Development',
                    'Hard-6: Database Design',
                    'Hard-7: Full-Stack Engineering',

                    'Soft-1: Business Strategy Alignment',
                    'Soft-2: Cross-Functional Collaboration',
                    'Soft-3: Strategic Problem-Solving',
                    'Soft-4: Client Engagement',
                    'Soft-5: Project Management',
                    'Soft-6: Team Management',
                    'Soft-7: Remote Work Adaptability',
                    'Soft-8: Team Collaboration',
                    'Soft-9: Communication Skills',
                    'Soft-10: Time Management',
                    'Soft-11: Adaptability',
                    'Soft-12: Critical Thinking',
                    'Soft-13: Technical Leadership',
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
                    [
                        'Hard-1: Full-Stack Development',
                        'Hard-2: AI/ML Integration',
                        'Hard-3: API Integration',
                        'Hard-4: UI/UX Design',
                        'Soft-1: Project Management',
                        'Soft-2: Team Management',
                        'Soft-3: Remote Work Adaptability',
                        'Soft-4: Team Collaboration',
                    ],
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
                    'Hard-1: System Architecture',
                    'Hard-2: Real-Time Analytics',
                    'Hard-3: Full-Stack Development',
                    'Hard-4: Database Optimization (MySQL, Redis)',
                    'Soft-1: Technical Leadership',
                    'Soft-2: Strategic Problem-Solving',
                    'Soft-3: Client Relationship Management',
                    'Soft-4: Project Management',
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
                    'Hard-1: Mobile App Development',
                    'Hard-2: API Integration',
                    'Hard-3: Security Best Practices',

                    'Soft-1: Technical Leadership',
                    'Soft-2: Project Management',
                    'Soft-3: Team Management',
                    'Soft-4: Remote Work Adaptability',
                    'Soft-5: Team Collaboration',
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


        // go through each work history and convert the skills_used and achievements to JSON

        // todo: convert skills to new polymorphic structure
        $this->command->error('WorkExperienceSeeder: work experience seeder needs converted to new polymorphic structure');
        \Log::error('WorkExperienceSeeder: work experience seeder needs converted to new polymorphic structure');
        dd('NOT IMPLEMENTED YET, TODO!!!');



        DB::table('work_experiences')->insert($workHistory);
    }
}
