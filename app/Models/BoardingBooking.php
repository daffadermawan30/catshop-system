<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BoardingBooking extends Model
{
    protected $fillable = [
        'customer_id', 'cat_id', 'room_id',
        'check_in_date', 'check_out_date',
        'actual_check_in', 'actual_check_out',
        'status', 'duration_days',
        'price_per_day', 'total_price',
        'customer_notes', 'admin_notes',
    ];

    protected $casts = [
        'check_in_date'    => 'date',
        'check_out_date'   => 'date',
        'actual_check_in'  => 'datetime',
        'actual_check_out' => 'datetime',
        'price_per_day'    => 'decimal:2',
        'total_price'      => 'decimal:2',
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

    // Jurnal harian selama kucing dititipkan
    public function journals()
    {
        return $this->hasMany(BoardingJournal::class)->orderBy('journal_date');
    }

    // Helper: hitung durasi dari tanggal check-in dan check-out
    public function calculateDuration(): int
    {
        return max(1, $this->check_in_date->diffInDays($this->check_out_date));
    }

    // Helper: hitung total harga
    public function calculateTotal(): float
    {
        return $this->calculateDuration() * $this->price_per_day;
    }
}
