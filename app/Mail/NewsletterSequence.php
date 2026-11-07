<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use App\Support\LeadMagnet;
use App\Support\Persona;
use App\Support\TripStyles;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Support\Facades\URL;

/**
 * Follow-up emails after the welcome (step 1): step 2 on day 3, step 3 on day 7.
 * Content comes from config/trip_styles.php and the trips actually tagged for the persona.
 */
class NewsletterSequence extends Mailable
{
    public string $unsubscribeUrl;

    public function __construct(public NewsletterSubscriber $subscriber, public int $step)
    {
        $this->unsubscribeUrl = URL::signedRoute('newsletter.unsubscribe', ['id' => $subscriber->id]);
    }

    public function envelope(): Envelope
    {
        $label = Persona::label($this->subscriber->persona);

        return new Envelope(
            replyTo: [new Address('africa@vumbiventures.com', 'Vumbi Ventures')],
            subject: $this->step === 2
                ? ($label ? "Trips for {$label}s, picked from our partners" : 'Trips from our local partners')
                : 'Questions before you book? Ask us',
        );
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
        $persona = $this->subscriber->persona;
        $slug = TripStyles::slugFor($persona);
        $style = $slug ? TripStyles::find($slug) : null;
        $tours = $slug ? TripStyles::tours($slug, 3) : collect();

        return new Content(
            view: 'emails.newsletter-sequence-' . $this->step,
            text: 'emails.newsletter-sequence-' . $this->step . '-text',
            with: [
                'style' => $style,
                'styleUrl' => $slug ? route('trips.show', $slug) : route('discover.index'),
                'tours' => $tours,
                'faq' => $style ? array_slice($style['faq'], 0, 2) : [],
                'magnet' => LeadMagnet::for($persona),
                'quizUrl' => route('quiz'),
                'contactUrl' => route('contact'),
                'whatsappNumber' => preg_replace('/\D/', '', (string) config('services.whatsapp.number')),
                'privacyUrl' => route('privacy'),
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ],
        );
    }
}
