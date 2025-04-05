<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    use PasswordValidationRules;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return \Stringable|string
     */
    public function title()
    {
        // return full name aka first name + last name
        return $this->first_name . ' ' . $this->last_name;
    }

    public function subtitle()
    {
        return $this->email;
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Users');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('User');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'last_name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Gravatar::make()
                ->maxWidth(50),

            Text::make('First Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Last Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Name', function () {
                return $this->first_name . ' ' . $this->last_name;
            })->onlyOnIndex(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules($this->passwordRules())
                ->updateRules($this->optionalPasswordRules()),

            Date::make('Date of Birth')->nullable(),

            Text::make('Phone Number')->nullable(),

            Text::make('Location')->nullable(),

            Text::make('LinkedIn', 'linkedin_url')
                // ->hideFromIndex()
                ->displayUsing(function ($value) {
                    $username = Str::after($value, 'linkedin.com/in/');
                    $username = Str::before($username, '/');
                    return "<a href='{$value}' target='_blank'>@{$username}</a>";
                })
                ->asHtml()
                ->nullable(),

            Text::make('GitHub', 'github_url')
                // ->hideFromIndex()
                ->displayUsing(function ($value) {
                    $username = Str::after($value, 'github.com/');
                    $username = Str::before($username, '/');
                    return "<a href='{$value}' target='_blank'>@{$username}</a>";
                })
                ->asHtml()
                ->nullable(),

            Text::make('Personal Website URL')
                ->hideFromIndex()
                ->copyable()
                ->nullable(),

            Text::make('Portfolio URL')
                 ->hideFromIndex()
                ->copyable()
                ->nullable(),

            HasMany::make('Work Experiences', 'workExperiences', WorkExperience::class),
            HasMany::make('Education', 'education', Education::class),
            HasMany::make('Skills', 'skills', Skill::class),
            HasMany::make('Projects', 'projects', Project::class),
            HasMany::make('Job Posts', 'jobPosts', JobPost::class),
            HasMany::make('Resumes', 'resumes', Resume::class),
            HasMany::make('Cover Letters', 'coverLetters', CoverLetter::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
