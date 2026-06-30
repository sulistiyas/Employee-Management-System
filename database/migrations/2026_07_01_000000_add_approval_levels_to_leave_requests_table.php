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
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'approved_at']);

            $table->foreignId('manager_approved_by')->nullable()->after('reason')
                ->references('employee_id')->on('employees')->nullOnDelete();
            $table->timestamp('manager_approved_at')->nullable()->after('manager_approved_by');

            $table->foreignId('hr_approved_by')->nullable()->after('manager_approved_at')
                ->references('employee_id')->on('employees')->nullOnDelete();
            $table->timestamp('hr_approved_at')->nullable()->after('hr_approved_by');

            $table->foreignId('director_approved_by')->nullable()->after('hr_approved_at')
                ->references('employee_id')->on('employees')->nullOnDelete();
            $table->timestamp('director_approved_at')->nullable()->after('director_approved_by');

            $table->string('rejected_at_level')->nullable()->after('director_approved_at');
            $table->text('rejection_reason')->nullable()->after('rejected_at_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['manager_approved_by']);
            $table->dropForeign(['hr_approved_by']);
            $table->dropForeign(['director_approved_by']);

            $table->dropColumn([
                'manager_approved_by',
                'manager_approved_at',
                'hr_approved_by',
                'hr_approved_at',
                'director_approved_by',
                'director_approved_at',
                'rejected_at_level',
                'rejection_reason',
            ]);

            $table->foreignId('approved_by')->nullable()
                ->references('employee_id')->on('employees')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
        });
    }
};
