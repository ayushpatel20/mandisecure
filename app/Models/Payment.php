<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_method', 'transaction_id',
        'amount', 'payment_status', 'payment_date',
        'screenshot', 'remarks',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount'       => 'decimal:2',
    ];

    public static array $methods = [
        'upi'           => ['label' => 'UPI',              'icon' => 'bi-phone'],
        'bank_transfer' => ['label' => 'Bank Transfer',    'icon' => 'bi-bank'],
        'cod'           => ['label' => 'Cash on Delivery', 'icon' => 'bi-cash-coin'],
    ];

    public static array $statuses = [
        'pending'  => ['label' => 'Pending',  'color' => 'warning'],
        'paid'     => ['label' => 'Paid',     'color' => 'success'],
        'failed'   => ['label' => 'Failed',   'color' => 'danger'],
        'refunded' => ['label' => 'Refunded', 'color' => 'secondary'],
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function methodLabel(): string
    {
        return self::$methods[$this->payment_method]['label'] ?? ucfirst($this->payment_method);
    }

    public function methodIcon(): string
    {
        return self::$methods[$this->payment_method]['icon'] ?? 'bi-credit-card';
    }

    public function statusLabel(): string
    {
        return self::$statuses[$this->payment_status]['label'] ?? ucfirst($this->payment_status);
    }

    public function statusColor(): string
    {
        return self::$statuses[$this->payment_status]['color'] ?? 'secondary';
    }

    public function isPending(): bool  { return $this->payment_status === 'pending'; }
    public function isPaid(): bool     { return $this->payment_status === 'paid'; }
    public function isFailed(): bool   { return $this->payment_status === 'failed'; }
    public function isRefunded(): bool { return $this->payment_status === 'refunded'; }
}
