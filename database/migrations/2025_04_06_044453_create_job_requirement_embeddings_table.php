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

        Schema::create('job_requirement_embeddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained();
            $table->enum('requirement_type', ['skills', 'experience', 'education', 'full_description']);
            $table->json('embedding');
            $table->text('requirement_text');
            $table->timestamps();

            $table->unique(['job_post_id', 'requirement_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requirement_embeddings');
    }
};
