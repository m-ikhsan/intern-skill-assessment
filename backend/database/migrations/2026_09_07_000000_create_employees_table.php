<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // TODO(intern): lengkapi kolom sesuai skema Employee di PRD Section 7.4
            $table->string('name');
            $table->string('department');
            $table->string('position');
            $table->unsignedInteger('years_at_company')->default(0);
            $table->unsignedInteger('monthly_salary')->nullable();
            $table->float('satisfaction_score')->nullable();
            $table->float('last_evaluation')->nullable();
            $table->boolean('attrition')->default(false);
            $table->enum('attrition_risk', ['low', 'medium', 'high'])->default('low');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
