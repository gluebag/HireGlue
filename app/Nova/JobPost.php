<?php

namespace App\Nova;

use App\Nova\Actions\ConvertGoogleJobPost;
use App\Nova\Actions\ImportJobPostFromUrl;
use App\Nova\Repeaters\EducationItem;
use App\Nova\Repeaters\ExperienceItem;
use App\Nova\Repeaters\SkillItem;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;

class JobPost extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\JobPost>
     */
    public static $model = \App\Models\JobPost::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'job_title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'company_name',
        'job_title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            Text::make('Company Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Job Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Job Description')
                ->rules('required')
                ->hideFromIndex(),

            Repeater::make('Required Education')
                ->repeatables([
                    EducationItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Required Experience')
                ->repeatables([
                    ExperienceItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Required Skills')
                ->repeatables([
                    SkillItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Preferred Skills')
                ->repeatables([
                    SkillItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Text::make('Job Post URL')
                ->hideFromIndex()
                ->nullable(),

            Date::make('Job Post Date')
                ->nullable(),

            Select::make('Job Location Type')
                ->options([
                    'remote' => 'Remote',
                    'in-office' => 'In-Office',
                    'hybrid' => 'Hybrid',
                ])
                ->default('remote'),

            Select::make('Position Level')
                ->options([
                    'entry-level' => 'Entry Level',
                    'mid-level' => 'Mid Level',
                    'senior' => 'Senior',
                    'lead' => 'Lead',
                    'manager' => 'Manager',
                    'director' => 'Director',
                    'executive' => 'Executive',
                ])
                ->default('mid-level'),

            Select::make('Job Type')
                ->options([
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                    'freelance' => 'Freelance',
                ])
                ->default('full-time'),

            Number::make('Resume Min Words')
                ->default(450)
                ->hideFromIndex(),

            Number::make('Resume Max Words')
                ->default(850)
                ->hideFromIndex(),

            Number::make('Cover Letter Min Words')
                ->default(450)
                ->hideFromIndex(),

            Number::make('Cover Letter Max Words')
                ->default(750)
                ->hideFromIndex(),

            Number::make('Resume Min Pages')
                ->default(1)
                ->hideFromIndex(),

            Number::make('Resume Max Pages')
                ->default(2)
                ->hideFromIndex(),

            Number::make('Cover Letter Min Pages')
                ->default(1)
                ->hideFromIndex(),

            Number::make('Cover Letter Max Pages')
                ->default(1)
                ->hideFromIndex(),

            Currency::make('Salary Range Min')
                ->nullable()
                ->hideFromIndex(),

            Currency::make('Salary Range Max')
                ->nullable()
                ->hideFromIndex(),

            Currency::make('Min Acceptable Salary')
                ->nullable()
                ->hideFromIndex(),

            Date::make('Ideal Start Date')
                ->nullable()
                ->hideFromIndex(),

            Number::make('Position Preference')
                ->default(1)
                ->help('1 = top choice, 2 = second choice, etc.')
                ->hideFromIndex(),

            Boolean::make('Open To Travel')
                ->default(true)
                ->hideFromIndex(),

            Boolean::make('First Time Applying')
                ->default(true)
                ->hideFromIndex(),

            Textarea::make('Things I Like')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Dislike')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Like About Company')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Dislike About Company')
                ->nullable()
                ->hideFromIndex(),

            HasMany::make('Resumes'),
            HasMany::make('Cover Letters', 'coverLetters'),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            ConvertGoogleJobPost::make()->standalone(),
            ImportJobPostFromUrl::make()->standalone(),
            
            new \App\Nova\Actions\GenerateResume,
            new \App\Nova\Actions\GenerateCoverLetter,
            new \App\Nova\Actions\GenerateApplicationMaterials,
            new \App\Nova\Actions\RegenerateWithFeedback,
        ];
    }
}
