<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Siapa yang input
            $table->enum('type', ['in', 'out', 'adjustment']);
            // Reference ke sale_id jika movement berasal dari transaksi penjualan
            $table->foreignId('sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->integer('quantity');        // Bisa positif (in) atau negatif tidak dipakai, selalu positif
            $table->integer('stock_before');    // Stok sebelum pergerakan, untuk audit trail
            $table->integer('stock_after');     // Stok setelah pergerakan
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
