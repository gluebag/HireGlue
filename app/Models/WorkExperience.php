<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WorkExperience extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'company_name', 'position', 'start_date', 'end_date',
        'current_job', 'description', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_job' => 'boolean',
        'achievements' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function skills()
    {
        return $this->morphMany(Skill::class, 'skillable');
    }
}
