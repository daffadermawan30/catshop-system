<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name', 'description', 'price_per_day', 'facilities', 'is_active',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    // Satu tipe kamar punya banyak kamar fisik
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // Helper: hitung kamar yang tersedia saat ini
    public function getAvailableRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->where('is_active', true)->count();
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_day, 0, ',', '.');
    }
}
