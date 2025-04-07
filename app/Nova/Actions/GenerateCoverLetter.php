<?php

namespace App\Nova\Actions;

use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateCoverLetter extends Action implements ShouldQueue
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

        foreach ($models as $jobPost) {
            try {
                $coverLetter = $generationService->generateCoverLetter($jobPost);

                return Action::message("Cover letter generated successfully! ID: {$coverLetter->id}");
            } catch (\Exception $e) {
                return Action::danger("Failed to generate cover letter: {$e->getMessage()}");
            }
        }
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Generate Cover Letter';
    }
}
