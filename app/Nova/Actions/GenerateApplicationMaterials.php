<?php

namespace App\Nova\Actions;

use App\Services\GenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateApplicationMaterials extends Action
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
                $resume = $generationService->generateResume($jobPost);
                $coverLetter = $generationService->generateCoverLetter($jobPost);
                
                return Action::message("Resume (ID: {$resume->id}) and Cover Letter (ID: {$coverLetter->id}) generated successfully!");
            } catch (\Exception $e) {
                return Action::danger("Failed to generate application materials: {$e->getMessage()}");
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
        return 'Generate Resume & Cover Letter';
    }
}
