<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department',
        'position',
        'years_at_company',
        'monthly_salary',
        'satisfaction_score',
        'last_evaluation',
        'attrition',
        'attrition_risk',
    ];

    protected $casts = [
        'satisfaction_score' => 'float',
        'last_evaluation' => 'float',
        'attrition' => 'boolean',
    ];
}
