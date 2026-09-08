<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * GET /api/employees
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'department' => ['sometimes', 'string', 'max:255'],
            'risk' => ['sometimes', 'in:low,medium,high'],
        ]);
        $query = Employee::query();

        if ($request->filled('department')) {
        $query->where('department', $request->query('department'));
    }

        if ($request->filled('risk')) {
        $query->where('attrition_risk', $request->query('risk'));
    }

        $employees = $query->paginate(10);

        return response()->json($employees);
    }

    /**
     * POST /api/employees
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'sometimes|in:Engineering,Sales,HR,Finance,Marketing',
            'position' => 'sometimes|string|max:255',
            'years_at_company' => 'sometimes|integer|min:0',
            'monthly_salary' => 'sometimes|nullable|integer|min:0',
            'satisfaction_score' => 'sometimes|nullable|numeric|min:0|max:1',
            'last_evaluation' => 'sometimes|nullable|numeric|min:0|max:1',
    ]);
        // $employee = Employee::create($request->all());
        $employee = Employee::create($validated);

        return response()->json($employee, 201);
    }

    /**
     * GET /api/employees/{employee}
     */
    public function show(Employee $employee): JsonResponse
    {
        return response()->json($employee);
    }

    /**
     * PUT /api/employees/{employee}
     */
    public function update(Request $request, Employee $employee): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'department' => 'sometimes|in:Engineering,Sales,HR,Finance,Marketing',
            'position' => 'sometimes|string|max:255',
            'years_at_company' => 'sometimes|integer|min:0',
            'monthly_salary' => 'sometimes|nullable|integer|min:0',
            'satisfaction_score' => 'sometimes|nullable|numeric|min:0|max:1',
            'last_evaluation' => 'sometimes|nullable|numeric|min:0|max:1',
    ]);
        // $employee->update($request->all());
        $employee->update($validated);

        return response()->json($employee);
    }

    /**
     * DELETE /api/employees/{employee}
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();

        return response()->json(null, 204);
    }

    /**
     * GET /api/employees/{employee}/attrition-risk
     */
    public function attritionRisk(Employee $employee): JsonResponse
    {
        // Mengembalikan hasil perhitungan risiko resign dalam bentuk JSON
        return response()->json(
            $this->calculateAttritionRisk($employee)
        );
    }

/**
 * Menghitung risiko resign menggunakan simulasi berbasis aturan sederhana.
 *
 * - Kepuasan kerja yang lebih rendah meningkatkan kemungkinan resign.
 * - Masa kerja yang lebih pendek sedikit meningkatkan kemungkinan resign.
 */
    private function calculateAttritionRisk(Employee $employee): array
    {
        // Mengambil nilai kepuasan kerja, default 0.5 jika tidak tersedia
        $satisfaction = (float) ($employee->satisfaction_score ?? 0.5);

        // Mengambil lama bekerja, default 0 tahun jika tidak tersedia
        $tenure = (int) ($employee->years_at_company ?? 0);

        // Menghitung probabilitas resign dan membatasi nilainya antara 0 sampai 1
        $probability = round(
        max(0, min(1, (1 - $satisfaction) * 0.7 + max(0, (3 - $tenure) / 10))),
        2
    );

        // Menentukan tingkat risiko berdasarkan nilai probabilitas
        $riskLevel = match (true) {
            $probability >= 0.6 => 'high',
            $probability >= 0.3 => 'medium',
            default => 'low',
        };

        // Mengembalikan tingkat risiko dan probabilitas
        return [
            'risk_level' => $riskLevel,
            'probability' => $probability
        ];
    }
}