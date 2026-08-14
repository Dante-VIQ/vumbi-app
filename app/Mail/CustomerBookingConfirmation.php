<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Lead $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Booking Request Received: {$this->lead->package_title}",
            from: config('mail.from.address'),
            to: $this->lead->email,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer.booking-confirmation',
            with: [
                'lead' => $this->lead,
            ],
        );
    }
}