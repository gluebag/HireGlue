<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'type', 'source', 'importance', 'validation_logic'
    ];

    protected $casts = [
        'validation_logic' => 'array',
        'importance' => 'integer'
    ];
}
