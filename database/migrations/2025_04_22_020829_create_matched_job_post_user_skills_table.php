<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // new table for matched job post user skills (aka: What skills does the job post require or prefer that match the user's skills, with a match_score)
        Schema::create('matched_job_post_user_skills', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->comment('User ID from the users table');
            $table->unsignedBigInteger('job_post_id')->comment('Job Post ID from the job_posts table');
            $table->unsignedBigInteger('user_skill_id')->comment('User Skill ID from the skills table');
            $table->unsignedBigInteger('job_post_skill_id')->comment('Job Post Skill ID from the skills table');

            $table->unique(['user_id', 'job_post_id', 'user_skill_id', 'job_post_skill_id'], 'matched_job_post_user_skills_unique');

            $table->enum('match_type', ['required', 'preferred', 'ideal'])
                ->comment('Type of match: required or preferred skill');

            $table->integer('match_score')->nullable()
                ->comment('Match score from 0 to 100, indicating how well the user\'s skill matches the job post\'s required or preferred skill');

            $table->text('match_reason')->nullable()
                ->comment('Reason for the match score (e.g., projects, reference in job post description, etc.)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matched_job_post_user_skills');
    }
};
