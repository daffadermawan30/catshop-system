<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_type_id', 'room_number', 'status', 'notes', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke tipe kamar
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    // Semua booking yang pernah ada di kamar ini
    public function boardingBookings()
    {
        return $this->hasMany(BoardingBooking::class);
    }

    // Booking aktif saat ini (status checked_in)
    public function activeBooking()
    {
        return $this->hasOne(BoardingBooking::class)->where('status', 'checked_in');
    }

    // Cek apakah kamar tersedia pada rentang tanggal tertentu
    // Digunakan saat membuat booking baru untuk validasi konflik
    public function isAvailableFor(string $checkIn, string $checkOut, ?int $excludeBookingId = null): bool
    {
        $query = $this->boardingBookings()
            ->whereNotIn('status', ['cancelled', 'checked_out'])
            // Logika overlap: booking baru konflik jika ada booking lain
            // yang check_in-nya sebelum check_out baru DAN check_out-nya setelah check_in baru
            ->where('check_in_date', '<', $checkOut)
            ->where('check_out_date', '>', $checkIn);

        // Saat edit, exclude booking itu sendiri agar tidak konflik dengan dirinya
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->doesntExist();
    }
}
