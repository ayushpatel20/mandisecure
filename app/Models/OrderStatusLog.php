<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusLog extends Model
{
    // No updated_at column in this table
    const UPDATED_AT = null;

    protected $fillable = ['order_id', 'status', 'remarks', 'changed_by'];

    protected $casts = ['created_at' => 'datetime'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function statusLabel(): string
    {
        return Order::$statuses[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function statusColor(): string
    {
        return Order::$statuses[$this->status]['color'] ?? 'secondary';
    }
}
