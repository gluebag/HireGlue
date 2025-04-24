<?php

namespace App\Providers;

use App\Models\User as ModelsUser;
use App\Nova\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use App\Nova\WorkExperience;
use App\Nova\Education;
use App\Nova\Skill;
use App\Nova\Project;
use App\Nova\JobPost;
use App\Nova\Resume;
use App\Nova\CoverLetter;
use App\Nova\ThreadSession;
use App\Nova\OpenAIPrompt;
use App\Nova\Rule;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Nova::withBreadcrumbs();

        Nova::userTimezone(function (Request $request) {
            return 'America/New_York';
            // return $request->user()?->timezone;
        });

        Nova::footer(function (Request $request) {
            return Blade::render('
                <div class="mt-8 leading-normal text-xs text-gray-500 space-y-1">
                    <p class="text-center">© 2025 Attentiv Development.</p>
                    <p class="text-center text-xxs">Powered by <a class="link-default" href="https://nova.laravel.com">Laravel Nova</a> · v5.4.3 (Silver Surfer)</p>
                </div>
            ');
        });

        Nova::mainMenu(function (Request $request) {
            return [
//                \Laravel\Nova\Menu\MenuItem::dashboard(\App\Nova\Dashboards\Main::class),

                \Laravel\Nova\Menu\MenuSection::make('User Profile', [
                    \Laravel\Nova\Menu\MenuItem::resource(User::class),
                    \Laravel\Nova\Menu\MenuItem::resource(Education::class),
                    \Laravel\Nova\Menu\MenuItem::resource(Skill::class),
                    \Laravel\Nova\Menu\MenuItem::resource(Project::class),
                    \Laravel\Nova\Menu\MenuItem::resource(WorkExperience::class),
                ])->icon('user')->collapsable(),

                \Laravel\Nova\Menu\MenuSection::make('Job Applications', [
                    \Laravel\Nova\Menu\MenuItem::resource(JobPost::class),
                    \Laravel\Nova\Menu\MenuItem::resource(Resume::class),
                    \Laravel\Nova\Menu\MenuItem::resource(CoverLetter::class),
                ])->icon('document-text')->collapsable(),

                \Laravel\Nova\Menu\MenuSection::make('AI Tools', [
                    \Laravel\Nova\Menu\MenuItem::resource(ThreadSession::class),
                    \Laravel\Nova\Menu\MenuItem::resource(OpenAIPrompt::class),
                ])->icon('sparkles')->collapsable(),

                \Laravel\Nova\Menu\MenuSection::make('Configuration', [
                    \Laravel\Nova\Menu\MenuItem::resource(Rule::class),
                ])->icon('cog')->collapsable(),
            ];
        });
    }

    /**
     * Register the configurations for Laravel Fortify.
     */
    protected function fortify(): void
    {
        Nova::fortify()
            ->features([
                Features::updatePasswords(),
                // Features::emailVerification(),
                // Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
            ])
            ->register();
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes(default: true)
            ->withPasswordResetRoutes()
            ->withoutEmailVerificationRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewNova', function (ModelsUser $user) {
            return true;

//            return in_array($user->email, [
//                'nathaniel@attentiv.dev',
//            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Dashboard>
     */
    protected function dashboards(): array
    {
        return [
//            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array<int, \Laravel\Nova\Tool>
     */
    public function tools(): array
    {
        return [];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();

        Nova::initialPath('/resources/job-posts');

        Nova::report(function ($exception) {
            Log::error($exception->getMessage(), [
                'exception' => $exception,
                'stack' => $exception->getTraceAsString(),
            ]);
        });
    }
}
