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

        Schema::table('education', function (Blueprint $table) {
            if(!Schema::hasIndex('education', ['user_id', 'institution', 'degree'], 'unique')) {
                $table->unique(['user_id', 'institution', 'degree']);
            }
        });

        Schema::table('job_posts', function (Blueprint $table) {
            if(!Schema::hasIndex('job_posts', ['user_id', 'company_name', 'job_title'], 'unique')) {
                $table->unique(['user_id', 'company_name', 'job_title']);
            }
        });

        Schema::table('openai_prompts', function (Blueprint $table) {
            if(!Schema::hasIndex('openai_prompts', ['name', 'type'], 'unique')) {
                $table->unique(['name', 'type']);
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if(!Schema::hasIndex('projects', ['user_id', 'name'], 'unique')) {
                $table->unique(['user_id', 'name']);
            }
        });

        Schema::table('rules', function (Blueprint $table) {
            if(!Schema::hasIndex('rules', ['name', 'type'], 'unique')) {
                $table->unique(['name', 'type']);
            }
        });

        Schema::table('skills', function (Blueprint $table) {
            if(!Schema::hasIndex('skills', ['user_id', 'name'], 'unique')) {
                $table->unique(['user_id', 'name']);
            }
        });

        Schema::table('work_experiences', function (Blueprint $table) {
            if(!Schema::hasIndex('work_experiences', ['user_id', 'company_name', 'position'], 'unique')) {
                $table->unique(['user_id', 'company_name', 'position']);
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'institution', 'degree']);
        });

        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'company_name', 'job_title']);
        });

        Schema::table('openai_prompts', function (Blueprint $table) {
            $table->dropUnique(['name', 'type']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });

        Schema::table('rules', function (Blueprint $table) {
            $table->dropUnique(['name', 'type']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });

        Schema::table('work_experiences', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'company_name', 'position']);
        });
    }
};
