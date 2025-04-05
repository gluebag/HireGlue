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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('company_name');
            $table->string('job_title');
            $table->text('job_description');
            $table->string('job_post_url')->nullable();
            $table->date('job_post_date')->nullable();
            $table->enum('job_location_type', ['remote', 'in-office', 'hybrid'])->default('remote');
            $table->json('required_skills')->nullable();
            $table->json('preferred_skills')->nullable();
            $table->json('required_experience')->nullable();
            $table->json('required_education')->nullable();
            $table->integer('resume_min_words')->default(450);
            $table->integer('resume_max_words')->default(850);
            $table->integer('cover_letter_min_words')->default(450);
            $table->integer('cover_letter_max_words')->default(750);
            $table->integer('resume_min_pages')->default(1);
            $table->integer('resume_max_pages')->default(2);
            $table->integer('cover_letter_min_pages')->default(1);
            $table->integer('cover_letter_max_pages')->default(1);
            $table->text('things_i_like')->nullable();
            $table->text('things_i_dislike')->nullable();
            $table->text('things_i_like_about_company')->nullable();
            $table->text('things_i_dislike_about_company')->nullable();
            $table->boolean('open_to_travel')->default(true);
            $table->decimal('salary_range_min', 10, 2)->nullable();
            $table->decimal('salary_range_max', 10, 2)->nullable();
            $table->decimal('min_acceptable_salary', 10, 2)->nullable();
            $table->enum('position_level', ['entry-level', 'mid-level', 'senior', 'lead', 'manager', 'director', 'executive'])->default('mid-level');
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'internship', 'freelance'])->default('full-time');
            $table->date('ideal_start_date')->nullable();
            $table->integer('position_preference')->default(1); // 1 = top choice, 2 = second choice, etc.
            $table->boolean('first_time_applying')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
