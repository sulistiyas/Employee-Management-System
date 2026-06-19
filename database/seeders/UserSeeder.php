<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = DB::table('roles')
            ->pluck('role_id', 'slug');

        $employees = DB::table('employees')
            ->pluck('employee_id', 'employee_number');

        $users = [

            // SUPER ADMIN
            [
                'employee_number' => 'EMP007',
                'role' => 'super-admin',
                'name' => 'Super Administrator',
                'email' => 'superadmin@ems.test',
            ],

            // DIRECTOR
            [
                'employee_number' => 'EMP001',
                'role' => 'director',
                'name' => 'System Director',
                'email' => 'director@ems.test',
            ],

            // MANAGERS
            [
                'employee_number' => 'EMP002',
                'role' => 'manager',
                'name' => 'IT Manager 1',
                'email' => 'manager1@ems.test',
            ],
            [
                'employee_number' => 'EMP003',
                'role' => 'manager',
                'name' => 'IT Manager 2',
                'email' => 'manager2@ems.test',
            ],
            [
                'employee_number' => 'EMP004',
                'role' => 'manager',
                'name' => 'HR Manager',
                'email' => 'manager3@ems.test',
            ],

            // HR
            [
                'employee_number' => 'EMP005',
                'role' => 'hr',
                'name' => 'HR Officer 1',
                'email' => 'hr1@ems.test',
            ],
            [
                'employee_number' => 'EMP006',
                'role' => 'hr',
                'name' => 'HR Officer 2',
                'email' => 'hr2@ems.test',
            ],
        ];

        // STAFF 1 - 20
        for ($i = 8; $i <= 27; $i++) {
            $staffNo = $i - 7;

            $users[] = [
                'employee_number' => sprintf('EMP%03d', $i),
                'role' => 'staff',
                'name' => "Staff {$staffNo}",
                'email' => "staff{$staffNo}@ems.test",
            ];
        }

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'employee_id' => $employees[$user['employee_number']],
                    'role_id' => $roles[$user['role']],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'last_login_at' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}