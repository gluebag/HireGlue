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
        Schema::create('prompt_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prompt_id')->constrained('openai_prompts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('status')->default('pending');
            $table->string('src_class')->nullable();
            $table->string('src_function')->nullable();
            $table->text('src_stack')->nullable();

            $table->unsignedBigInteger('tokens_used')->nullable();
            $table->unsignedInteger('elapsed_time')->nullable();
            $table->text('api_response')->nullable();

            $table->json('model_config')->nullable();
            $table->text('system_message')->nullable();
            $table->json('user_messages')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompt_history');
    }
};
