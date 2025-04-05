<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;

class EducationItem extends Repeatable
{
  /**
     * Get the displayable singular label for the repeatable.
     */
    public static function singularLabel(): string
    {
        return 'Education Requirement';
    }

    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Select::make('Degree Level', 'level')
                ->options([
                    'high_school' => 'High School Diploma',
                    'associate' => 'Associate\'s Degree',
                    'bachelor' => 'Bachelor\'s Degree',
                    'master' => 'Master\'s Degree',
                    'doctorate' => 'Doctorate/PhD',
                    'certificate' => 'Professional Certificate',
                    'other' => 'Other',
                ])
                ->rules('required')
                ->default('bachelor'),

            Text::make('Field of Study', 'field')
                ->help('e.g., Computer Science, Business, etc.')
                ->rules('required'),

            Boolean::make('Is Required', 'is_required')
                ->help('Must have this exact degree or is it flexible?')
                ->default(true),

            Number::make('Minimum GPA', 'min_gpa')
                ->min(0)
                ->max(4.0)
                ->step(0.1)
                ->nullable()
                ->help('Leave blank if not applicable'),

            Textarea::make('Additional Requirements', 'description')
                ->rows(2)
                ->nullable()
                ->help('Any specific requirements or notes about this education'),
        ];
    }
}
