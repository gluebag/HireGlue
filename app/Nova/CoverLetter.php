<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class CoverLetter extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\CoverLetter>
     */
    public static $model = \App\Models\CoverLetter::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Cover Letters';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Cover Letter';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'content'
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

            BelongsTo::make('Job Post', 'jobPost', JobPost::class),

            Textarea::make('Content')
                ->alwaysShow()
                ->hideFromIndex(),

            Text::make('File Path')
                ->nullable(),

            Number::make('Word Count')
                ->nullable(),

            Code::make('Rule Compliance')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),

            Code::make('Generation Metadata')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
}
