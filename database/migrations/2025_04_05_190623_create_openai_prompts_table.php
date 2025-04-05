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
        Schema::create('openai_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['resume', 'cover_letter', 'analysis', 'rule_check']);
            $table->text('prompt_template');
            $table->json('parameters')->nullable();
            $table->string('model')->default('gpt-4o');
            $table->integer('max_tokens')->default(2000);
            $table->decimal('temperature', 2, 1)->default(0.7);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('openai_prompts');
    }
};
