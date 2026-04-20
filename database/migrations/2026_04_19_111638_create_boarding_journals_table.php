<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boarding_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boarding_booking_id')
                  ->constrained('boarding_bookings')
                  ->cascadeOnDelete();
            $table->date('journal_date');
            // Kondisi: good, normal, stressed, sick
            $table->enum('condition', ['good', 'normal', 'stressed', 'sick'])
                  ->default('normal');
            $table->text('eating_notes')->nullable();   // Makan pagi/siang/malam
            $table->text('activity_notes')->nullable(); // Aktivitas hari ini
            $table->text('health_notes')->nullable();   // Catatan kesehatan
            $table->string('photo')->nullable();        // Foto harian
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps();

            // Satu jurnal per hari per booking
            $table->unique(['boarding_booking_id', 'journal_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_journals');
    }
};
