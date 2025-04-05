<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Education extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'education';

    protected $fillable = [
        'user_id', 'institution', 'degree', 'field_of_study',
        'start_date', 'end_date', 'current', 'gpa', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current' => 'boolean',
        'gpa' => 'decimal:2',
        'achievements' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
