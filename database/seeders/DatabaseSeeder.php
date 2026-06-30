<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE roles, departments, positions, employees, users, leave_types RESTART IDENTITY CASCADE');
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            DepartmentAssignmentSeeder::class,
            UserSeeder::class,
            LeaveTypeSeeder::class,
        ]);
    }
}