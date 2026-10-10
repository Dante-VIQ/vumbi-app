<?php

test('the safari checklist page loads and offers the signup and WhatsApp help', function () {
    $this->withoutVite();
    config(['services.whatsapp.number' => '+254 700 000 111']);

    $this->get('/safari-checklist')
        ->assertOk()
        ->assertSee('Kenya first-safari planning checklist')
        ->assertSee('etakenya.go.ke')
        ->assertSee('Get Field Notes by email')
        ->assertSee('wa.me/254700000111', false);
});

test('the privacy policy page loads and names what is collected', function () {
    $this->withoutVite();

    $this->get('/privacy-policy')
        ->assertOk()
        ->assertSee('Privacy Policy')
        ->assertSee('Newsletter')
        ->assertSee('info@vumbiventures.com');
});

test('the footer and signup boxes link to the privacy policy', function () {
    \Livewire\Livewire::test('newsletter-subscribe')
        ->assertSee(route('privacy'), false);
});

test('the sitemap lists the new pages', function () {
    cache()->forget('sitemap_content');

    $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

    expect($xml)->toContain(url('/safari-checklist'))->toContain(url('/privacy-policy'));
});
