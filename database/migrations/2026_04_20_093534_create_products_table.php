<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->decimal('buy_price', 10, 2)->default(0);  // Harga beli dari supplier
            $table->decimal('sell_price', 10, 2);              // Harga jual ke pelanggan
            $table->integer('stock')->default(0);
            $table->integer('stock_min')->default(5);         // Alert jika stok <= ini
            $table->string('unit')->default('pcs');           // Satuan: pcs, kg, gr, ml, dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
