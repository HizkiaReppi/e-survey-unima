<?php

namespace Database\Factories;

use App\Enums\AcademicRank;
use App\Enums\CertificationStatus;
use App\Enums\FunctionalPosition;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class LecturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => fake()->name(),
            'department_id' => Department::factory(),
            'functional_position' => fake()->randomElement(array_column(FunctionalPosition::cases(), 'value')),
            'academic_rank' => fake()->randomElement(array_column(AcademicRank::cases(), 'value')),
            'employee_status' => fake()->randomElement(['PNS', 'Non PNS']),
            'certification_status' => fake()->randomElement(array_column(CertificationStatus::cases(), 'value')),
        ];
    }
}
