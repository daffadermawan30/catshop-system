<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
    }

    /**
     * Kirim pesan WA ke satu nomor
     */
    public function send(string $phone, string $message): bool
    {
        // Normalisasi nomor: hapus karakter non-digit, ganti awalan 0 dengan 62
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Jangan kirim jika token kosong (dev/testing mode)
        if (empty($this->token)) {
            Log::info("[WA-SKIP] {$phone}: {$message}");
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target'  => $phone,
                'message' => $message,
                'delay'   => 2,
            ]);

            if (!$response->successful()) {
                Log::error("[WA-ERROR] {$phone}: " . $response->body());
                return false;
            }

            Log::info("[WA-SENT] {$phone}");
            return true;
        } catch (\Exception $e) {
            Log::error("[WA-EXCEPTION] {$phone}: " . $e->getMessage());
            return false;
        }
    }

    // =====================================================
    // TEMPLATE PESAN
    // =====================================================

    /**
     * Konfirmasi booking grooming
     */
    public function sendGroomingConfirmation(
        string $phone,
        string $customerName,
        string $catName,
        string $packageName,
        string $date,
        string $time
    ): bool {
        $message = "🐱 *CatShop — Konfirmasi Booking Grooming*\n\n"
            . "Halo *{$customerName}*! Booking grooming kamu berhasil dikonfirmasi.\n\n"
            . "🐾 Kucing  : {$catName}\n"
            . "✂️ Paket   : {$packageName}\n"
            . "📅 Tanggal : {$date}\n"
            . "🕐 Jam     : {$time}\n\n"
            . "Mohon datang tepat waktu ya. Kalau ada perubahan, hubungi kami di nomor ini.\n\n"
            . "_Terima kasih sudah mempercayakan perawatan kucing ke CatShop_ 🧡";

        return $this->send($phone, $message);
    }

    /**
     * Reminder H-1 grooming
     */
    public function sendGroomingReminder(
        string $phone,
        string $customerName,
        string $catName,
        string $date,
        string $time
    ): bool {
        $message = "🔔 *CatShop — Reminder Grooming Besok*\n\n"
            . "Halo *{$customerName}*! Mengingatkan bahwa besok ada jadwal grooming untuk *{$catName}*.\n\n"
            . "📅 {$date} pukul {$time}\n\n"
            . "Pastikan kucing dalam kondisi sehat dan tidak habis makan besar ya 🐱\n"
            . "Sampai jumpa besok!";

        return $this->send($phone, $message);
    }

    /**
     * Konfirmasi check-in penitipan
     */
    public function sendBoardingCheckIn(
        string $phone,
        string $customerName,
        string $catName,
        string $roomName,
        string $checkInDate,
        string $checkOutDate
    ): bool {
        $message = "🏠 *CatShop — Kucing Sudah Check-In!*\n\n"
            . "Halo *{$customerName}*! *{$catName}* sudah resmi menginap di CatShop.\n\n"
            . "🛏️ Kamar     : {$roomName}\n"
            . "📅 Check-in  : {$checkInDate}\n"
            . "📅 Check-out : {$checkOutDate}\n\n"
            . "Kami akan merawat {$catName} dengan penuh kasih sayang.\n"
            . "Laporan harian akan kami kirim setiap sore hari 🧡\n\n"
            . "_Selamat menikmati perjalanan Anda!_";

        return $this->send($phone, $message);
    }

    /**
     * Laporan harian penitipan
     */
    public function sendBoardingDailyReport(
        string $phone,
        string $customerName,
        string $catName,
        string $date,
        string $notes
    ): bool {
        $message = "📋 *CatShop — Laporan Harian {$catName}*\n"
            . "_{$date}_\n\n"
            . "Halo *{$customerName}*! Ini laporan harian kucing kesayangan Anda:\n\n"
            . "{$notes}\n\n"
            . "_Jangan ragu hubungi kami jika ada pertanyaan_ 🐱";

        return $this->send($phone, $message);
    }

    /**
     * Notifikasi check-out & tagihan
     */
    public function sendBoardingCheckOut(
        string $phone,
        string $customerName,
        string $catName,
        string $checkOutDate,
        string $totalCost
    ): bool {
        $message = "✅ *CatShop — {$catName} Siap Dijemput!*\n\n"
            . "Halo *{$customerName}*! {$catName} sudah selesai menginap dan siap dijemput.\n\n"
            . "📅 Tanggal check-out : {$checkOutDate}\n"
            . "💰 Total biaya       : Rp {$totalCost}\n\n"
            . "Silakan datang ke CatShop untuk menjemput. Pembayaran bisa dilakukan di tempat.\n"
            . "Terima kasih sudah mempercayakan penitipan ke kami 🧡";

        return $this->send($phone, $message);
    }

    /**
     * Notifikasi booking baru ke admin/owner
     */
    public function sendNewBookingAlert(
        string $ownerPhone,
        string $serviceType,
        string $customerName,
        string $catName,
        string $date
    ): bool {
        $message = "🔔 *[CatShop Admin] Booking Baru!*\n\n"
            . "Ada booking {$serviceType} baru masuk:\n\n"
            . "👤 Pelanggan : {$customerName}\n"
            . "🐾 Kucing    : {$catName}\n"
            . "📅 Tanggal   : {$date}\n\n"
            . "Segera cek dan konfirmasi di panel admin.";

        return $this->send($ownerPhone, $message);
    }
}
