<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeShiftSeeder extends Seeder
{
    /**
     * Menetapkan shift awal untuk tiap employee.
     * Seeder ini dijalankan setelah EmployeeSeeder dan ShiftSeeder, karena
     * membutuhkan data employee_id dan shift_id yang sudah ada.
     */
    public function run(): void
    {
        $employees = DB::table('employees')
            ->pluck('employee_id', 'employee_number');

        $shifts = DB::table('shifts')
            ->pluck('shift_id', 'code');

        $effectiveDate = '2024-01-01';

        // Director, Manager, HR -> shift Pagi
        $pagiEmployees = ['EMP001', 'EMP002', 'EMP003', 'EMP004', 'EMP005', 'EMP006', 'EMP007'];

        foreach ($pagiEmployees as $employeeNumber) {
            $this->assignShift($employees[$employeeNumber], $shifts['SH-PG-001'], $effectiveDate);
        }

        // 10 IT Staff -> rotasi Pagi, Sore, Malam
        $itShiftCodes = ['SH-PG-001', 'SH-SR-001', 'SH-ML-001'];
        for ($i = 8; $i <= 17; $i++) {
            $employeeNumber = sprintf('EMP%03d', $i);
            $shiftCode = $itShiftCodes[($i - 8) % count($itShiftCodes)];

            $this->assignShift($employees[$employeeNumber], $shifts[$shiftCode], $effectiveDate);
        }

        // HR Staff, Finance Staff, Ops Staff -> shift Pagi
        for ($i = 18; $i <= 27; $i++) {
            $employeeNumber = sprintf('EMP%03d', $i);

            $this->assignShift($employees[$employeeNumber], $shifts['SH-PG-001'], $effectiveDate);
        }
    }

    private function assignShift(int $employeeId, int $shiftId, string $effectiveDate): void
    {
        DB::table('employee_shifts')->updateOrInsert(
            [
                'employee_id' => $employeeId,
                'effective_date' => $effectiveDate,
            ],
            [
                'shift_id' => $shiftId,
                'changed_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}