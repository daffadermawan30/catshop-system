<?php

namespace App\Console\Commands;

use App\Models\BoardingBooking;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendBoardingDailyReports extends Command
{
    protected $signature   = 'catshop:send-boarding-reports';
    protected $description = 'Kirim laporan harian penitipan ke pelanggan setiap sore';

    public function handle(): void
    {
        // Booking yang sedang aktif (sudah check-in, belum check-out)
        $activeBookings = BoardingBooking::with(['customer', 'cat', 'journals'])
            ->where('status', 'checked_in')
            ->get();

        if ($activeBookings->isEmpty()) {
            $this->info('Tidak ada kucing yang sedang dititipkan.');
            return;
        }

        $wa   = new WhatsAppService();
        $sent = 0;
        $today = now()->toDateString();

        foreach ($activeBookings as $booking) {
            $customer = $booking->customer;
            if (!$customer->phone) {
                continue;
            }

            // Ambil jurnal hari ini jika ada, atau buat pesan default
            $todayJournal = $booking->journals
                ->where('date', $today)
                ->first();

            $notes = $todayJournal
                ? "🍽️ Makan: {$todayJournal->food_notes}\n"
                  . "🚽 Kondisi: {$todayJournal->condition_notes}\n"
                  . "💬 Catatan: {$todayJournal->notes}"
                : "Kucing dalam kondisi sehat, makan teratur, dan bermain dengan baik hari ini 😊";

            $wa->sendBoardingDailyReport(
                phone: $customer->phone,
                customerName: $customer->name,
                catName: $booking->cat->name,
                date: now()->format('d M Y'),
                notes: $notes,
            );

            $sent++;
        }

        $this->info("Laporan terkirim: {$sent}/{$activeBookings->count()}");
    }
}
