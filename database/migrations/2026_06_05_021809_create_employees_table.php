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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('employee_number')->unique();
            $table->foreignId('department_id')->references('department_id')->on('departments')->restrictOnDelete();
            $table->foreignId('position_id')->references('position_id')->on('positions')->restrictOnDelete();
            $table->string('full_name');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date');
            $table->string('employment_status'); // e.g. active, resigned, terminated
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
