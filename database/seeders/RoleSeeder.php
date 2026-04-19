<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role admin dan pelanggan jika belum ada
        Role::firstOrCreate(['name' => 'admin'], [
            'description' => 'Pemilik dan pengelola catshop'
        ]);

        Role::firstOrCreate(['name' => 'pelanggan'], [
            'description' => 'Pelanggan catshop'
        ]);
    }
}
