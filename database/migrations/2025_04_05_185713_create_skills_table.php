<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();

            $table->morphs('skillable');

            $table->string('name');

            $table->unique(['skillable_id', 'skillable_type', 'name'], 'unique_skill_name');

            $table->enum('type', ['technical', 'soft', 'domain', 'tool', 'work_experience', 'language', 'other'])
                ->nullable()
                ->comment('Type of skill. Technical, Soft Skill, Domain Knowledge, Tool/Software, Language, Other');

            $table->integer('years_experience')->nullable()
                ->comment('Estimated years of experience in this skill');

            $table->integer('proficiency')->nullable()
                ->comment('Proficiency level from 1 to 10');

            $table->enum('proficiency_reason_type', ['job_post_description', 'project', 'work', 'github', 'local_code', 'other'])
                ->nullable()
                ->comment('Type of reason for the proficiency level. Job Post Analysis, Project Experience, Work Experience/Achievements, GitHub/Portfolio Analysis, Local Code Analysis, Other');

            $table->text('proficiency_reason')->nullable()
                ->comment('Reason for the proficiency level (e.g., projects, reference in job post description, etc.)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
