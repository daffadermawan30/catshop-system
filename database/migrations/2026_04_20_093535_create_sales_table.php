<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // INV-20260419-0001
            $table->foreignId('customer_id')
                  ->nullable()
                  ->constrained('customers')
                  ->nullOnDelete(); // Boleh null = pelanggan umum/walk-in
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Kasir
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);  // Uang yang diterima
            $table->decimal('change_amount', 12, 2)->default(0); // Kembalian
            $table->enum('payment_method', ['cash', 'transfer', 'qris', 'debit', 'credit'])
                  ->default('cash');
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
