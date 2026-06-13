<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Order $order) {}

    public function envelope(): Envelope
    {
        $label = $this->order->statusLabel();
        return new Envelope(
            subject: "Order {$this->order->order_number} — {$label} | MandiSecure",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.orders.buyer_status');
    }
}
