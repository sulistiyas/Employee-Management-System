<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Constraint unique sebelumnya (employee_id, shift_id, effective_date) tidak
     * mencegah satu karyawan punya 2 shift aktif berbeda di tanggal yang sama.
     * Karena pola assignment-nya fixed (1 karyawan = 1 shift aktif), constraint
     * yang benar adalah (employee_id, effective_date) saja.
     *
     * Kolom changed_by ditambahkan untuk audit trail: siapa admin/HR yang
     * melakukan assignment.
     */
    public function up(): void
    {
        Schema::table('employee_shifts', function (Blueprint $table) {
            $table->dropUnique(['employee_id', 'shift_id', 'effective_date']);
            $table->unique(['employee_id', 'effective_date']);

            $table->foreignId('changed_by')
                ->nullable()
                ->after('effective_date')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_shifts', function (Blueprint $table) {
            $table->dropForeign(['changed_by']);
            $table->dropColumn('changed_by');

            $table->dropUnique(['employee_id', 'effective_date']);
            $table->unique(['employee_id', 'shift_id', 'effective_date']);
        });
    }
};
