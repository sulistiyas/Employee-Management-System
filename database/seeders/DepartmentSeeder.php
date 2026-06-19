<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'code' => 'IT',
                'name' => 'Information Technology',
                'description' => 'Information Technology Department',
            ],
            [
                'code' => 'HR',
                'name' => 'Human Resource',
                'description' => 'Human Resource Department',
            ],
            [
                'code' => 'FIN',
                'name' => 'Finance',
                'description' => 'Finance Department',
            ],
            [
                'code' => 'OPS',
                'name' => 'Operations',
                'description' => 'Operations Department',
            ],
            [
                'code' => 'GEN',
                'name' => 'General',
                'description' => 'General Department',
            ],
        ];

        foreach ($departments as $department) {
            DB::table('departments')->updateOrInsert(
                ['code' => $department['code']],
                array_merge($department, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}