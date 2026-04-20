<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'address', 'gender', 'identity_number',
    ];

    // Relasi ke akun user (login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu pelanggan bisa punya banyak kucing
    public function cats()
    {
        return $this->hasMany(Cat::class);
    }

    // Riwayat booking grooming pelanggan ini
    // Ini yang menyebabkan error di CustomerController::show()
    public function groomingBookings()
    {
        return $this->hasMany(GroomingBooking::class);
    }

    // Riwayat booking penitipan pelanggan ini
    // Dipakai di Sprint 4
    public function boardingBookings()
    {
        return $this->hasMany(BoardingBooking::class);
    }
}
