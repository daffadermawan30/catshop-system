<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')
                  ->constrained('room_types')
                  ->cascadeOnDelete();
            $table->string('room_number');    // Contoh: R-01, VIP-03
            $table->enum('status', ['available', 'occupied', 'maintenance'])
                  ->default('available');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('room_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
