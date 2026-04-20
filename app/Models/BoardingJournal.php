<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingJournal extends Model
{
    protected $fillable = [
        'boarding_booking_id',
        'journal_date',
        'condition',
        'eating_notes',
        'activity_notes',
        'health_notes',
        'photo',
        'created_by',
    ];

    protected $casts = [
        'journal_date' => 'date',
    ];

    public function boardingBooking()
    {
        return $this->belongsTo(BoardingBooking::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Label kondisi dalam Bahasa Indonesia
    public function getConditionLabelAttribute(): string
    {
        return [
            'good'     => '😊 Baik',
            'normal'   => '😐 Normal',
            'stressed' => '😰 Stres',
            'sick'     => '🤒 Sakit',
        ][$this->condition] ?? $this->condition;
    }
}
