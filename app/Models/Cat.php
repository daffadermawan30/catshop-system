<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cat extends Model
{
    protected $fillable = [
        'customer_id', 'name', 'breed', 'gender',
        'date_of_birth', 'weight', 'fur_color',
        'is_sterilized', 'photo', 'allergies',
        'special_notes', 'last_vaccination_date',
        'next_vaccination_date', 'is_active',
    ];

    protected $casts = [
        'date_of_birth'         => 'date',
        'last_vaccination_date' => 'date',
        'next_vaccination_date' => 'date',
        'is_sterilized'         => 'boolean',
        'is_active'             => 'boolean',
        'weight'                => 'decimal:2',
    ];

    // Relasi ke pemilik
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Riwayat grooming kucing ini
    public function groomingBookings()
    {
        return $this->hasMany(GroomingBooking::class);
    }

    // Riwayat penitipan kucing ini
    public function boardingBookings()
    {
        return $this->hasMany(BoardingBooking::class);
    }

    // Helper: hitung umur kucing dari date_of_birth
    public function getAgeAttribute(): string
    {
        if (! $this->date_of_birth) {
            return 'Tidak diketahui';
        }

        $months = $this->date_of_birth->diffInMonths(now());
        if ($months < 12) {
            return $months . ' bulan';
        }
        return floor($months / 12) . ' tahun ' . ($months % 12) . ' bulan';
    }
}
