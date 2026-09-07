<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

// TODO(intern): pastikan semua route berikut aktif dan sesuai konvensi RESTful

Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees', [EmployeeController::class, 'store']);
Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

Route::get('/employees/{employee}/attrition-risk', [EmployeeController::class, 'attritionRisk']);
