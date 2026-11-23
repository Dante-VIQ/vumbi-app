<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Support\Facades\URL;

class CampaignEmail extends Mailable
{
    public string $unsubscribeUrl;

    /** $subscriber is null for admin test sends and previews. */
    public function __construct(public EmailCampaign $campaign, public ?NewsletterSubscriber $subscriber = null, public bool $isTest = false)
    {
        $this->unsubscribeUrl = $subscriber
            ? URL::signedRoute('newsletter.unsubscribe', ['id' => $subscriber->id])
            : url('/privacy-policy');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address(config('mail.admin_address', 'africa@vumbiventures.com'), 'Vumbi Ventures')],
            subject: ($this->isTest ? '[TEST] ' : '') . $this->campaign->subject,
        );
    }

    public function headers(): Headers
    {
        return $this->subscriber
            ? new Headers(text: [
                'List-Unsubscribe' => '<' . $this->unsubscribeUrl . '>',
                'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
            ])
            : new Headers;
    }

    public function content(): Content
    {
        $data = [
            'campaign' => $this->campaign,
            'bodyHtml' => $this->campaign->bodyHtml(),
            'ctaUrl' => $this->campaign->ctaUrl() ? html_entity_decode($this->campaign->ctaUrl()) : null,
            'unsubscribeUrl' => $this->unsubscribeUrl,
            'privacyUrl' => url('/privacy-policy'),
            'isTest' => $this->isTest,
        ];

        return new Content(view: 'emails.campaign', text: 'emails.campaign-text', with: $data);
    }
}
