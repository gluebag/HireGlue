<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequirementEmbedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'requirement_type',
        'embedding',
        'requirement_text',
    ];

    /**
     * Get the job post that the embedding is for.
     */
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
