<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillEmbedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_id',
        'embedding',
        'skill_description',
    ];

    /**
     * Get the user that owns the skill embedding.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skill that the embedding is for.
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
