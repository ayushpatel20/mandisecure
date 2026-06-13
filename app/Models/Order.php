<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'buyer_id',
        'name', 'mobile', 'email', 'delivery_address', 'state', 'district', 'pin_code',
        'subtotal', 'delivery_charges', 'total_amount',
        'status', 'notes', 'tracking_number', 'courier',
    ];

    public function hasTracking(): bool
    {
        return filled($this->tracking_number) || filled($this->courier);
    }

    public static array $statuses = [
        'pending'    => ['label' => 'Pending',    'color' => 'warning'],
        'confirmed'  => ['label' => 'Confirmed',  'color' => 'info'],
        'processing' => ['label' => 'Processing', 'color' => 'primary'],
        'shipped'    => ['label' => 'Shipped',    'color' => 'secondary'],
        'delivered'  => ['label' => 'Delivered',  'color' => 'success'],
        'cancelled'  => ['label' => 'Cancelled',  'color' => 'danger'],
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class)->orderBy('created_at');
    }

    public function logStatus(string $status, ?int $changedBy, ?string $remarks = null): void
    {
        $this->statusLogs()->create([
            'status'     => $status,
            'remarks'    => $remarks,
            'changed_by' => $changedBy,
        ]);
    }

    public function statusLabel(): string
    {
        return self::$statuses[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function statusColor(): string
    {
        return self::$statuses[$this->status]['color'] ?? 'secondary';
    }
}
