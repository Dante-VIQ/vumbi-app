<?php

use App\Mail\SafariQuoteReceived;
use Illuminate\Support\Facades\Mail;

it('sends a safari quote email when the landing page inquiry form is submitted', function () {
    Mail::fake();

    $this->from('https://vumbiventures.com')
        ->post('/inquiry/send-quote', [
            'destination' => 'Masai Mara',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+254712345678',
            'travel_month' => '2026-11',
            'travelers' => 2,
            'notes' => 'Need a luxury lodge',
        ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Your safari quote request has been sent! Our team will contact you shortly.');

    Mail::assertSent(SafariQuoteReceived::class, function ($mail) {
        return $mail->hasTo(config('mail.from.address', 'africa@vumbiventures.com'))
            && $mail->quote->email === 'jane@example.com';
    });
});
