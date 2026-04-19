<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_number', 'payable_id', 'payable_type',
        'customer_id', 'amount', 'payment_method',
        'status', 'paid_at', 'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount'  => 'decimal:2',
    ];

    // Polymorphic: bisa milik grooming_booking atau boarding_booking
    public function payable()
    {
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Generate nomor invoice otomatis
    public static function generateInvoiceNumber(): string
    {
        $year  = now()->format('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
