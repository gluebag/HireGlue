<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory;

    const TYPE_TECHNICAL = 'technical';
    const TYPE_SOFT = 'soft';
    const TYPE_DOMAIN = 'domain';
    const TYPE_TOOL = 'tool';
    const TYPE_WORK_EXPERIENCE = 'work_experience';
    const TYPE_LANGUAGE = 'language';
    const TYPE_OTHER = 'other';

    const REQUIREMENT_TYPE_IDEAL = 'ideal';
    const REQUIREMENT_TYPE_PREFERRED = 'preferred';
    const REQUIREMENT_TYPE_REQUIRED = 'required';

    const PROFICIENCY_REASON_TYPE_JOB_POST_DESCRIPTION = 'job_post_description';
    const PROFICIENCY_REASON_TYPE_PROJECT = 'project';
    const PROFICIENCY_REASON_TYPE_WORK = 'work';
    const PROFICIENCY_REASON_TYPE_GITHUB = 'github';
    const PROFICIENCY_REASON_TYPE_LOCAL_CODE = 'local_code';
    const PROFICIENCY_REASON_TYPE_OTHER = 'other';


    const TYPE_MAP = [
        self::TYPE_TECHNICAL => 'Technical',
        self::TYPE_SOFT => 'Soft Skill',
        self::TYPE_DOMAIN => 'Domain Knowledge',
        self::TYPE_TOOL => 'Tool/Software',
        self::TYPE_WORK_EXPERIENCE => 'Work Experience',
        self::TYPE_LANGUAGE => 'Language',
        self::TYPE_OTHER => 'Other',
    ];

    const REQUIREMENT_TYPE_MAP = [
        self::REQUIREMENT_TYPE_IDEAL => 'Ideal',
        self::REQUIREMENT_TYPE_PREFERRED => 'Preferred',
        self::REQUIREMENT_TYPE_REQUIRED => 'Required',
    ];

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
