<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ThreadSession extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ThreadSession>
     */
    public static $model = \App\Models\ThreadSession::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'thread_id', 'assistant_id',
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
            
            Badge::make('Type')
                ->map([
                    'resume' => 'info',
                    'cover_letter' => 'success',
                    'validation' => 'warning',
                ]),
                
            Badge::make('Status')
                ->map([
                    'created' => 'info',
                    'processing' => 'warning',
                    'completed' => 'success',
                    'failed' => 'danger',
                ]),
                
            Text::make('Assistant ID', 'assistant_id')
                ->hideFromIndex(),
                
            Text::make('Thread ID', 'thread_id')
                ->hideFromIndex(),
                
            DateTime::make('Completed At')
                ->hideFromIndex(),
                
            Textarea::make('Error')
                ->hideFromIndex()
                ->onlyOnDetail(),
                
            Code::make('Content')
                ->language('markdown')
                ->hideFromIndex()
                ->onlyOnDetail(),
                
            Code::make('Metrics')
                ->language('json')
                ->hideFromIndex()
                ->onlyOnDetail(),
        ];
    }
}