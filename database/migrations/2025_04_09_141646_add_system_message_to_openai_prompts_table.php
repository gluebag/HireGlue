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
            $table->text('system_message')->nullable()->after('type');

            // add FULL_TEXT index to system_message column and prompt_template column
            $table->fullText('system_message');
            $table->fullText('prompt_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('openai_prompts', function (Blueprint $table) {
            // drop FULL_TEXT index from system_message column and prompt_template column
//            $table->dropFullText('system_message');
//            $table->dropFullText('prompt_template');
            $table->dropColumn('system_message');
        });
    }
};
