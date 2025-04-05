<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'description', 'start_date', 'end_date',
        'url', 'technologies_used', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'technologies_used' => 'array',
        'achievements' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
