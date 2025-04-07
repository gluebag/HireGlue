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

    protected $appends = ['achievements_breakdown'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAchievementsBreakdownAttribute()
    {
        // convert the storage format which is Repeatable line-item to basic array
        // e.g. input format is: [{"type":"line-item-achievement","fields":{"description":"Began self-teaching...."}},{"type":"line-item-achievement","fields":{"description":"Launched PopSheets.com, a website h...s"}}]

        // e.g. output should be just string array of descriptions
        // e.g. ["Began self-teaching....", "Launched PopSheets.com, a website h...s"]
        return collect($this->achievements)
            ->map(function ($item) {
                return $item['fields']['description'] ?? null;
            })
            ->filter()
            ->all();
    }
}
