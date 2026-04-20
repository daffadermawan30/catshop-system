<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number', 'customer_id', 'user_id',
        'subtotal', 'discount_amount', 'total',
        'paid_amount', 'change_amount',
        'payment_method', 'status', 'notes',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total'           => 'decimal:2',
        'paid_amount'     => 'decimal:2',
        'change_amount'   => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Kasir yang melakukan transaksi
    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Generate nomor invoice unik: INV-YYYYMMDD-XXXX
    public static function generateInvoiceNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = "INV-{$date}-";

        // Ambil invoice terakhir hari ini dan increment nomornya
        $last = static::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $seq = $last ? (intval(substr($last, -4)) + 1) : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
