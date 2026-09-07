<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Dedi Suryadi',
                'department' => 'Engineering',
                'position' => 'Software Engineer',
                'years_at_company' => 8,
                'monthly_salary' => 11000000,
                'satisfaction_score' => 0.30,
                'last_evaluation' => 0.82,
                'attrition' => true,
                'attrition_risk' => 'high',
            ],
            [
                'name' => 'Sri Kusuma',
                'department' => 'Engineering',
                'position' => 'Tech Lead',
                'years_at_company' => 1,
                'monthly_salary' => 14000000,
                'satisfaction_score' => 0.18,
                'last_evaluation' => 0.46,
                'attrition' => true,
                'attrition_risk' => 'high',
            ],
            [
                'name' => 'Tika Pratama',
                'department' => 'Marketing',
                'position' => 'Content Strategist',
                'years_at_company' => 7,
                'monthly_salary' => 8500000,
                'satisfaction_score' => 0.63,
                'last_evaluation' => 0.87,
                'attrition' => false,
                'attrition_risk' => 'low',
            ],
            [
                'name' => 'Hendra Wijaya',
                'department' => 'Finance',
                'position' => 'Accountant',
                'years_at_company' => 5,
                'monthly_salary' => 8000000,
                'satisfaction_score' => 0.29,
                'last_evaluation' => 0.83,
                'attrition' => false,
                'attrition_risk' => 'medium',
            ],
            [
                'name' => 'Siti Rahma',
                'department' => 'HR',
                'position' => 'HR Specialist',
                'years_at_company' => 4,
                'monthly_salary' => 7500000,
                'satisfaction_score' => 0.76,
                'last_evaluation' => 0.68,
                'attrition' => false,
                'attrition_risk' => 'low',
            ],
            [
                'name' => 'Wahyu Saputra',
                'department' => 'Marketing',
                'position' => 'Social Media Specialist',
                'years_at_company' => 2,
                'monthly_salary' => 6000000,
                'satisfaction_score' => 0.27,
                'last_evaluation' => 0.35,
                'attrition' => true,
                'attrition_risk' => 'high',
            ],
            [
                'name' => 'Lina Lestari',
                'department' => 'Engineering',
                'position' => 'QA Engineer',
                'years_at_company' => 3,
                'monthly_salary' => 9500000,
                'satisfaction_score' => 0.85,
                'last_evaluation' => 0.74,
                'attrition' => false,
                'attrition_risk' => 'low',
            ],
            [
                'name' => 'Diah Kusuma',
                'department' => 'Sales',
                'position' => 'Sales Manager',
                'years_at_company' => 8,
                'monthly_salary' => 15000000,
                'satisfaction_score' => 0.68,
                'last_evaluation' => 0.73,
                'attrition' => false,
                'attrition_risk' => 'medium',
            ],
            [
                'name' => 'Fitri Lestari',
                'department' => 'Sales',
                'position' => 'Account Executive',
                'years_at_company' => 2,
                'monthly_salary' => 7000000,
                'satisfaction_score' => 0.90,
                'last_evaluation' => 0.75,
                'attrition' => false,
                'attrition_risk' => 'low',
            ],
            [
                'name' => 'Budi Santoso',
                'department' => 'Engineering',
                'position' => 'Backend Developer',
                'years_at_company' => 3,
                'monthly_salary' => 10500000,
                'satisfaction_score' => 0.52,
                'last_evaluation' => 0.80,
                'attrition' => false,
                'attrition_risk' => 'medium',
            ],
            [
                'name' => 'Agus Setiawan',
                'department' => 'Finance',
                'position' => 'Financial Analyst',
                'years_at_company' => 6,
                'monthly_salary' => 11000000,
                'satisfaction_score' => 0.88,
                'last_evaluation' => 0.91,
                'attrition' => false,
                'attrition_risk' => 'low',
            ],
            [
                'name' => 'Ratna Sari',
                'department' => 'HR',
                'position' => 'Recruiter',
                'years_at_company' => 1,
                'monthly_salary' => 6500000,
                'satisfaction_score' => 0.40,
                'last_evaluation' => 0.50,
                'attrition' => false,
                'attrition_risk' => 'medium',
            ],
        ];

        foreach ($employees as $employeeData) {
            Employee::forceCreate($employeeData);
        }
    }
}
