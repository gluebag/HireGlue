<?php

namespace App\Nova;

use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

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
        'type'
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

            BelongsTo::make('User')
                ->hideFromIndex()
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('Type')
                ->options([
                    'technical' => 'Technical',
                    'soft' => 'Soft',
                    'language' => 'Language',
                    'other' => 'Other'
                ])
                ->default('technical')
                ->sortable()
                ->rules('required'),

            Number::make('Proficiency')
                ->min(1)
                ->max(10)
                ->default(0)
                ->help('Rate your proficiency from 1-10'),

            Textarea::make('Proficiency Reason')
                ->rows(3)
                ->alwaysShow()
                ->help('Explain why you rated your proficiency this way'),

            Number::make('Years Experience')
                ->sortable()
                ->min(0)
                ->default(0),

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

