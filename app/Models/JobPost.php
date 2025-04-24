<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'company_name', 'job_title', 'job_description', 'job_post_url',
        'application_url', 'team', 'job_post_date', 'posted_date', 'job_location_type',
        'locations',
         'required_education', 'resume_min_words', 'resume_max_words',
        'cover_letter_min_words', 'cover_letter_max_words', 'resume_min_pages',
        'resume_max_pages', 'cover_letter_min_pages', 'cover_letter_max_pages',
        'things_i_like', 'things_i_dislike', 'things_i_like_about_company',
        'things_i_dislike_about_company', 'open_to_travel', 'salary_range_min',
        'salary_range_max', 'min_acceptable_salary', 'position_level', 'job_type',
        'job_id', 'ideal_start_date', 'position_preference', 'first_time_applying',
        'biggest_challenge_description', 'biggest_challenge_root_cause'
    ];

    protected $casts = [
        'required_education' => 'array',
        'locations' => 'array',
        'job_post_date' => 'date',
        'posted_date' => 'date',
        'ideal_start_date' => 'date',
        'open_to_travel' => 'boolean',
        'first_time_applying' => 'boolean',
        'salary_range_min' => 'decimal:2',
        'salary_range_max' => 'decimal:2',
        'min_acceptable_salary' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function coverLetters()
    {
        return $this->hasMany(CoverLetter::class);
    }

    public function skills()
    {
        return $this->morphMany(Skill::class, 'skillable');
    }
}
