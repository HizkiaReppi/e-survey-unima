<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => rand(10000, 99999) . 'MB',
            'period_id' => Period::factory(),
            'department_id' => Department::factory()
        ];
    }
}