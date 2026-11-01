<?php

use App\Http\Controllers\FaqController;

function faqJsonLd(string $html): array
{
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m);

    return array_map(fn ($j) => json_decode($j, true), $m[1]);
}

test('the FAQ page answers "What is Vumbi Ventures?" with the shared definition', function () {
    $res = $this->get('/faq')->assertOk();

    $res->assertSee('What is Vumbi Ventures?')->assertSee(config('seo.definition'));
    expect($res->getContent())->toContain('<title>FAQ: What Is Vumbi Ventures');
});

test('the FAQ is published as FAQPage schema that matches the visible questions', function () {
    $html = $this->get('/faq')->assertOk()->getContent();

    $faq = collect(faqJsonLd($html))->firstWhere('@type', 'FAQPage');
    expect($faq)->not->toBeNull();

    $names = collect($faq['mainEntity'])->pluck('name');
    expect($names)->toContain('What is Vumbi Ventures?')->toContain('Does Vumbi Ventures operate the tours itself?');
    foreach ($names as $q) {
        $this->assertStringContainsString(e($q), $html, "Schema question not visible on the page: {$q}");
    }
    expect($faq['mainEntity'][0]['acceptedAnswer']['text'])->toBe(config('seo.definition'));
});

test('"Who runs Vumbi Ventures?" appears only when a founder is configured', function () {
    $with = collect(FaqController::sections())->flatten(1)->pluck('q');
    expect($with)->toContain('Who runs Vumbi Ventures?');

    config(['about.founder.name' => null]);
    $without = collect(FaqController::sections())->flatten(1)->pluck('q');
    expect($without)->not->toContain('Who runs Vumbi Ventures?');
});

test('the About page leads with the definition and shows the founder note and a credited image', function () {
    $html = $this->get('/about')->assertOk()->getContent();

    expect($html)->toContain(e(config('seo.definition')))
        ->toContain('A note from the founder')->toContain('Vumbi&quot; is Swahili for dust')
        ->toContain('images.unsplash.com/photo-1747348070600-879dd45881e3')->toContain('Simon Van Rompaey')->toContain('Unsplash');

    $about = collect(faqJsonLd($html))->firstWhere('@type', 'AboutPage');
    expect($about['mainEntity'])->toBe(['@id' => url('/') . '#organization']);
});

test('a real portrait replaces the scenic image and the section hides when the note is removed', function () {
    config(['about.founder.photo' => 'images/team/dante.jpg']);
    $html = $this->get('/about')->assertOk()->getContent();
    expect($html)->toContain('images/team/dante.jpg')->not->toContain('images.unsplash.com');

    config(['about.founder.note' => null]);
    $this->get('/about')->assertOk()->assertDontSee('A note from the founder');
});

test('the organization entity carries the definition, founder and expertise', function () {
    $html = $this->get('/faq')->getContent();
    $org = collect(faqJsonLd($html))->firstWhere('@type', 'TravelAgency');

    expect($org['description'])->toBe(config('seo.definition'))
        ->and($org['alternateName'])->toBe('Vumbi')
        ->and($org['founder'])->toMatchArray(['@type' => 'Person', 'name' => 'Dante'])
        ->and($org['knowsAbout'])->toContain('Kenya safaris')
        ->and($org['foundingLocation']['name'])->toBe('Nakuru, Kenya');
});

test('the contact page schema uses the configured email, not the old address', function () {
    $html = $this->get('/contact')->assertOk()->getContent();

    expect($html)->not->toContain('info@vumbiventures.com');
    $contact = collect(faqJsonLd($html))->firstWhere('@type', 'ContactPage');
    expect($contact['mainEntity']['email'])->toBe(config('seo.email'));
});

test('the homepage, llms.txt, sitemap and footer all point at the FAQ', function () {
    $this->get('/')->assertOk()->assertSee('What is Vumbi Ventures?')->assertSee(route('faq'), false);
    $this->get('/llms.txt')->assertOk()->assertSee(config('seo.definition'))->assertSee('/faq', false);
    $this->get('/sitemap.xml')->assertOk()->assertSee('/faq', false);
});
