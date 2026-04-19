<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting: roles dulu sebelum admin
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            GroomingPackageSeeder::class,
        ]);
    }
}
