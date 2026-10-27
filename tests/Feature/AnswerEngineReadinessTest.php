<?php

use App\Models\Blog;
use App\Models\User;
use App\Support\ArticleFaq;
use Illuminate\Support\Facades\DB;

beforeEach(fn () => $this->withoutVite());

function aeoPost(array $attrs = []): Blog
{
    $user = User::withoutEvents(fn () => User::factory()->create());

    return Blog::create(array_merge([
        'user_id' => $user->id,
        'category' => 'Destination Guide',
        'title' => 'Kilimanjaro Guide',
        'description' => '<p>Intro</p><h2>Key Facts</h2><p>Height: 5,895 m.</p>'
            . '<h2>Frequently Asked Questions</h2>'
            . '<h3>How long does the climb take?</h3><p>Most routes take 5 to 9 days.</p>'
            . '<h3>Do I need a guide?</h3><p>Yes, guides are required on the mountain.</p>',
        'meta_description' => null,
    ], $attrs));
}

function jsonLd(string $html): array
{
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m);

    return array_map(fn ($j) => json_decode($j, true), $m[1]);
}

test('FAQ extractor reads question and answer pairs and ignores other sections', function () {
    $pairs = ArticleFaq::extract(aeoPost()->description);

    expect($pairs)->toHaveCount(2)
        ->and($pairs[0]['q'])->toBe('How long does the climb take?')
        ->and($pairs[0]['a'])->toBe('Most routes take 5 to 9 days.');
    expect(ArticleFaq::extract('<h2>Key Facts</h2><h3>Not a question</h3><p>x</p>'))->toBe([])
        ->and(ArticleFaq::extract(null))->toBe([]);
});

test('article pages publish Article and FAQPage schema with a real description', function () {
    $post = aeoPost();
    $res = $this->get($post->url)->assertOk();

    $types = collect(jsonLd($res->getContent()))->keyBy('@type');
    expect($types)->toHaveKeys(['Article', 'FAQPage', 'TravelAgency', 'WebSite'])
        ->and($types['Article']['description'])->toContain('Key Facts Height')
        ->and($types['Article']['articleSection'])->toBe('Destination Guide')
        ->and($types['Article']['publisher'])->toBe(['@id' => url('/') . '#organization'])
        ->and($types['FAQPage']['mainEntity'])->toHaveCount(2);
});

test('articles without an FAQ section get no FAQPage schema', function () {
    $post = aeoPost(['description' => '<p>Just a story.</p><h2>Part one</h2><p>Text.</p>']);
    $types = collect(jsonLd($this->get($post->url)->getContent()))->pluck('@type');

    expect($types)->not->toContain('FAQPage');
});

test('organization schema points at the homepage with a stable id and the configured contact details', function () {
    $post = aeoPost();
    $org = collect(jsonLd($this->get($post->url)->getContent()))->firstWhere('@type', 'TravelAgency');

    expect($org['url'])->toBe(url('/'))
        ->and($org['@id'])->toBe(url('/') . '#organization')
        ->and($org['email'])->toBe(config('seo.email'))
        ->and($org['email'])->not->toBe('info@vumbiventures.com')
        ->and($org)->not->toHaveKey('openingHours');
});

test('llms.txt lists the key pages and recent articles as plain text', function () {
    aeoPost(['title' => 'Serengeti Migration Guide']);

    $res = $this->get('/llms.txt')->assertOk();
    expect($res->headers->get('Content-Type'))->toContain('text/plain');
    $res->assertSee('# Vumbi Ventures')->assertSee('/trips/adventure', false)
        ->assertSee('Serengeti Migration Guide')->assertSee('/discover', false);
});

test('robots.txt allows AI search crawlers and keeps private paths blocked for them', function () {
    $robots = file_get_contents(public_path('robots.txt'));

    expect($robots)->toContain('User-agent: OAI-SearchBot')->toContain('User-agent: PerplexityBot')
        ->toContain('User-agent: Claude-SearchBot')->toContain('Disallow: /admin/')
        ->not->toMatch('/^User-agent: GPTBot/m');
});

test('AI crawler visits are counted per bot, path and day and ordinary visitors are not', function () {
    $this->withHeader('User-Agent', 'Mozilla/5.0 (compatible; PerplexityBot/1.0)')->get('/llms.txt')->assertOk();
    $this->withHeader('User-Agent', 'Mozilla/5.0 (compatible; PerplexityBot/1.0)')->get('/llms.txt')->assertOk();
    $this->withHeader('User-Agent', 'Mozilla/5.0 Chrome/120')->get('/llms.txt')->assertOk();

    $rows = DB::table('ai_crawler_hits')->get();
    expect($rows)->toHaveCount(1)->and($rows[0]->bot)->toBe('PerplexityBot')->and((int) $rows[0]->hits)->toBe(2);

    $this->artisan('ai:report')->expectsOutputToContain('PerplexityBot')->assertSuccessful();
});
