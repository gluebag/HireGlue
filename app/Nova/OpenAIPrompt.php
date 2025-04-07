<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;

class OpenAIPrompt extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\OpenAIPrompt>
     */
    public static $model = \App\Models\OpenAIPrompt::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Prompts';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'AI Prompt';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'type',
        'prompt_template'
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



            Boolean::make('Active')
            ->default(true),

            // Form field
            Select::make('Type')
                ->options([
                    'resume' => 'Resume',
                    'cover_letter' => 'Cover Letter',
                    'analysis' => 'Analysis',
                    'rule_check' => 'Rule Check'
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->onlyOnForms(),

            // Display field
            Badge::make('Type')
                ->map([
                    'resume' => 'info',
                    'cover_letter' => 'success',
                    'analysis' => 'analysis',
                    'rule_check' => 'rule_check',
                ])
                ->addTypes([
                    'rule_check' => 'font-medium text-gray-600 bg-gray-100',
                    'analysis' => 'font-medium text-yellow-600 bg-yellow-100',
                ])
                ->icons([
                    'info' => 'document',
                    'success' => 'mail',
                    'warning' => 'chart-bar',
                    'danger' => 'check-circle',
                    'rule_check' => 'magnifying-glass-circle',
                    'analysis' => 'chart-bar',
                ])
                ->exceptOnForms(),

            Textarea::make('Prompt Template')
                ->alwaysShow()
                ->rules('required'),

            Code::make('Parameters')
                ->language('json')
                ->nullable()
                ->hideFromIndex(),

            // Form field
            Text::make('Model')
                ->default('gpt-4o')
                ->rules('required')
                ->onlyOnForms(),

            // Display field - different colors based on model pricing tiers
            Badge::make('Model')
                ->map([
                    'gpt-4' => 'generic',
                    'gpt-4-32k' => 'generic',
                    'gpt-4-turbo' => 'generic',
                    'gpt-3.5-turbo-16k' => 'generic',
                    'gpt-3.5-turbo' => 'generic',
                    'gpt-4o' => 'generic', // high intelligence
                    'gpt-4o-mini' => 'generic', // high intelligence
                    'chatgpt-4o-latest' => 'success', // high intelligence
                    'o1' => 'warning', // high intelligence
                    'o3-mini' => 'warning', // high intelligence
                    'o1-pro' => 'danger', // high intelligence
                    'gpt-4.5-preview' => 'danger', // high intelligence

                ])
                ->addTypes([
                    'generic' => 'font-medium text-gray-600',
                ])
                ->withIcons()
                ->icons([
                    //     'success' => 'check-circle',
                    //     'info' => 'information-circle',
                    //     'danger' => 'exclamation-circle',
                    //     'warning' => 'exclamation-circle',
                    'generic' => '',
                    'danger' => 'currency-dollar',
                    'warning' => 'currency-dollar',
                    'success' => 'currency-dollar',
                ])
                ->exceptOnForms(),

            // Form field
            Number::make('Temperature')
                ->step(0.1)
                ->min(0)
                ->max(1.0)
                ->default(0.7)
                ->rules('required')
                ->onlyOnForms(),

            // Display field
            Badge::make('Temperature')
                ->resolveUsing(function ($value) {
                    // Categorize temperature into ranges
                    if ($value >= 0.8) return 'very-high';
                    if ($value >= 0.6) return 'high';
                    if ($value >= 0.4) return 'medium';
                    if ($value >= 0.2) return 'low';
                    return 'very-low';
                })
                ->label(function ($value) {
                    // Format the label to show the temperature
                    return $value . ' (' . $this->temperature . ')';
                })
                ->addTypes([
                    'generic' => 'font-medium text-gray-600 bg-gray-100',
                ])
                ->map([
                    'very-high' => 'danger',
                    'high' => 'generic',
                    'medium' => 'generic',
                    'low' => 'warning',
                    'very-low' => 'danger',
                ])
                ->exceptOnForms(),

            // Form field
            Number::make('Max Tokens')
                ->min(100)
                ->default(2000)
                ->rules('required')
                ->onlyOnForms(),

            // Display field
            Badge::make('Max Tokens')
                ->resolveUsing(function ($value) {
                    // Categorize token usage into ranges
                    if ($value > 32000) return 'extreme';
                    if ($value > 16000) return 'very-high';
                    if ($value > 8000) return 'high';
                    if ($value > 4000) return 'medium';
                    return 'low';
                })
                ->addTypes([
                    'generic' => 'font-medium text-gray-600',

                ])
                ->label(function ($value) {
                    // Format the label to show the number of tokens
                    return $value . ' (' . $this->max_tokens . ')';
                })
                ->map([
                    'extreme' => 'danger',
                    'very-high' => 'danger',
                    'high' => 'warning',
                    'medium' => 'generic',
                    'low' => 'generic',
                ])
                ->icons([
                    'danger' => 'exclamation-circle',
                    'warning' => 'exclamation',
                    'success' => 'check-circle',
                    'info' => 'information-circle',
                    'generic' => '',
                ])
                ->exceptOnForms(),


        ];
    }
}
