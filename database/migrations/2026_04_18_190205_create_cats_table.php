<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->id();
            // Setiap kucing dimiliki oleh satu customer
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete();
            $table->string('name');
            // Ras kucing, contoh: Persia, Anggora, DSH, dll.
            $table->string('breed')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('date_of_birth')->nullable();
            // Berat dalam kg untuk memantau kondisi kesehatan
            $table->decimal('weight', 4, 2)->nullable();
            // Warna bulu
            $table->string('fur_color')->nullable();
            // Sudah dikebiri/steril atau belum
            $table->boolean('is_sterilized')->default(false);
            // Foto profil kucing
            $table->string('photo')->nullable();
            // Catatan alergi makanan/obat yang penting diketahui staff
            $table->text('allergies')->nullable();
            // Catatan khusus tambahan (karakter, kebiasaan, dll.)
            $table->text('special_notes')->nullable();
            // Tanggal vaksin terakhir
            $table->date('last_vaccination_date')->nullable();
            // Tanggal jadwal vaksin berikutnya
            $table->date('next_vaccination_date')->nullable();
            // Status aktif/tidak
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cats');
    }
};
