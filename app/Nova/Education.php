<?php

namespace App\Nova;

use App\Nova\Repeaters\LineItemAchievement;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Education extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Education>
     */
    public static $model = \App\Models\Education::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'institution';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'institution', 'degree', 'field_of_study'
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
                ->hideFromIndex()
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            Text::make('Institution')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Degree')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Field of Study')
                ->nullable(),

            Date::make('Start Date')
                ->rules('required'),

            Date::make('End Date')
                ->nullable()
                ->hideFromIndex()
                ->help('Leave blank if currently attending'),

            Boolean::make('Current')
                ->default(false),

            Number::make('GPA')
                ->step(0.01)
                ->min(0)
                ->max(4.0)
                ->nullable()
                ->hideFromIndex(),


            Repeater::make('Achievements', 'achievements')
                ->repeatables([
                    LineItemAchievement::make()
                ])
                ->nullable()
                ->asJson(),
//            KeyValue::make('Achievements')
//                ->keyLabel('Achievement')
//                ->valueLabel('Description')
//                ->nullable()
//                ->hideFromIndex(),

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
