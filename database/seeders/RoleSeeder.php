<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'super-admin', 'slug' => 'super-admin', 'description' => 'Full system administrator access'],
            ['name' => 'director', 'slug' => 'director', 'description' => 'Director-level permissions'],
            ['name' => 'manager', 'slug' => 'manager', 'description' => 'Manager-level permissions'],
            ['name' => 'hr', 'slug' => 'hr', 'description' => 'Human resources access'],
            ['name' => 'staff', 'slug' => 'staff', 'description' => 'Standard staff access'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                array_merge($role, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
