<?php

namespace Database\Seeders;

use App\Models\GroomingPackage;
use Illuminate\Database\Seeder;

class GroomingPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'             => 'Mandi & Sisir',
                'description'      => 'Mandi dengan shampo khusus kucing, dikeringkan, dan disisir.',
                'price'            => 50000,
                'duration_minutes' => 60,
            ],
            [
                'name'             => 'Mandi + Potong Kuku',
                'description'      => 'Mandi lengkap ditambah potong kuku.',
                'price'            => 65000,
                'duration_minutes' => 75,
            ],
            [
                'name'             => 'Full Grooming',
                'description'      => 'Mandi, sisir, potong kuku, bersihkan telinga, dan parfum.',
                'price'            => 100000,
                'duration_minutes' => 120,
            ],
        ];

        foreach ($packages as $package) {
            GroomingPackage::firstOrCreate(['name' => $package['name']], $package);
        }
    }
}
