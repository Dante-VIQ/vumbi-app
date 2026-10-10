<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Support\Facades\URL;

class NewsletterWelcome extends Mailable
{
    public string $unsubscribeUrl;

    public function __construct(public NewsletterSubscriber $subscriber)
    {
        // A signed link: only someone holding this email can unsubscribe the address.
        $this->unsubscribeUrl = URL::signedRoute('newsletter.unsubscribe', ['id' => $subscriber->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Kenya first-safari checklist (and what to read first)');
    }

    public function headers(): Headers
    {
        return new Headers(text: [
            'List-Unsubscribe' => '<' . $this->unsubscribeUrl . '>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]);
    }

    public function content(): Content
    {
        $whatsapp = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));

        return new Content(
            view: 'emails.newsletter-welcome',
            text: 'emails.newsletter-welcome-text',
            with: [
                'checklistUrl' => url('/safari-checklist'),
                'privacyUrl' => route('privacy'),
                'unsubscribeUrl' => $this->unsubscribeUrl,
                'whatsappNumber' => $whatsapp,
            ],
        );
    }
}
