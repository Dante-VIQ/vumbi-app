<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->data['inquiry_type'] ?? 'other') {
            'travel' => 'New Travel Inquiry - Vumbi Ventures',
            'web_dev' => 'New Web Development Inquiry - Vumbi Ventures',
            'partnership' => 'New Partnership Inquiry - Vumbi Ventures',
            default => 'New Contact Form Submission - Vumbi Ventures',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-form',
            with: ['data' => $this->data],
        );
    }
}