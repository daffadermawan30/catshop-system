<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Contoh: Standard, Deluxe, VIP
            $table->text('description')->nullable();
            $table->decimal('price_per_day', 10, 2); // Harga per malam
            $table->string('facilities')->nullable();  // JSON atau teks: AC, TV, Mainan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
