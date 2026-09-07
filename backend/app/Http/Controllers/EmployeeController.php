<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * GET /api/employees
     * 
     * TODO(intern): 
     * 1. Support query parameter `?department=` untuk memfilter karyawan berdasarkan departemen.
     * 2. Support query parameter `?risk=` untuk memfilter berdasarkan tingkat risiko attrition ('low'|'medium'|'high').
     * 3. Pertahankan pagination (misal 10 data per halaman).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Employee::query();

        // TODO(intern): Tambahkan filter department di sini:
        // if ($request->filled('department')) {
        //     $query->where('department', $request->query('department'));
        // }

        // TODO(intern): Tambahkan filter risk di sini:
        // if ($request->filled('risk')) {
        //     $query->where('attrition_risk', $request->query('risk'));
        // }

        $employees = $query->paginate(10);

        return response()->json($employees);
    }

    /**
     * POST /api/employees
     * 
     * TODO(intern):
     * Lakukan validasi request sebelum create.
     * Field wajib: 'name' (string). Field lainnya: 'department', 'position', dsb.
     * Jika request tidak valid (misal 'name' tidak dikirim), Laravel otomatis mengembalikan HTTP 422.
     * Contoh:
     *   $validated = $request->validate([
     *       'name' => 'required|string|max:255',
     *       'department' => 'required|string',
     *       'position' => 'required|string',
     *   ]);
     */
    public function store(Request $request): JsonResponse
    {
        // TODO(intern): Implementasikan $request->validate(...) di sini sebelum create
        $employee = Employee::create($request->all());

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
     * 
     * TODO(intern):
     * Lakukan validasi request saat update:
     *   $validated = $request->validate([...]);
     *   $employee->update($validated);
     */
    public function update(Request $request, Employee $employee): JsonResponse
    {
        // TODO(intern): Implementasikan validasi update di sini
        $employee->update($request->all());

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
     * 
     * Mengembalikan data estimasi risiko resign untuk 1 karyawan:
     * { "risk_level": "low|medium|high", "probability": float }
     * 
     * TODO(intern):
     * Anda dapat mengkalkulasi risiko berdasarkan data karyawan (misal: satisfaction_score rendah & years_at_company tinggi),
     * atau mengintegrasikan dengan memanggil modul Machine Learning di http://localhost:5000/predict.
     */
    public function attritionRisk(Employee $employee): JsonResponse
    {
        // Simulasi dasar default (sudah lolos automated test shape)
        $probability = $employee->attrition_risk === 'high' ? 0.78 : ($employee->attrition_risk === 'medium' ? 0.45 : 0.15);

        return response()->json([
            'risk_level' => $employee->attrition_risk ?? 'low',
            'probability' => $probability,
        ]);
    }
}
