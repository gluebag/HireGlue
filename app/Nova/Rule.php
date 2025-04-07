<?php

namespace App\Nova;

use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class Rule extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Rule>
     */
    public static $model = \App\Models\Rule::class;

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
        'id', 'name', 'description'
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
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->rules('required')
                ->hideFromIndex(),

            // Form field
            Select::make('Type')
                ->options([
                    'resume' => 'Resume',
                    'cover_letter' => 'Cover Letter',
                    'both' => 'Both'
                ])
                ->displayUsingLabels()
                ->default('both')
                ->rules('required')
                ->onlyOnForms(),

            // Display field
            Badge::make('Type')
                ->map([
                    'resume' => 'info',
                    'cover_letter' => 'success',
                    'both' => 'warning',
                ])
                ->icons([
                    'info' => 'document',
                    'success' => 'mail',
                    'warning' => 'duplicate',
                ])
                ->exceptOnForms(),

            Text::make('Source')
                ->nullable(),

            // Form field
            Number::make('Importance')
                ->min(1)
                ->max(10)
                ->default(5)
                ->help('Rate importance from 1-10')
                ->onlyOnForms(),

            // Display field
            Badge::make('Importance')
                ->map([
                    '1' => 'info',
                    '2' => 'info',
                    '3' => 'success',
                    '4' => 'success',
                    '5' => 'success',
                    '6' => 'warning',
                    '7' => 'warning',
                    '8' => 'warning',
                    '9' => 'danger',
                    '10' => 'danger',
                ])
                ->exceptOnForms(),

            Code::make('Validation Logic')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),

            // show the created_at date in the format "DD/MM/YYYY HH:MM AM/PM"
            DateTime::make('Created At', 'created_at')
                ->sortable(),
        ];
    }
}
