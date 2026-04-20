<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroomingRecord extends Model
{
    protected $fillable = [
        'booking_id',
        'condition_notes',
        'products_used',
        'weight_at_service',
        'result_notes',
        'photo_before',
        'photo_after',
    ];

    protected $casts = [
        'weight_at_service' => 'decimal:2',
    ];

    // Relasi ke booking yang menghasilkan catatan ini
    public function booking()
    {
        return $this->belongsTo(GroomingBooking::class, 'booking_id');
    }
}
