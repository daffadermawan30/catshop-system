<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'type', 'sale_id',
        'quantity', 'stock_before', 'stock_after', 'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Label tipe dalam bahasa Indonesia
    public function getTypeLabelAttribute(): string
    {
        return ['in' => '📦 Stok Masuk', 'out' => '📤 Stok Keluar', 'adjustment' => '🔧 Penyesuaian'][$this->type] ?? $this->type;
    }
}
