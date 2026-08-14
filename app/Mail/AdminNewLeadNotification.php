<?php

namespace App\Mail;

use App\Models\PartnerLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewLeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public PartnerLead $lead;

    public function __construct(PartnerLead $lead)
    {
        $this->lead = $lead;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🔔 New Booking Lead: {$this->lead->package_title}",
            from: config('mail.from.address'),
            to: config('mail.admin_email', 'bookings@vumbiventures.com'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-lead',
            with: [
                'lead' => $this->lead,
            ],
        );
    }
}