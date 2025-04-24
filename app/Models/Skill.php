<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory;

    const PROFICIENCY_REASON_TYPE_JOB_POST_DESCRIPTION = 'job_post_description';
    const PROFICIENCY_REASON_TYPE_PROJECT = 'project';
    const PROFICIENCY_REASON_TYPE_WORK = 'work';
    const PROFICIENCY_REASON_TYPE_GITHUB = 'github';
    const PROFICIENCY_REASON_TYPE_LOCAL_CODE = 'local_code';
    const PROFICIENCY_REASON_TYPE_OTHER = 'other';

    const PROFICIENCY_REASON_TYPE_MAP = [
        self::PROFICIENCY_REASON_TYPE_PROJECT =>  'Direct Experience (Project)',
        self::PROFICIENCY_REASON_TYPE_WORK =>  'Direct Experience (Work)',
        self::PROFICIENCY_REASON_TYPE_JOB_POST_DESCRIPTION => 'Job Post (Analysis)',
        self::PROFICIENCY_REASON_TYPE_GITHUB => 'GitHub/Portfolio (Analysis)',
        self::PROFICIENCY_REASON_TYPE_LOCAL_CODE => 'Local Code (Analysis)',
        self::PROFICIENCY_REASON_TYPE_OTHER => 'Other',
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skillable()
    {
        return $this->morphTo();
    }
}
