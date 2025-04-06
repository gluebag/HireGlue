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
        Schema::table('resumes', function (Blueprint $table) {
            $table->foreignId('thread_session_id')->nullable()->after('job_post_id')->constrained();
        });

        Schema::table('cover_letters', function (Blueprint $table) {
            $table->foreignId('thread_session_id')->nullable()->after('job_post_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropForeign(['thread_session_id']);
            $table->dropColumn('thread_session_id');
        });

        Schema::table('cover_letters', function (Blueprint $table) {
            $table->dropForeign(['thread_session_id']);
            $table->dropColumn('thread_session_id');
        });
    }
};
