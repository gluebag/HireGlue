<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenAIPrompt extends Model
{
    use SoftDeletes;

    protected $table = 'openai_prompts';

    protected $guarded = [];

    protected $casts = [
        'parameters' => 'array',
        'max_tokens' => 'integer',
        'temperature' => 'decimal:1',
        'active' => 'boolean',
    ];

    public function promptHistory() :\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PromptHistory::class, 'prompt_id');
    }

    public function lastHistory() :\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PromptHistory::class, 'id', 'last_history_id');
    }



//    public function last
}
