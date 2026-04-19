<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    protected $fillable = [
        'customer_id', 'name', 'breed', 'gender', 'date_of_birth',
        'weight', 'fur_color', 'is_sterilized', 'photo',
        'allergies', 'special_notes', 'last_vaccination_date',
        'next_vaccination_date', 'is_active',
    ];

    protected $casts = [
        'date_of_birth'          => 'date',
        'last_vaccination_date'  => 'date',
        'next_vaccination_date'  => 'date',
        'is_sterilized'          => 'boolean',
        'is_active'              => 'boolean',
    ];

    // Kucing ini milik customer siapa
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Semua riwayat grooming kucing ini
    public function groomingBookings()
    {
        return $this->hasMany(GroomingBooking::class);
    }

    // Semua riwayat penitipan kucing ini
    public function boardingBookings()
    {
        return $this->hasMany(BoardingBooking::class);
    }
}
