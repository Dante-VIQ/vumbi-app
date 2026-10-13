<?php

namespace App\Mail;

use App\Models\SafariQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SafariQuoteReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public SafariQuote $quote) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Safari Quote — ' . $this->quote->destination,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.safari-quote-received',
            with: ['quote' => $this->quote],
        );
    }
}