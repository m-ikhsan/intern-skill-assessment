<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 *
 * Letakkan file ini di database/factories/EmployeeFactory.php pada project Laravel Anda.
 * Dibutuhkan oleh tests/Feature/EmployeeApiTest.php.
 */
class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'department' => $this->faker->randomElement(['Engineering', 'Sales', 'HR', 'Finance', 'Marketing']),
            'position' => $this->faker->jobTitle(),
            'years_at_company' => $this->faker->numberBetween(0, 15),
            'monthly_salary' => $this->faker->numberBetween(4000000, 25000000),
            'satisfaction_score' => $this->faker->randomFloat(2, 0, 1),
            'last_evaluation' => $this->faker->randomFloat(2, 0, 1),
            'attrition' => $this->faker->boolean(20),
            'attrition_risk' => $this->faker->randomElement(['low', 'medium', 'high']),
        ];
    }
}
