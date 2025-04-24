<?php

namespace App\Nova;

use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Laravel\Nova\Fields\MorphTo;

class Skill extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Skill>
     */
    public static $model = \App\Models\Skill::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'type',
        'proficiency_reason_type',
        'proficiency_reason',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->help('Name of the skill. E.g. Java, Python, etc. Note: Don\'t be overly specific. Just the name of the skill is enough.')
                ->rules('required', 'max:255'),

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
                ->rules('required')
                ->help('Type of skill. Technical, Soft Skill, Domain Knowledge, Tool/Software, Language, Other'),

            Number::make('Years of Experience (Est.)', 'years_experience')
                ->min(0)
                ->max(50)
                ->step(1)
                ->default(0)
                ->nullable()
                ->help('Estimated years of experience in this skill'),


            MorphTo::make('Via', 'skillable')
                ->types([
                User::class,
                WorkExperience::class,
                Project::class,
                JobPost::class,
            ])->searchable(),


            Number::make('Proficiency Level', 'proficiency')
                ->min(0)
                ->max(10)
                ->step(1)
                ->default(0)
                ->nullable()
                ->help(
                    <<<EOH
Proficiency level in this skill (1-10):
1-2 (Novice): Basic theoretical knowledge, little to no practical experience.
3-4 (Beginner): Some hands-on experience, can handle simple tasks with guidance.
5-6 (Intermediate): Solid working knowledge, can work independently on moderately complex tasks.
7-8 (Advanced): Expert-level skills, can lead projects or solve complex problems.
9-10 (Master/Expert): World-class expertise, can innovate or teach others.
EOH
                ),

            Select::make('Proficiency Reason (Source)', 'proficiency_reason_type')
                ->options([
                    '' => 'Select Proficiency Reason Source',
                    'job_post_description' => 'Job Post (Analysis)',
                    'project' => 'Direct Experience (Project)',
                    'work' => 'Direct Experience (Work)',
                    'github' => 'GitHub/Portfolio Analysis',
                    'local_code' => 'Local Code Analysis',
                    'other' => 'Other',
                ])
                ->nullable()
                //                ->rules('required')
                ->help('Type of reason for the proficiency level. Job Post Analysis, Project Experience, Work Experience/Achievements, GitHub/Portfolio Analysis, Local Code Analysis, Other'),

            Textarea::make('Proficiency Reason (Details)', 'proficiency_reason')
                ->nullable()
                ->rows(3)
                ->hide()
                ->rules('sometimes')
                ->dependsOn('proficiency_reason_type', function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    if(!empty($formData->str('proficiency_reason_type'))) {
                        $field->show()->rules('required');
                    }
                })
                ->help('Explanation for why this skill is needed or how it was acquired and what proficiency level is expected. E.g. "I have used Java for 5 years in various projects, including web development and data analysis."'),

            // show the created_at date in the format "DD/MM/YYYY HH:MM AM/PM"
            DateTime::make('Added At', 'created_at')
                ->sortable(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            \App\Nova\Actions\ImportUserData::make()->standalone(),
            \App\Nova\Actions\ImportGitHubData::make()->standalone(),
            // \App\Nova\Actions\ImportLinkedInData::make()->standalone(),
        ];
    }
}
