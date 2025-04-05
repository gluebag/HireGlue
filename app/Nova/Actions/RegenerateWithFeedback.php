<?php

namespace App\Nova\Actions;

use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class RegenerateWithFeedback extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $generationService = app(GenerationService::class);

        foreach ($models as $document) {
            try {
                $regenerated = $generationService->regenerateWithFeedback($document, [
                    'feedback' => $fields->feedback
                ]);
                
                return Action::message("Document regenerated successfully!");
            } catch (\Exception $e) {
                return Action::danger("Failed to regenerate document: {$e->getMessage()}");
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Textarea::make('Feedback', 'feedback')
                ->rules('required')
                ->help('Provide feedback on what should be improved in this document.'),
        ];
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Regenerate with Feedback';
    }
}
