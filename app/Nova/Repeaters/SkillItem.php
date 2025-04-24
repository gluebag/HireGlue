<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;

class SkillItem extends Repeatable
{
    public static $model = \App\Models\Skill::class;

      /**
     * Get the displayable singular label for the repeatable.
     */
    public static function singularLabel(): string
    {
        return 'Skill';
    }


    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
          return [

            Text::make('Name', 'name')
                ->rules('required')
                ->help('Name of the skill. E.g. Java, Python, etc. Note: Don\'t be overly specific. Just the name of the skill is enough.'),

            Select::make('Type', 'type')
                ->options([
                    'technical' => 'Technical',
                    'soft' => 'Soft Skill',
                    'domain' => 'Domain Knowledge',
                    'tool' => 'Tool/Software',
                    'work_experience' => 'Work Experience',
                    'language' => 'Language',
                    'other' => 'Other',
                ])
                ->default('technical')
                ->rules('required')
                ->help('Type of skill. Technical, Soft Skill, Domain Knowledge, Tool/Software, Language, Other'),

              Number::make('Years of Experience (Est.)', 'years_experience')
                  ->min(0)
                  ->max(50)
                  ->step(1)
                  ->default(0)
                  ->nullable()
                  ->help('Estimated years of experience in this skill'),

            Number::make('Proficiency Level', 'proficiency')
                ->min(1)
                ->max(10)
                ->step(1)
                ->default(0)
                ->nullable()
                ->help(<<<EOH
Proficiency level in this skill (1-10):
1-2 (Novice): Basic theoretical knowledge, little to no practical experience.
3-4 (Beginner): Some hands-on experience, can handle simple tasks with guidance.
5-6 (Intermediate): Solid working knowledge, can work independently on moderately complex tasks.
7-8 (Advanced): Expert-level skills, can lead projects or solve complex problems.
9-10 (Master/Expert): World-class expertise, can innovate or teach others.
EOH
                ),

              Select::make('Proficiency Reason (Type)', 'proficiency_reason_type')
                ->options([
                    '' => 'Select Reason Type',
                    'job_post_description' => 'Job Post (Analysis)',
                    'project' => 'Direct Experience (Project)',
                    // 'Work Experience/Achievements',
                    'work' => 'Direct Experience (Work)',
                    'github' => 'GitHub/Portfolio Analysis',
                    'local_code' => 'Local Code Analysis',
                    'other' => 'Other',
                ])
                ->nullable()
//                ->rules('required')
                ->help('Type of reason for the proficiency level. Job Post Analysis, Project Experience, Work Experience/Achievements, GitHub/Portfolio Analysis, Local Code Analysis, Other'),

            \Laravel\Nova\Fields\Textarea::make('Proficiency Reason (Details)', 'proficiency_reason')
                ->nullable()
                ->rows(2)
              ->help('Explanation for why this skill is needed or how it was acquired and what proficiency level is expected. E.g. "I have used Java for 5 years in various projects, including web development and data analysis."')

        ];
    }
}
