<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GroomingBooking extends Model
{
    protected $fillable = [
        'customer_id', 'cat_id', 'package_id',
        'booking_date', 'status', 'customer_notes', 'total_price',
    ];

    protected $casts = [
        // Otomatis cast string tanggal menjadi Carbon object
        // Sehingga bisa langsung pakai ->format(), ->diffForHumans(), dll.
        'booking_date' => 'datetime',
        'total_price'  => 'decimal:2',
    ];

    // Relasi ke pelanggan
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke kucing
    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }

    // Relasi ke paket grooming
    public function package()
    {
        return $this->belongsTo(GroomingPackage::class, 'package_id');
    }

    // Relasi ke catatan hasil grooming (one-to-one)
    // Ini yang hilang dan menyebabkan error di show.blade.php
    public function record()
    {
        return $this->hasOne(GroomingRecord::class, 'booking_id');
    }
}
