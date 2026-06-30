<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentAssignmentSeeder extends Seeder
{
    /**
     * Menetapkan manager dan HR penanggung jawab untuk tiap department.
     * Seeder ini dijalankan setelah EmployeeSeeder, karena membutuhkan
     * data employee yang sudah ada untuk dijadikan referensi.
     */
    public function run(): void
    {
        $employees = DB::table('employees')
            ->pluck('employee_id', 'employee_number');

        $assignments = [
            'IT' => [
                'manager_employee_id' => $employees['EMP002'], // IT Manager 1
                'hr_employee_id' => $employees['EMP005'], // HR Officer 1
            ],
            'HR' => [
                'manager_employee_id' => $employees['EMP004'], // HR Manager
                'hr_employee_id' => $employees['EMP006'], // HR Officer 2
            ],
        ];

        foreach ($assignments as $code => $assignment) {
            DB::table('departments')
                ->where('code', $code)
                ->update(array_merge($assignment, [
                    'updated_at' => now(),
                ]));
        }
    }
}