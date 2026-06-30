<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $departments = DB::table('departments')
            ->pluck('department_id', 'code');

        $positions = DB::table('positions')
            ->pluck('position_id', 'name');

        $employees = [

            // DIRECTOR
            [
                'employee_number' => 'EMP001',
                'department_id' => $departments['IT'],
                'position_id' => $positions['it-director'],
                'full_name' => 'System Director',
            ],

            // MANAGERS
            [
                'employee_number' => 'EMP002',
                'department_id' => $departments['IT'],
                'position_id' => $positions['it-manager'],
                'full_name' => 'IT Manager 1',
            ],
            [
                'employee_number' => 'EMP003',
                'department_id' => $departments['IT'],
                'position_id' => $positions['it-manager'],
                'full_name' => 'IT Manager 2',
            ],
            [
                'employee_number' => 'EMP004',
                'department_id' => $departments['HR'],
                'position_id' => $positions['hr-manager'],
                'full_name' => 'HR Manager',
            ],

            // HR
            [
                'employee_number' => 'EMP005',
                'department_id' => $departments['HR'],
                'position_id' => $positions['hr-officer'],
                'full_name' => 'HR Officer 1',
            ],
            [
                'employee_number' => 'EMP006',
                'department_id' => $departments['HR'],
                'position_id' => $positions['hr-officer'],
                'full_name' => 'HR Officer 2',
            ],

            // SUPER ADMIN EMPLOYEE
            [
                'employee_number' => 'EMP007',
                'department_id' => $departments['IT'],
                'position_id' => $positions['it-manager'],
                'full_name' => 'Super Administrator',
            ],
        ];

        // 10 IT STAFF
        for ($i = 8; $i <= 17; $i++) {
            $employees[] = [
                'employee_number' => sprintf('EMP%03d', $i),
                'department_id' => $departments['IT'],
                'position_id' => $positions['it-staff'],
                'full_name' => 'IT Staff ' . ($i - 7),
            ];
        }

        // 5 HR STAFF
        for ($i = 18; $i <= 22; $i++) {
            $employees[] = [
                'employee_number' => sprintf('EMP%03d', $i),
                'department_id' => $departments['HR'],
                'position_id' => $positions['hr-officer'],
                'full_name' => 'HR Staff ' . ($i - 17),
            ];
        }

        // 3 FINANCE STAFF
        for ($i = 23; $i <= 25; $i++) {
            $employees[] = [
                'employee_number' => sprintf('EMP%03d', $i),
                'department_id' => $departments['FIN'],
                'position_id' => $positions['finance-staff'],
                'full_name' => 'Finance Staff ' . ($i - 22),
            ];
        }

        // 2 OPS STAFF
        for ($i = 26; $i <= 27; $i++) {
            $employees[] = [
                'employee_number' => sprintf('EMP%03d', $i),
                'department_id' => $departments['OPS'],
                'position_id' => $positions['operations-staff'],
                'full_name' => 'Operations Staff ' . ($i - 25),
            ];
        }

        foreach ($employees as $index => $employee) {
            DB::table('employees')->updateOrInsert(
                [
                    'employee_number' => $employee['employee_number'],
                ],
                array_merge($employee, [
                    'gender' => $index % 2 === 0 ? 'Male' : 'Female',
                    'birth_date' => '1990-01-01',
                    'phone' => '0812345678' . str_pad($index, 2, '0', STR_PAD_LEFT),
                    'address' => 'Jakarta, Indonesia',
                    'join_date' => '2024-01-01',
                    'employment_status' => 'active',
                    'photo' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}