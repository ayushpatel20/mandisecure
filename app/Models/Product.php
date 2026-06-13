<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'seller_id',
        'product_name',
        'slug',
        'description',
        'image',
        'price',
        'wholesale_price',
        'discount_price',
        'stock_quantity',
        'low_stock_threshold',
        'unit',
        'minimum_order_quantity',
        'delivery_charges',
        'expected_delivery_time',
        'location',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'price'                  => 'decimal:2',
        'wholesale_price'        => 'decimal:2',
        'discount_price'         => 'decimal:2',
        'delivery_charges'       => 'decimal:2',
        'stock_quantity'         => 'integer',
        'low_stock_threshold'    => 'integer',
        'minimum_order_quantity' => 'integer',
    ];

    public static array $units = ['kg', 'gram', 'piece', 'dozen', 'litre', 'quintal', 'bag'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isApproved(): bool   { return $this->status === 'approved'; }
    public function isRejected(): bool   { return $this->status === 'rejected'; }
    public function isOutOfStock(): bool { return $this->stock_quantity <= 0; }
    public function isLowStock(): bool   { return $this->stock_quantity > 0 && $this->stock_quantity <= $this->low_stock_threshold; }

    public function scopeApproved($query)   { return $query->where('status', 'approved'); }
    public function scopePending($query)    { return $query->where('status', 'pending'); }
    public function scopeRejected($query)   { return $query->where('status', 'rejected'); }
    public function scopeOutOfStock($query) { return $query->where('stock_quantity', '<=', 0); }
    public function scopeLowStock($query)   { return $query->where('stock_quantity', '>', 0)->whereColumn('stock_quantity', '<=', 'low_stock_threshold'); }
}
