<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grooming_records', function (Blueprint $table) {
            $table->id();
            // Satu catatan hanya milik satu booking (unique)
            $table->foreignId('booking_id')
                  ->unique()
                  ->constrained('grooming_bookings')
                  ->cascadeOnDelete();
            $table->text('condition_notes')->nullable();
            $table->string('products_used')->nullable();
            $table->decimal('weight_at_service', 5, 2)->nullable();
            $table->text('result_notes')->nullable();
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
