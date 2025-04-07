<?php

namespace App\Nova\Repeaters;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;

class LineItemAchievement extends Repeatable
{

    /**
     * Get the displayable singular label for the repeatable.
     */
    public static function singularLabel(): string
    {
        return 'Achievement';
    }

    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Textarea::make('Description', 'description')
                ->alwaysShow()
                ->rules('required')
                ->help('What did you achieve?')
                ->rows(3)
        ];
    }
}
