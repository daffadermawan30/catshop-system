<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'address', 'identity_number', 'gender',
    ];

    // Satu customer punya satu akun user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu customer bisa punya banyak kucing
    public function cats()
    {
        return $this->hasMany(Cat::class);
    }

    // Semua booking grooming milik customer ini
    public function groomingBookings()
    {
        return $this->hasMany(GroomingBooking::class);
    }

    // Semua booking penitipan milik customer ini
    public function boardingBookings()
    {
        return $this->hasMany(BoardingBooking::class);
    }
}
