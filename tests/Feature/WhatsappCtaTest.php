<?php

use Illuminate\Support\Facades\Blade;

test('the WhatsApp CTA links to the business number with the article title and a tracking event', function () {
    config(['services.whatsapp.number' => '+254 700 000 111']);

    $html = Blade::render('<x-whatsapp-cta :title="$title" placement="blog_endpost" :blog-id="9" />', [
        'title' => 'The Kingdom of Kush',
    ]);

    expect($html)->toContain('https://wa.me/254700000111?text=')
        ->and($html)->toContain(rawurlencode('"The Kingdom of Kush"'))
        ->and($html)->toContain('data-gtag-event="whatsapp_click"')
        ->and($html)->toContain('blog_endpost');
});

test('the WhatsApp CTA renders nothing when no number is configured', function () {
    config(['services.whatsapp.number' => '']);

    expect(trim(Blade::render('<x-whatsapp-cta />')))->toBe('');
});
