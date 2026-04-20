<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'sku', 'barcode', 'description',
        'photo', 'buy_price', 'sell_price', 'stock',
        'stock_min', 'unit', 'is_active',
    ];

    protected $casts = [
        'buy_price'  => 'decimal:2',
        'sell_price' => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Helper: apakah stok di bawah minimum?
    public function isLowStock(): bool
    {
        return $this->stock <= $this->stock_min;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->sell_price, 0, ',', '.');
    }
}
