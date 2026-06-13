<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquiry extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $senderName,
        public readonly string $senderEmail,
        public readonly string $senderMobile,
        public readonly string $subject,
        public readonly string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[MandiSecure Inquiry] ' . $this->subject,
            replyTo: [
                new \Illuminate\Mail\Mailables\Address($this->senderEmail, $this->senderName),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-inquiry',
        );
    }
}
