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
        Schema::table('openai_prompts', function (Blueprint $table) {
            $table->unsignedInteger('last_history_id')->nullable()->after('id');
            $table->text('examples_message')->nullable()->after('prompt_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('openai_prompts', function (Blueprint $table) {
            $table->dropColumn('last_history_id');
            $table->dropColumn('examples_message');
        });
    }
};
