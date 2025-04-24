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
        Schema::table('job_posts', function (Blueprint $table) {
            // Add new fields for Claude-based job post analysis
            $table->string('job_id')->nullable()->after('job_type');
            $table->string('application_url')->nullable()->after('job_post_url');
            $table->string('team')->nullable()->after('application_url');
            $table->json('locations')->nullable()->after('job_location_type');
            $table->date('posted_date')->nullable()->after('job_post_date');

            // Add biggest challenge fields
            $table->text('biggest_challenge_description')->nullable()->after('first_time_applying');
            $table->text('biggest_challenge_root_cause')->nullable()->after('biggest_challenge_description');

            // Add ideal skills field
            $table->json('ideal_skills')->nullable()->after('preferred_skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn([
                'job_id',
                'application_url',
                'team',
                'locations',
                'posted_date',
                'biggest_challenge_description',
                'biggest_challenge_root_cause',
                'ideal_skills'
            ]);
        });
    }
};
