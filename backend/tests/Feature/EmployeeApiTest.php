<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Automated grading test — backend track.
 *
 * Test ini SENGAJA akan gagal sebelum EmployeeController diimplementasikan.
 * Semua test WAJIB berstatus PASS sebagai syarat kelulusan track backend.
 *
 * Jalankan dengan: php artisan test tests/Feature/EmployeeApiTest.php
 */
class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_employees(): void
    {
        Employee::factory()->count(3)->create();

        $response = $this->getJson('/api/employees');

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(3, count($response->json('data') ?? $response->json()));
    }

    public function test_can_filter_employees_by_department(): void
    {
        Employee::factory()->create(['department' => 'Engineering']);
        Employee::factory()->create(['department' => 'Sales']);

        $response = $this->getJson('/api/employees?department=Engineering');

        $response->assertStatus(200);
        $data = $response->json('data') ?? $response->json();
        foreach ($data as $employee) {
            $this->assertEquals('Engineering', $employee['department']);
        }
    }

    public function test_can_create_employee_with_valid_data(): void
    {
        $payload = [
            'name' => 'Budi Santoso',
            'department' => 'Engineering',
            'position' => 'Backend Developer',
            'years_at_company' => 2,
            'monthly_salary' => 8000000,
            'satisfaction_score' => 0.7,
            'last_evaluation' => 0.8,
        ];

        $response = $this->postJson('/api/employees', $payload);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Budi Santoso']);
        $this->assertDatabaseHas('employees', ['name' => 'Budi Santoso']);
    }

    public function test_cannot_create_employee_without_required_fields(): void
    {
        $response = $this->postJson('/api/employees', [
            'department' => 'Engineering',
        ]);

        // TODO(intern): controller harus melakukan validasi request sehingga
        // request tanpa "name" mengembalikan status 422 (Unprocessable Entity).
        $response->assertStatus(422);
    }

    public function test_can_show_single_employee(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/employees/{$employee->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $employee->id]);
    }

    public function test_can_update_employee(): void
    {
        $employee = Employee::factory()->create(['position' => 'Junior Developer']);

        $response = $this->putJson("/api/employees/{$employee->id}", [
            'name' => $employee->name,
            'department' => $employee->department,
            'position' => 'Senior Developer',
            'years_at_company' => $employee->years_at_company,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'position' => 'Senior Developer',
        ]);
    }

    public function test_can_delete_employee(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/employees/{$employee->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_attrition_risk_endpoint_returns_expected_shape(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/employees/{$employee->id}/attrition-risk");

        $response->assertStatus(200);
        $response->assertJsonStructure(['risk_level', 'probability']);

        $riskLevel = $response->json('risk_level');
        $this->assertContains($riskLevel, ['low', 'medium', 'high']);

        $probability = $response->json('probability');
        $this->assertIsFloat((float) $probability);
        $this->assertGreaterThanOrEqual(0.0, $probability);
        $this->assertLessThanOrEqual(1.0, $probability);
    }

    public function test_returns_404_for_nonexistent_employee(): void
    {
        $response = $this->getJson('/api/employees/999999');

        $response->assertStatus(404);
    }
}
