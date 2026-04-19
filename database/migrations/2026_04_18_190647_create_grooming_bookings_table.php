<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grooming_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('cat_id')->constrained('cats')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('grooming_packages');
            // Tanggal dan jam booking
            $table->dateTime('booking_date');
            // Status perjalanan booking
            $table->enum('status', [
                'pending',    // Baru masuk, belum dikonfirmasi
                'confirmed',  // Sudah dikonfirmasi admin
                'in_progress',// Sedang dikerjakan
                'done',       // Selesai
                'cancelled',  // Dibatalkan
            ])->default('pending');
            // Catatan dari pelanggan saat booking
            $table->text('customer_notes')->nullable();
            // Catatan dari admin/staff
            $table->text('admin_notes')->nullable();
            // Total harga (bisa berbeda dari paket karena diskon dll.)
            $table->decimal('total_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grooming_bookings');
    }
};
