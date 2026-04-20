<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            // Tanggal aktual check-in/out (bisa berbeda dari rencana)
            $table->timestamp('actual_check_in')->nullable();
            $table->timestamp('actual_check_out')->nullable();
            $table->enum('status', [
                'pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'
            ])->default('pending');
            $table->integer('duration_days')->default(1);
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_bookings');
    }
};
