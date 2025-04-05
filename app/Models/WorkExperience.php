<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkExperience extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'company_name', 'position', 'start_date', 'end_date',
        'current_job', 'description', 'skills_used', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_job' => 'boolean',
        'skills_used' => 'array',
        'achievements' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
