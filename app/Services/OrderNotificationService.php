<?php

namespace App\Services;

use App\Mail\NewOrderAdminMail;
use App\Mail\NewOrderSellerMail;
use App\Mail\OrderCancelledSellerMail;
use App\Mail\OrderPlacedMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

/**
 * Dispatches order-related emails.
 * All sends are wrapped in try/catch so a misconfigured SMTP never blocks
 * the HTTP response. For production queued processing, configure QUEUE_CONNECTION
 * in .env and add implements ShouldQueue to each Mailable.
 */
class OrderNotificationService
{
    /** Called immediately after a new order is placed. */
    public static function orderPlaced(Order $order): void
    {
        $order->loadMissing(['items.seller', 'items.product']);

        // Buyer confirmation
        try {
            Mail::to($order->email)->send(new OrderPlacedMail($order));
        } catch (\Throwable) {}

        // Each unique seller in the order
        $sellers = $order->items
            ->pluck('seller')
            ->filter()
            ->unique('id');

        foreach ($sellers as $seller) {
            try {
                Mail::to($seller->email)->send(new NewOrderSellerMail($order, $seller));
            } catch (\Throwable) {}
        }

        // Admin notification (first admin account)
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            try {
                Mail::to($admin->email)->send(new NewOrderAdminMail($order));
            } catch (\Throwable) {}
        }
    }

    /**
     * Called when an order status changes.
     * @param string $previousStatus  The status before the change.
     */
    public static function statusChanged(Order $order, string $previousStatus): void
    {
        if ($order->status === $previousStatus) {
            return;
        }

        $order->loadMissing(['items.seller']);

        // Buyer emails for meaningful status transitions
        $buyerStatuses = ['processing', 'shipped', 'delivered', 'cancelled'];
        if (in_array($order->status, $buyerStatuses)) {
            try {
                Mail::to($order->email)->send(new OrderStatusMail($order));
            } catch (\Throwable) {}
        }

        // Seller cancellation alerts
        if ($order->status === 'cancelled') {
            $sellers = $order->items
                ->pluck('seller')
                ->filter()
                ->unique('id');

            foreach ($sellers as $seller) {
                try {
                    Mail::to($seller->email)->send(new OrderCancelledSellerMail($order, $seller));
                } catch (\Throwable) {}
            }
        }
    }
}
