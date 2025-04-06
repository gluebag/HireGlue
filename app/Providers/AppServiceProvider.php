<?php

namespace App\Providers;

use App\Services\HtmlToMarkdownService;
use Illuminate\Support\ServiceProvider;
use App\Services\AssistantsService;
use App\Services\ThreadManagementService;
use App\Services\PromptEngineeringService;
use App\Services\EmbeddingsService;
use App\Services\GenerationService;
use App\Services\PDFService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AssistantsService::class, function ($app) {
            return new AssistantsService();
        });

        $this->app->singleton(ThreadManagementService::class, function ($app) {
            return new ThreadManagementService(
                $app->make(AssistantsService::class)
            );
        });

        $this->app->singleton(PromptEngineeringService::class, function ($app) {
            return new PromptEngineeringService();
        });

        $this->app->singleton(EmbeddingsService::class, function ($app) {
            return new EmbeddingsService();
        });

        $this->app->singleton(HtmlToMarkdownService::class, function ($app) {
            return new HtmlToMarkdownService();
        });

        $this->app->singleton(GenerationService::class, function ($app) {
            return new GenerationService(
                $app->make(ThreadManagementService::class),
                $app->make(PromptEngineeringService::class),
                $app->make(EmbeddingsService::class),
                $app->make(PDFService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
