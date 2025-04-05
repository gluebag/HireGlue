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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('job_post_id')->constrained();
            $table->text('content');
            $table->string('file_path')->nullable();
            $table->integer('word_count')->nullable();
            $table->json('skills_included')->nullable();
            $table->json('experiences_included')->nullable();
            $table->json('education_included')->nullable();
            $table->json('projects_included')->nullable();
            $table->json('rule_compliance')->nullable(); // Track which rules were followed
            $table->json('generation_metadata')->nullable(); // Store OpenAI generation details
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
