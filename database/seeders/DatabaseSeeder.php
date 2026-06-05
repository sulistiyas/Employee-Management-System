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
        DB::statement('TRUNCATE TABLE users, roles, positions, employees, departments RESTART IDENTITY CASCADE');
        $this->call([
            RoleSeeder::class,
            PositionSeeder::class,
        ]);
    }
}
