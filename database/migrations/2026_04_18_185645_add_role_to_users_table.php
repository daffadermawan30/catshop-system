<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom role_id sebagai foreign key ke tabel roles
            // nullable() karena saat awal register belum ada role
            $table->foreignId('role_id')
                  ->nullable()
                  ->after('email')
                  ->constrained('roles')
                  ->nullOnDelete();

            // Nomor telepon pelanggan
            $table->string('phone')->nullable()->after('role_id');
            // Alamat pelanggan
            $table->text('address')->nullable()->after('phone');
            // Status akun (aktif/nonaktif)
            $table->boolean('is_active')->default(true)->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'phone', 'address', 'is_active']);
        });
    }
};
