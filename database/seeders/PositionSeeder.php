<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $departments = DB::table('departments')
            ->pluck('department_id', 'code');

        $positions = [

            // IT
            [
                'department_id' => $departments['IT'],
                'name' => 'it-director',
                'level' => 'Director',
            ],
            [
                'department_id' => $departments['IT'],
                'name' => 'it-manager',
                'level' => 'Manager',
            ],
            [
                'department_id' => $departments['IT'],
                'name' => 'it-staff',
                'level' => 'Staff',
            ],

            // HR
            [
                'department_id' => $departments['HR'],
                'name' => 'hr-manager',
                'level' => 'Manager',
            ],
            [
                'department_id' => $departments['HR'],
                'name' => 'hr-officer',
                'level' => 'Staff',
            ],

            // FIN
            [
                'department_id' => $departments['FIN'],
                'name' => 'finance-staff',
                'level' => 'Staff',
            ],

            // OPS
            [
                'department_id' => $departments['OPS'],
                'name' => 'operations-staff',
                'level' => 'Staff',
            ],
        ];

        foreach ($positions as $position) {
            DB::table('positions')->updateOrInsert(
                [
                    'department_id' => $position['department_id'],
                    'name' => $position['name'],
                ],
                array_merge($position, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}