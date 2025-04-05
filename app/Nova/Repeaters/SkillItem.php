<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;

class SkillItem extends Repeatable
{
      /**
     * Get the displayable singular label for the repeatable.
     */
    public static function singularLabel(): string
    {
        return 'Skill';
    }


    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
          return [
            Text::make('Name', 'name')
                ->rules('required'),
                
            Select::make('Type', 'type')
                ->options([
                    'technical' => 'Technical',
                    'soft' => 'Soft Skill',
                    'domain' => 'Domain Knowledge',
                    'tool' => 'Tool/Software',
                    'language' => 'Language',
                    'other' => 'Other',
                ])
                ->default('technical'),
                
            Number::make('Proficiency Level', 'level')
                ->min(1)
                ->max(5)
                ->default(3)
                ->help('1 = Beginner, 5 = Expert'),
        ];
    }
}
