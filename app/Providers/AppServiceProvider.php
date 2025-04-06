<?php

namespace App\Providers;

use App\Services\AssistantsService;
use App\Services\ThreadManagementService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\ServiceProvider;
use App\Services\RulesService;
use App\Services\OpenAIService;
use App\Services\PDFService;
use App\Services\GenerationService;
use PHPUnit\Framework\TestCase;

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

        $this->app->singleton(GenerationService::class, function ($app) {
            return new GenerationService(
                $app->make(ThreadManagementService::class),
                $app->make(PDFService::class)
            );
        });
//        $this->app->singleton(GenerationService::class, function ($app) {
//            return new GenerationService(
//                $app->make(OpenAIService::class),
//                $app->make(RulesService::class),
//                $app->make(PDFService::class)
//            );
//        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ParallelTesting::setUpProcess(function (int $token) {
            // ...
        });

        ParallelTesting::setUpTestCase(function (int $token, TestCase $testCase) {
            // ...
        });

        // Executed when a test database is created...
        ParallelTesting::setUpTestDatabase(function (string $database, int $token) {
            Artisan::call('db:seed');
        });

        ParallelTesting::tearDownTestCase(function (int $token, TestCase $testCase) {
            // ...
        });

        ParallelTesting::tearDownProcess(function (int $token) {
            // ...
        });
    }
}
