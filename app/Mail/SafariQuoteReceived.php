<?php

namespace App\Mail;

use App\Models\SafariQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SafariQuoteReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SafariQuote $quote) {}

    public function build()
    {
        return $this->subject('New Safari Quote Inquiry: ' . $this->quote->name)
                    ->markdown('emails.safari-quote');
    }
}