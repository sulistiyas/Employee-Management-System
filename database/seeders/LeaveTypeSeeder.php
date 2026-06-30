<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Cuti Tahunan',
                'max_days' => 12,
                'is_paid' => true,
            ],
            [
                'name' => 'Cuti Sakit',
                'max_days' => 14,
                'is_paid' => true,
            ],
            [
                'name' => 'Cuti Melahirkan',
                'max_days' => 90,
                'is_paid' => true,
            ],
            [
                'name' => 'Cuti Menikah',
                'max_days' => 3,
                'is_paid' => true,
            ],
            [
                'name' => 'Cuti Duka Cita',
                'max_days' => 2,
                'is_paid' => true,
            ],
            [
                'name' => 'Cuti Tanpa Gaji',
                'max_days' => 30,
                'is_paid' => false,
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            DB::table('leave_types')->updateOrInsert(
                ['name' => $leaveType['name']],
                array_merge($leaveType, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}