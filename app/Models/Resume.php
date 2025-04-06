<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_post_id',
        'thread_session_id',
        'content',
        'file_path',
        'word_count',
        'skills_included',
        'experiences_included',
        'education_included',
        'projects_included',
        'rule_compliance',
        'generation_metadata'
    ];

    protected $casts = [
        'skills_included' => 'array',
        'experiences_included' => 'array',
        'education_included' => 'array',
        'projects_included' => 'array',
        'rule_compliance' => 'array',
        'generation_metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    /**
     * Get the thread session that generated this resume
     */
    public function threadSession()
    {
        return $this->belongsTo(ThreadSession::class);
    }
}
