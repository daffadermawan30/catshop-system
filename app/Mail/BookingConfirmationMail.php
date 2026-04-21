<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $customerName,
        public string $catName,
        public string $serviceType,   // 'Grooming' atau 'Penitipan'
        public string $packageName,
        public string $date,
        public string $time = '',
        public float  $estimatedCost = 0,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "✅ Konfirmasi Booking {$this->serviceType} — CatShop",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
        );
    }
}
