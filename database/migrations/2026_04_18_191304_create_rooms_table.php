<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types');
            // Nomor atau nama kamar, contoh: "A1", "VIP-1"
            $table->string('room_number')->unique();
            // Status kamar saat ini
            $table->enum('status', [
                'available',  // Kosong, bisa dipesan
                'occupied',   // Sedang ditempati kucing
                'maintenance',// Sedang dibersihkan/diperbaiki
            ])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
