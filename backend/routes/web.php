<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'EMS - Employee Management & Attrition System API',
        'status' => 'online',
        'version' => '1.0.0',
        'endpoints' => [
            'employees' => '/api/employees',
            'attrition_risk' => '/api/employees/{id}/attrition-risk',
        ],
    ]);
});
