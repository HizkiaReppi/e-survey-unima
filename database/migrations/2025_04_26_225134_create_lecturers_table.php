<?php

use App\Enums\AcademicRank;
use App\Enums\CertificationStatus;
use App\Enums\FunctionalPosition;
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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname');
            $table->foreignUlid('department_id')->constrained('departments')->cascadeOnDelete();
            $table->enum('functional_position', array_column(FunctionalPosition::cases(), 'value'))
                  ->nullable();
            $table->enum('academic_rank', array_column(AcademicRank::cases(), 'value'))
                  ->nullable();
            $table->string('employee_status', 50)->nullable()->default(null);
            $table->enum('certification_status', array_column(CertificationStatus::cases(), 'value'))->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
