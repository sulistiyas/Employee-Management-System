<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departmentId = DB::table('departments')->where('code', 'GEN')->value('department_id');

        if (! $departmentId) {
            $departmentId = DB::table('departments')->insertGetId([
                'code' => 'GEN',
                'name' => 'General',
                'description' => 'General fallback department',
                'created_at' => now(),
                'updated_at' => now(),
            ],'department_id');
        }

        $positions = [
            ['name' => 'staff', 'level' => 'Staff'],
            ['name' => 'supervisor', 'level' => 'Supervisor'],
            ['name' => 'manager', 'level' => 'Manager'],
            ['name' => 'director', 'level' => 'Director'],
        ];

        foreach ($positions as $position) {
            DB::table('positions')->updateOrInsert(
                ['name' => $position['name']],
                array_merge($position, [
                    'department_id' => $departmentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
