<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroomingPackage extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'duration_minutes', 'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Satu paket bisa ada di banyak booking
    public function groomingBookings()
    {
        return $this->hasMany(GroomingBooking::class, 'package_id');
    }

    // Helper: format harga ke Rupiah
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
