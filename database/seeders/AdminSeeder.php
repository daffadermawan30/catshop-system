<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        // Buat akun admin utama
        User::firstOrCreate(
            ['email' => 'admin@catshop.com'],
            [
                'name'      => 'Admin CatShop',
                'password'  => Hash::make('catshop123'), // Ganti setelah deploy!
                'role_id'   => $adminRole->id,
                'is_active' => true,
            ]
        );
    }
}
