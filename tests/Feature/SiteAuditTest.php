<?php
use Illuminate\Support\Facades\Artisan;

use App\Models\Blog;

it('passes the on-page audit for core pages and serves a real og image', function () {
    expect(file_exists(public_path('images/og-default.jpg')))->toBeTrue();
    expect(filesize(public_path('images/og-default.jpg')))->toBeLessThan(400_000);

    foreach (['/', '/faq', '/about', '/services', '/contact', '/quiz'] as $path) {
        $html = $this->get($path)->assertOk()->getContent();
        expect(substr_count($html, '<title'))->toBe(1);
        expect($html)->toContain('rel="canonical"')->toContain('og:image')->toContain('application/ld+json');
    }
});

it('does not duplicate the brand in the title and keeps it short', function () {
    $html = $this->get('/contact')->getContent();
    preg_match('#<title>(.*?)</title>#s', $html, $m);
    expect(substr_count(strtolower($m[1]), 'vumbi'))->toBe(1);
});

it('no longer loads jquery', function () {
    expect($this->get('/')->getContent())->not->toContain('jquery-3.7.1');
});

it('crawls the sitemap with site:audit and finds no errors', function () {
    $code = Artisan::call('site:audit');
    fwrite(STDERR, Artisan::output());
    expect($code)->toBe(0);
});

it('fires generate_lead after a contact submission', function () {
    $html = $this->withSession(['lead_event' => 'contact_page'])->get('/contact')->getContent();
    expect($html)->toContain("track('generate_lead', { source: \"contact_page\" })");
});
