<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

class ExperienceItem extends Repeatable
{
    /**
     * Get the displayable singular label for the repeatable.
     */
    public static function singularLabel(): string
    {
        return 'Experience Requirement';
    }


    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Title', 'title')
                ->rules('required'),

            Number::make('Years Required', 'years')
                ->min(0)
                ->step(0.5)
                ->default(1),

            Select::make('Level', 'level')
                ->options([
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                    'expert' => 'Expert',
                ])
                ->default('intermediate'),

            Textarea::make('Description', 'description')
                ->rows(2)
                ->nullable(),
        ];
    }
}
