<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'type', 'proficiency', 'years_experience', 'proficiency_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
