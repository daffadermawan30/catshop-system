<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroomingBooking extends Model
{
    protected $fillable = [
        'customer_id', 'cat_id', 'package_id', 'booking_date',
        'status', 'customer_notes', 'admin_notes', 'total_price',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_price'  => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
    public function package()
    {
        return $this->belongsTo(GroomingPackage::class, 'package_id');
    }
    public function record()
    {
        return $this->hasOne(GroomingRecord::class, 'booking_id');
    }

    // Relasi polymorphic ke pembayaran
    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }
}
