<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'code' => 'SH-PG',
                'name' => 'Pagi',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'late_tolerance_minutes' => 15,
            ],
            [
                'code' => 'SH-SR',
                'name' => 'Sore',
                'start_time' => '15:00:00',
                'end_time' => '23:00:00',
                'late_tolerance_minutes' => 15,
            ],
            [
                'code' => 'SH-ML',
                'name' => 'Malam',
                'start_time' => '23:00:00',
                'end_time' => '07:00:00',
                'late_tolerance_minutes' => 15,
            ],
        ];

        foreach ($shifts as $shift) {
            DB::table('shifts')->updateOrInsert(
                ['code' => $shift['code']],
                array_merge($shift, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
