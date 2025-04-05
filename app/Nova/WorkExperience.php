<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;

class WorkExperience extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\WorkExperience>
     */
    public static $model = \App\Models\WorkExperience::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'company_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'company_name', 'position'
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

            BelongsTo::make('User'),

            Text::make('Company Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Position')
                ->sortable()
                ->rules('required', 'max:255'),

            Date::make('Start Date')
                ->rules('required'),

            Date::make('End Date')
                ->nullable()
                ->hideFromIndex()
                ->help('Leave blank if this is your current job'),

            Boolean::make('Current Job')
                ->default(false),

            Textarea::make('Description')
                ->rules('required')
                ->hideFromIndex(),

            KeyValue::make('Skills Used')
                ->keyLabel('Skill')
                ->valueLabel('Description')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Achievements')
                ->keyLabel('Achievement')
                ->valueLabel('Description')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
}
