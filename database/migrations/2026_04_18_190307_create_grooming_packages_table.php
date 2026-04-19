<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grooming_packages', function (Blueprint $table) {
            $table->id();
            // Nama paket, contoh: "Mandi & Sisir", "Full Grooming", dll.
            $table->string('name');
            // Deskripsi detail isi paket
            $table->text('description')->nullable();
            // Harga dalam rupiah
            $table->decimal('price', 10, 2);
            // Estimasi durasi pengerjaan dalam menit
            $table->integer('duration_minutes')->default(60);
            // Bisa di-nonaktifkan tanpa harus dihapus
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grooming_packages');
    }
};
