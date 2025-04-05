<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class Resume extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Resume>
     */
    public static $model = \App\Models\Resume::class;

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
        return 'Resumes';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Resume';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'content'
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
            
            BelongsTo::make('Job Post', 'jobPost', JobPost::class),

            Textarea::make('Content')
                ->alwaysShow()
                ->hideFromIndex(),

            Text::make('File Path')
                ->nullable(),

            Number::make('Word Count')
                ->nullable(),

            KeyValue::make('Skills Included', 'skills_included')
                ->keyLabel('Skill')
                ->valueLabel('Relevance')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Experiences Included', 'experiences_included')
                ->keyLabel('Experience')
                ->valueLabel('Relevance')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Education Included', 'education_included')
                ->keyLabel('Education')
                ->valueLabel('Relevance')
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Projects Included', 'projects_included')
                ->keyLabel('Project')
                ->valueLabel('Relevance')
                ->nullable()
                ->hideFromIndex(),

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
