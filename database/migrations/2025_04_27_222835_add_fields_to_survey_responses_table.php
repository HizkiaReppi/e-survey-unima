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
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->foreignUuid('lecturer_id')->nullable()->constrained('lecturers')->onDelete('cascade')->after('user_id');
            $table->foreignUlid('course_id')->nullable()->constrained('courses')->onDelete('cascade')->after('lecturer_id');
            $table->unsignedTinyInteger('credits')->nullable()->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn(['lecturer_id', 'course_id', 'credits']);
        });
    }
};
