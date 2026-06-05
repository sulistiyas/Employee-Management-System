<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id('employee_shift_id');
            $table->foreignId('employee_id')->references('employee_id')->on('employees')->cascadeOnDelete();
            $table->foreignId('shift_id')->references('shift_id')->on('shifts')->restrictOnDelete();
            $table->date('effective_date');
            $table->timestamps();

            $table->unique(['employee_id', 'shift_id', 'effective_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_shifts');
    }
};
