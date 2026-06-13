<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderSellerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly User  $seller,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Order Received — ' . $this->order->order_number . ' | MandiSecure',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.orders.seller_new');
    }
}
