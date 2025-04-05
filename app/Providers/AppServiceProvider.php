<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RulesService;
use App\Services\OpenAIService;
use App\Services\PDFService;
use App\Services\GenerationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        
        $this->app->singleton(GenerationService::class, function ($app) {
            return new GenerationService(
                $app->make(OpenAIService::class),
                $app->make(RulesService::class),
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
