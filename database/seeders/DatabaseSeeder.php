<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,        // Primero los roles
            AdminUserSeeder::class,   // Luego el usuario administrador
        ]);
    }
}
