<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boarding_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('cat_id')->constrained('cats')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms');
            // Tanggal check-in dan check-out yang direncanakan
            $table->date('checkin_date');
            $table->date('checkout_date');
            // Waktu aktual check-in dan check-out (diisi saat kejadian)
            $table->dateTime('actual_checkin')->nullable();
            $table->dateTime('actual_checkout')->nullable();
            $table->enum('status', [
                'pending',
                'confirmed',
                'checked_in',
                'checked_out',
                'cancelled',
            ])->default('pending');
            // Berat kucing saat check-in
            $table->decimal('checkin_weight', 4, 2)->nullable();
            // Kondisi kucing saat check-in
            $table->text('checkin_notes')->nullable();
            // Kondisi kucing saat check-out
            $table->text('checkout_notes')->nullable();
            // Makanan yang dibawa pemilik (jika ada)
            $table->text('food_instructions')->nullable();
            // Obat yang perlu diberikan
            $table->text('medication_instructions')->nullable();
            // Total biaya dihitung dari durasi × harga per malam
            $table->decimal('total_price', 10, 2)->nullable();
            $table->text('customer_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_bookings');
    }
};
