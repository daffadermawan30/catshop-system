<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            // Contoh: "Standar", "VIP", "Suite"
            $table->string('name');
            $table->text('description')->nullable();
            // Harga per malam dalam rupiah
            $table->decimal('price_per_night', 10, 2);
            // Fasilitas yang tersedia (AC, mainan, dll.)
            $table->text('facilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
