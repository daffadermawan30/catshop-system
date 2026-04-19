<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingBooking extends Model
{
    protected $fillable = [
        'customer_id', 'cat_id', 'room_id',
        'checkin_date', 'checkout_date', 'actual_checkin', 'actual_checkout',
        'status', 'checkin_weight', 'checkin_notes', 'checkout_notes',
        'food_instructions', 'medication_instructions',
        'total_price', 'customer_notes',
    ];

    protected $casts = [
        'checkin_date'    => 'date',
        'checkout_date'   => 'date',
        'actual_checkin'  => 'datetime',
        'actual_checkout' => 'datetime',
        'total_price'     => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function journals()
    {
        return $this->hasMany(BoardingJournal::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    // Hitung jumlah malam penitipan
    public function getDurationAttribute(): int
    {
        return $this->checkin_date->diffInDays($this->checkout_date);
    }
}
