<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;

class Project extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Project>
     */
    public static $model = \App\Models\Project::class;

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
        'description'
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
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),
                
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->rules('required')
                ->hideFromIndex(),

            Date::make('Start Date')
                ->nullable(),

            Date::make('End Date')
                ->nullable(),

            Text::make('URL')
                ->hideFromIndex()
                ->nullable(),

            KeyValue::make('Technologies Used')
                ->keyLabel('Technology')
                ->valueLabel('Details')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Achievements')
                ->keyLabel('Achievement')
                ->valueLabel('Description')
                ->nullable()
                ->hideFromIndex(),
        ];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            \App\Nova\Actions\ImportUserData::make()->standalone(),
            \App\Nova\Actions\ImportGitHubData::make()->standalone(),
        ];
    }
}
