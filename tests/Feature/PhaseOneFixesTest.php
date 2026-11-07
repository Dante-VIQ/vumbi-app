<?php

test('travel pages use the single configured WhatsApp number', function () {
    $this->withoutVite();
    config(['services.whatsapp.number' => '254700000222']);

    foreach (['/about', '/contact'] as $path) {
        $this->get($path)
            ->assertOk()
            ->assertSee('wa.me/254700000222', false)
            ->assertDontSee('254734591543', false)
            ->assertDontSee('254745781236', false);
    }
});

test('the services page no longer uses a placeholder image or unverified claims', function () {
    $this->withoutVite();

    $this->get('/services')
        ->assertOk()
        ->assertDontSee('placehold.co', false)
        ->assertDontSee('Best price guarantee')
        ->assertDontSee('200+');
});

test('the contact page price range covers budget and premium trips', function () {
    $this->withoutVite();

    $this->get('/contact')
        ->assertOk()
        ->assertSee('from about $80/person', false)
        ->assertSee('$500–$5,000+/person', false);
});

test('the terms page loads and the footer links to it and to the sitemap', function () {
    $this->withoutVite();

    $this->get('/terms')
        ->assertOk()
        ->assertSee('Terms of Service')
        ->assertSee('Affiliate links');

    $this->get('/about')
        ->assertOk()
        ->assertSee(route('terms'), false)
        ->assertSee(url('/sitemap.xml'), false);
});

test('the sitemap lists the terms page', function () {
    cache()->forget('sitemap_content');

    $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

    expect($xml)->toContain(url('/terms'));
});

test('the discover page has a no-results fallback with a quote link and WhatsApp', function () {
    $this->withoutVite();
    config(['services.whatsapp.number' => '254700000222']);

    $this->get('/discover')
        ->assertOk()
        ->assertSee('We couldn\'t find a guide for that yet', false)
        ->assertSee(route('contact'), false)
        ->assertSee('wa.me/254700000222', false)
        ->assertDontSee('picsum.photos', false)
        ->assertDontSee('](https://', false);
});
