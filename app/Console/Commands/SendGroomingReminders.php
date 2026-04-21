<?php

namespace App\Console\Commands;

use App\Models\GroomingBooking;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendGroomingReminders extends Command
{
    protected $signature   = 'catshop:send-grooming-reminders';
    protected $description = 'Kirim reminder WA grooming H-1 ke pelanggan';

    public function handle(): void
    {
        $tomorrow = now()->addDay()->toDateString();

        // Ambil semua booking grooming besok yang statusnya confirmed
        $bookings = GroomingBooking::with(['customer', 'cat'])
            ->where('status', 'confirmed')
            ->whereDate('scheduled_date', $tomorrow)
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada booking untuk besok.');
            return;
        }

        $wa   = new WhatsAppService();
        $sent = 0;

        foreach ($bookings as $booking) {
            $customer = $booking->customer;
            if (!$customer->phone) {
                continue;
            }

            $success = $wa->sendGroomingReminder(
                phone: $customer->phone,
                customerName: $customer->name,
                catName: $booking->cat->name,
                date: $booking->scheduled_date->format('d M Y'),
                time: $booking->scheduled_time ?? '-',
            );

            if ($success) {
                $sent++;
            }
        }

        $this->info("Reminder terkirim: {$sent}/{$bookings->count()}");
    }
}
