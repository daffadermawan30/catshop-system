<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use App\Models\Customer;
use App\Models\GroomingBooking;
use App\Models\BoardingBooking;

class DashboardController extends Controller
{
    public function index()
    {
        // Data ringkasan untuk dashboard
        $data = [
            // Total pelanggan terdaftar
            'total_customers' => Customer::count(),
            // Total kucing terdaftar
            'total_cats' => Cat::count(),
            // Booking grooming hari ini
            'grooming_today' => GroomingBooking::whereDate('booking_date', today())
                ->whereIn('status', ['confirmed', 'in_progress'])
                ->count(),
            // Kucing yang sedang dititip sekarang
            'boarding_active' => BoardingBooking::where('status', 'checked_in')->count(),
            // 5 booking grooming terbaru
            'recent_grooming' => GroomingBooking::with(['customer', 'cat', 'package'])
                ->latest()
                ->take(5)
                ->get(),
            // Booking penitipan yang aktif
            'active_boardings' => BoardingBooking::with(['customer', 'cat', 'room'])
                ->where('status', 'checked_in')
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
