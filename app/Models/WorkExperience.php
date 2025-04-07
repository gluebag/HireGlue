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
        'current_job', 'description', 'skills_used', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_job' => 'boolean',
        'skills_used' => 'array',
        'achievements' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHighLevelSkillsBreakdownAttribute()
    {
        // convert the storage format which is Repeatable line-item to basic array
        // e.g. input format is: [{"type":"line-item-achievement","fields":{"description":"Began self-teaching...."}},{"type":"line-item-achievement","fields":{"description":"Launched PopSheets.com, a website h...s"}}]

        // e.g. output should be just string array of descriptions
        // e.g. ["Began self-teaching....", "Launched PopSheets.com, a website h...s"]
        return collect($this->skills_used)->mapToGroups(function ($skillName, $skillTypeKey) {
            // $skillTypeKey is the type of skill in format of "Hard-1" "Hard-2" "Soft-1" "Soft-2" etc.
            // convert it into grouped format of "Hard" => ["skill1", "skill2"]
            $skillTypeKey = Str::title(explode('-', $skillTypeKey)[0]);
            $skillName = $skillName->name;

            return [$skillTypeKey => $skillName];
        })->toArray();
    }
}
