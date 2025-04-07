<?php

namespace App\Nova;

use App\Nova\Actions\ConvertGoogleJobPost;
use App\Nova\Actions\ImportJobPostFromContent;
use App\Nova\Repeaters\EducationItem;
use App\Nova\Repeaters\ExperienceItem;
use App\Nova\Repeaters\SkillItem;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\NovaRequest;

class JobPost extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\JobPost>
     */
    public static $model = \App\Models\JobPost::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'job_title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'company_name',
        'job_title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->hideFromIndex()
                ->default($request->user()->id)
                ->withoutTrashed()
                ->searchable(),

            Text::make('Company Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Job Title')
                ->sortable()
                ->rules('required', 'max:255')
                ->onlyOnForms(),

            URL::make('Job Title', 'job_post_url')
                ->displayUsing(function($value, $resource, $attribute) {
                    return $resource->job_title;
                })
                ->sortable()
                ->exceptOnForms(),

            Stack::make('Salary', [
                Text::make('Salary Range', function() {
                    if (!$this->salary_range_min && !$this->salary_range_max) {
                        return 'Not specified';
                    }

                    if ($this->salary_range_min && !$this->salary_range_max) {
                        return '$' . number_format($this->salary_range_min);
                    }

                    if (!$this->salary_range_min && $this->salary_range_max) {
                        return 'Up to $' . number_format($this->salary_range_max);
                    }

                    return '$' . number_format($this->salary_range_min) . ' - $' . number_format($this->salary_range_max);
                }),
                Text::make('', function() {
                    if (!$this->salary_range_min && !$this->salary_range_max) {
                        return '';
                    }

                    $avgSalary = $this->salary_range_min && $this->salary_range_max
                        ? ($this->salary_range_min + $this->salary_range_max) / 2
                        : ($this->salary_range_min ?: $this->salary_range_max);

//                     $calculatedSalary = $avgSalary;
                    $calculatedSalary = $this->salary_range_min;

                    // Apply fixed threshold based classification
                    if ($calculatedSalary < 175000) {
                        return '<span style="color: #ef4444; font-weight: bold;">Low</span>';
                    } elseif ($calculatedSalary < 180000) {
                        return '<span style="color: #f59e0b; font-weight: bold;">Medium-Low</span>';
                    } elseif ($calculatedSalary < 210000) {
                        return '<span style="color: #eab308; font-weight: bold;">Medium</span>';
                    } else {
                        return '<span style="color: #10b981; font-weight: bold;">High</span>';
                    }
                })->asHtml(),
            ]),

            Badge::make('Interest Metrics', function() {
                $metrics = [];

                if (!empty($this->things_i_like)) {
                    $likesCount = substr_count($this->things_i_like, "\n") + 1;
                    $metrics[] = "Likes: $likesCount";
                }

                if (!empty($this->things_i_dislike)) {
                    $dislikesCount = substr_count($this->things_i_dislike, "\n") + 1;
                    $metrics[] = "Dislikes: $dislikesCount";
                }

                if (!empty($this->things_i_like_about_company)) {
                    $companyLikesCount = substr_count($this->things_i_like_about_company, "\n") + 1;
                    $metrics[] = "Co+: $companyLikesCount";
                }

                if (!empty($this->things_i_dislike_about_company)) {
                    $companyDislikesCount = substr_count($this->things_i_dislike_about_company, "\n") + 1;
                    $metrics[] = "Co-: $companyDislikesCount";
                }

                return empty($metrics) ? 'No data' : implode(' | ', $metrics);
            })->map([
                'No data' => 'danger',
                '*' => 'success',
            ]),

            Text::make('Requirements', function() {
                $reqSkills = $this->required_skills ?? [];
                $prefSkills = $this->preferred_skills ?? [];
                $reqExp = $this->required_experience ?? [];
                $reqEdu = $this->required_education ?? [];

                $counts = [];
                if (count($reqSkills)) $counts[] = count($reqSkills) . " Skills";
                if (count($prefSkills)) $counts[] = count($prefSkills) . " Pref";
                if (count($reqExp)) $counts[] = count($reqExp) . " Exp";
                if (count($reqEdu)) $counts[] = count($reqEdu) . " Edu";

                return empty($counts) ? 'No reqs' : implode(' | ', $counts);
            }),

            Textarea::make('Job Description')
                ->rules('required')
                ->hideFromIndex(),

            Repeater::make('Required Education')
                ->repeatables([
                    EducationItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Required Experience')
                ->repeatables([
                    ExperienceItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Required Skills')
                ->repeatables([
                    SkillItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Repeater::make('Preferred Skills')
                ->repeatables([
                    SkillItem::make()
                ])
                ->asJson()
                ->nullable()
                ->hideFromIndex(),

            Text::make('Job Post URL')
                ->hideFromIndex()
                ->nullable(),

            Date::make('Job Post Date')
                ->nullable(),

            Select::make('Job Location Type')
                ->options([
                    'remote' => 'Remote',
                    'in-office' => 'In-Office',
                    'hybrid' => 'Hybrid',
                ])
                ->default('remote'),

            Select::make('Position Level')
                ->options([
                    'entry-level' => 'Entry Level',
                    'mid-level' => 'Mid Level',
                    'senior' => 'Senior',
                    'lead' => 'Lead',
                    'manager' => 'Manager',
                    'director' => 'Director',
                    'executive' => 'Executive',
                ])
                ->default('mid-level'),

            Select::make('Job Type')
                ->options([
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                    'freelance' => 'Freelance',
                ])
                ->default('full-time'),

            Number::make('Resume Min Words')
                ->default(450)
                ->hideFromIndex(),

            Number::make('Resume Max Words')
                ->default(850)
                ->hideFromIndex(),

            Number::make('Cover Letter Min Words')
                ->default(450)
                ->hideFromIndex(),

            Number::make('Cover Letter Max Words')
                ->default(750)
                ->hideFromIndex(),

            Number::make('Resume Min Pages')
                ->default(1)
                ->hideFromIndex(),

            Number::make('Resume Max Pages')
                ->default(2)
                ->hideFromIndex(),

            Number::make('Cover Letter Min Pages')
                ->default(1)
                ->hideFromIndex(),

            Number::make('Cover Letter Max Pages')
                ->default(1)
                ->hideFromIndex(),

            Currency::make('Salary Range Min')
                ->nullable()
                ->hideFromIndex(),

            Currency::make('Salary Range Max')
                ->nullable()
                ->hideFromIndex(),

            Currency::make('Min Acceptable Salary')
                ->nullable()
                ->hideFromIndex(),

            Date::make('Ideal Start Date')
                ->nullable()
                ->hideFromIndex(),

            Number::make('Position Preference')
                ->default(1)
                ->help('1 = top choice, 2 = second choice, etc.')
                ->hideFromIndex(),

            Boolean::make('Open To Travel')
                ->default(true)
                ->hideFromIndex(),

            Boolean::make('First Time Applying')
                ->default(true)
                ->hideFromIndex(),

            Textarea::make('Things I Like')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Dislike')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Like About Company')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Things I Dislike About Company')
                ->nullable()
                ->hideFromIndex(),

            // show the created_at date in the format "DD/MM/YYYY HH:MM AM/PM"
            DateTime::make('Added At', 'created_at')
                ->sortable(),

            HasMany::make('Resumes'),
            HasMany::make('Cover Letters', 'coverLetters'),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            ConvertGoogleJobPost::make()->standalone(),
            ImportJobPostFromContent::make()->standalone(),

            new \App\Nova\Actions\GenerateResume,
            new \App\Nova\Actions\GenerateCoverLetter,
            new \App\Nova\Actions\GenerateApplicationMaterials,
            new \App\Nova\Actions\RegenerateWithFeedback,
        ];
    }
}
