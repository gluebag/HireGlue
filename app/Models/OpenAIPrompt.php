<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenAIPrompt extends Model
{
    use SoftDeletes;

    protected $table = 'openai_prompts';

    protected $fillable = [
        'name', 'type', 'prompt_template', 'parameters',
        'model', 'max_tokens', 'temperature', 'active'
    ];

    protected $casts = [
        'parameters' => 'array',
        'max_tokens' => 'integer',
        'temperature' => 'decimal:1',
        'active' => 'boolean'
    ];
}
