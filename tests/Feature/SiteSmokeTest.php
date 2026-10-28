<?php

use App\Models\Blog;
use App\Models\PartnerPackage;
use App\Models\User;

beforeEach(fn () => $this->withoutVite());

function smokeJsonLd(string $html): array
{
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m);

    return $m[1];
}

test('every public page loads, has a title, and publishes valid structured data', function () {
    $user = User::withoutEvents(fn () => User::factory()->create());
    $post = Blog::create(['user_id' => $user->id, 'category' => 'culture', 'title' => 'Smoke "Quoted" Post', 'description' => '<p>Body</p><h2>Frequently Asked Questions</h2><h3>Q one?</h3><p>A one.</p><h3>Q two?</h3><p>A two.</p>']);
    $tour = PartnerPackage::create(['title' => 'Smoke Safari', 'slug' => 'smoke-safari', 'location' => 'Kenya', 'description' => 'A trip.', 'price' => 64500, 'type' => 'safari', 'vehicle_type' => 'Land Cruiser', 'active' => true, 'audiences' => ['group']]);

    $urls = ['/', '/about', '/services', '/contact', '/quiz', '/discover', '/tours', '/tours/smoke-safari',
        '/trips/adventure', '/trips/youth', '/trips/groups', '/blog', $post->url, '/destinations', '/culture',
        '/studio', '/studio/contact', '/privacy-policy', '/terms', '/safari-checklist', '/sitemap.xml', '/llms.txt'];

    foreach ($urls as $url) {
        $res = $this->get($url);
        $this->assertSame(200, $res->status(), "GET {$url} returned {$res->status()}");

        $body = $res->getContent();
        if (! in_array($url, ['/sitemap.xml', '/llms.txt'], true)) {
            $this->assertStringContainsString('<title>', $body, "{$url} has no title");
            foreach (smokeJsonLd($body) as $json) {
                $this->assertNotNull(json_decode($json, true), "{$url} has invalid JSON-LD: " . substr($json, 0, 120));
            }
        }
    }
});

test('prices are shown in dollars on customer pages', function () {
    PartnerPackage::create(['title' => 'Dollar Safari', 'slug' => 'dollar-safari', 'location' => 'Kenya', 'description' => 'A trip.', 'price' => 64500, 'type' => 'safari', 'vehicle_type' => 'Land Cruiser', 'active' => true]);

    $this->get('/tours')->assertOk()->assertSee('$500')->assertDontSee('KSh');
    $this->get('/tours/dollar-safari')->assertOk()->assertSee('$500')->assertDontSee('KSh');
});

test('the homepage has its own description, one organization entity and the quiz link', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)->toContain('Real stories, live prices')->toContain(route('quiz'));

    $types = collect(smokeJsonLd($html))->map(fn ($j) => json_decode($j, true)['@type'] ?? null);
    expect($types->filter(fn ($t) => $t === 'TravelAgency'))->toHaveCount(1);
});

test('a numeric blog URL 301s and the destination 404s cleanly when unknown', function () {
    $user = User::withoutEvents(fn () => User::factory()->create());
    $post = Blog::create(['user_id' => $user->id, 'category' => 'culture', 'title' => 'Redirect Me', 'description' => '<p>x</p>']);

    $this->get("/blog/{$post->id}")->assertStatus(301);
    $this->get('/destinations/does-not-exist')->assertNotFound();
    $this->get('/tours/does-not-exist')->assertNotFound();
});

test('destination and culture detail pages render and publish valid structured data', function () {
    $user = User::withoutEvents(fn () => User::factory()->create());
    $dest = \App\Models\Destination::create(['name' => 'Maasai "Mara"', 'location' => 'Narok, Kenya', 'detail' => '<p>Big cats.</p>', 'price_range' => '$100 - $300', 'best_time_to_visit' => 'July']);
    $culture = \App\Models\Culture::create(['user_id' => $user->id, 'name' => 'Maasai Beadwork', 'location' => 'Kenya', 'detail' => '<p>Colour and meaning.</p>']);

    foreach ([route('pages.destination', $dest->slug), route('pages.culture', $culture->id)] as $url) {
        $res = $this->get($url);
        $this->assertSame(200, $res->status(), "GET {$url} returned {$res->status()}");
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $res->getContent(), $m);
        $types = collect($m[1])->map(function ($j) use ($url) {
            $d = json_decode($j, true);
            $this->assertNotNull($d, "{$url} has invalid JSON-LD");

            return $d['@type'] ?? null;
        });
        $this->assertContains(str_contains($url, 'culture') ? 'Article' : 'TouristDestination', $types->all(), "{$url} is missing its structured data");
    }
});
