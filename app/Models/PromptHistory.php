<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptHistory extends Model
{
    protected $table = 'prompt_history';

    protected $guarded = [];

    protected $casts = [
        'model_config' => 'array',
        'user_messages' => 'array',
        'api_response' => 'array',
        'system_message' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prompt()
    {
        return $this->belongsTo(OpenAiPrompt::class);
    }


}
