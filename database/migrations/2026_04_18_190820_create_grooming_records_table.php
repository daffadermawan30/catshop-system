<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grooming_records', function (Blueprint $table) {
            $table->id();
            // Relasi ke booking yang sudah selesai
            $table->foreignId('booking_id')
                  ->unique()
                  ->constrained('grooming_bookings')
                  ->cascadeOnDelete();
            // Catatan kondisi kucing saat masuk
            $table->text('condition_notes')->nullable();
            // Produk/shampo yang dipakai
            $table->string('products_used')->nullable();
            // Berat kucing saat itu
            $table->decimal('weight_at_service', 4, 2)->nullable();
            // Catatan hasil akhir grooming
            $table->text('result_notes')->nullable();
            // Foto sebelum dan sesudah (opsional)
            $table->string('photo_before')->nullable();
            $table->string('photo_after')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grooming_records');
    }
};
