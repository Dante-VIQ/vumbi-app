<?php

test('traveller pages carry no web-development copy or Studio banner', function () {
    $this->withoutVite();

    foreach (['/about', '/services', '/contact', '/discover'] as $path) {
        $this->get($path)
            ->assertOk()
            ->assertDontSee('Looking for custom web app development', false)
            ->assertDontSee('Visit Vumbi Studio', false)
            ->assertDontSee('Studio (Tech)', false)
            ->assertDontSee('digital innovation company', false);
    }
});

test('the services page is travel-only', function () {
    $this->withoutVite();

    $this->get('/services')
        ->assertOk()
        ->assertDontSee('Web Development Packages', false)
        ->assertDontSee('$700', false)
        ->assertDontSee('Laravel / PHP', false)
        ->assertDontSee('Two ways to grow', false)
        ->assertSee('Find My Trip');
});

test('the contact page is travel-only but points to the Studio contact', function () {
    $this->withoutVite();

    $this->get('/contact')
        ->assertOk()
        ->assertDontSee('SEO & Growth Strategy', false)
        ->assertDontSee('Schedule Technical Scoping Call', false)
        ->assertDontSee('value="web_dev"', false)
        ->assertSee('value="travel"', false)
        ->assertSee('value="partnership"', false)
        ->assertSee(route('studio.contact'), false);
});

test('the main nav drops Services and Studio and renames the booking button', function () {
    $this->withoutVite();

    $this->get('/about')
        ->assertOk()
        ->assertSee('Find My Trip')
        ->assertDontSee('Book Safari Expedition', false)
        ->assertDontSee('concierge-bell', false);
});

test('the studio page shows the packages that moved from services', function () {
    $this->withoutVite();

    $this->get('/studio')
        ->assertOk()
        ->assertSee('Website &amp; Growth Packages', false)
        ->assertSee('$700', false)
        ->assertSee(route('studio.contact'), false);
});

test('the studio contact page loads, prefills the package and posts to the contact form', function () {
    $this->withoutVite();

    $this->get('/studio/contact?package=growth')
        ->assertOk()
        ->assertSee('Contact Vumbi Tech Studio')
        ->assertSee('Growth ($1,250)', false)
        ->assertSee('value="web_dev"', false)
        ->assertSee(route('contact.submit'), false);
});

test('a studio enquiry is emailed and returns to the page it came from', function () {
    \Illuminate\Support\Facades\Mail::fake();

    $this->from(route('studio.contact'))
        ->post(route('contact.submit'), [
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'inquiry_type' => 'web_dev',
            'website' => 'https://example.com',
            'message' => 'We need a booking site.',
            'consent' => '1',
        ])
        ->assertRedirect(route('studio.contact'))
        ->assertSessionHas('success');

    \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\ContactFormSubmitted::class, fn ($mail) => $mail->data['website'] === 'https://example.com');
});

test('a travel enquiry with travel details is accepted', function () {
    \Illuminate\Support\Facades\Mail::fake();

    $this->from(route('contact'))
        ->post(route('contact.submit'), [
            'name' => 'Test Traveller',
            'email' => 'traveller@example.com',
            'inquiry_type' => 'travel',
            'travel_details' => 'August, 4 people',
            'message' => 'Mara safari please.',
            'consent' => '1',
        ])
        ->assertRedirect(route('contact'));

    \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\ContactFormSubmitted::class);
});

test('the sitemap lists the studio contact page', function () {
    cache()->forget('sitemap_content');

    expect($this->get('/sitemap.xml')->assertOk()->getContent())->toContain(url('/studio/contact'));
});
