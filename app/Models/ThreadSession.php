<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThreadSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_post_id',
        'assistant_id',
        'thread_id',
        'type',
        'status',
        'content',
        'error',
        'completed_at',
        'metrics',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'metrics' => 'array',
    ];

    /**
     * Get the user that owns the session
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job post this session is for
     */
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
    
    /**
     * Get the resume associated with this session
     */
    public function resume()
    {
        return $this->hasOne(Resume::class);
    }
    
    /**
     * Get the cover letter associated with this session
     */
    public function coverLetter()
    {
        return $this->hasOne(CoverLetter::class);
    }
    
    /**
     * Check if the session is for a resume
     */
    public function isResumeSession(): bool
    {
        return $this->type === 'resume';
    }
    
    /**
     * Check if the session is for a cover letter
     */
    public function isCoverLetterSession(): bool
    {
        return $this->type === 'cover_letter';
    }
    
    /**
     * Check if the session is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
    
    /**
     * Check if the session failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
    
    /**
     * Check if the session is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === 'processing';
    }
}