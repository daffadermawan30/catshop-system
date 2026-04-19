<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Nomor invoice otomatis, contoh: INV-2026001
            $table->string('invoice_number')->unique();
            // Bisa terhubung ke grooming_booking atau boarding_booking
            // Kita pakai polymorphic relation
            $table->morphs('payable'); // membuat kolom payable_id + payable_type
            $table->foreignId('customer_id')->constrained('customers');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', [
                'cash',
                'transfer',
                'qris',
            ])->default('cash');
            $table->enum('status', [
                'unpaid',
                'paid',
                'refunded',
            ])->default('unpaid');
            // Waktu pembayaran dikonfirmasi
            $table->dateTime('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
