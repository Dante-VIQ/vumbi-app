<?php

use App\Mail\ContactFormSubmitted;
use App\Models\PartnerPackage;
use Illuminate\Support\Facades\Mail;

beforeEach(fn () => $this->withoutVite());

function phaseFourPackage(array $attrs = []): PartnerPackage
{
    static $n = 0;
    $n++;

    return PartnerPackage::create(array_merge([
        'title' => "P4 Trip {$n}",
        'location' => 'Kenya',
        'description' => 'A test trip.',
        'price' => 300,
        'type' => 'safari',
        'vehicle_type' => 'Land Cruiser',
        'active' => true,
    ], $attrs));
}

test('each trip style page renders with a unique title and remembers the persona', function () {
    $titles = [];
    foreach (['adventure' => 'adventure', 'youth' => 'youth', 'groups' => 'group'] as $slug => $persona) {
        $res = $this->get("/trips/{$slug}")->assertOk()->assertCookie('vumbi_persona', $persona);
        preg_match('/<title>(.*?)<\/title>/s', $res->getContent(), $m);
        $titles[] = $m[1];
        $res->assertSee('FAQPage', false);
    }
    expect(array_unique($titles))->toHaveCount(3);
});

test('trip page lists tagged tours with a price range', function () {
    phaseFourPackage(['title' => 'Tagged Hike', 'price' => 25800, 'audiences' => ['adventure']]);
    phaseFourPackage(['title' => 'Tagged Climb', 'price' => 64500, 'audiences' => ['adventure']]);
    phaseFourPackage(['title' => 'Other Trip', 'audiences' => ['youth']]);

    $this->get('/trips/adventure')->assertOk()
        ->assertSee('Tagged Hike')->assertSee('Tagged Climb')->assertDontSee('Other Trip')
        ->assertSee('from $200 to $500', false);
});

test('trip page falls back honestly when nothing is tagged', function () {
    phaseFourPackage(['title' => 'Untagged Trip']);

    $this->get('/trips/youth')->assertOk()
        ->assertSee('Untagged Trip')->assertSee('still tagging trips', false);
});

test('unknown trip style 404s and /trips redirects to services', function () {
    $this->get('/trips/nonsense')->assertNotFound();
    $this->get('/trips')->assertRedirect('/services');
});

test('services page links to the three trip pages and has no web-dev copy', function () {
    $res = $this->get('/services')->assertOk();
    foreach (['adventure', 'youth', 'groups'] as $slug) {
        $res->assertSee("/trips/{$slug}", false);
    }
    $res->assertDontSee('web development', false);
});

test('about page has no invented stats and hides empty people sections', function () {
    $res = $this->get('/about')->assertOk();
    $res->assertDontSee('2000+')->assertDontSee('Innovation Potential')->assertDontSee('Authentic Groundwork');
    $res->assertDontSee('Founder');
});

test('about page shows founder when configured', function () {
    config(['about.founder' => ['name' => 'Dante Test', 'role' => 'Founder', 'note' => 'Why I started this.', 'photo' => null]]);
    $this->get('/about')->assertOk()->assertSee('Dante Test')->assertSee('Why I started this.');
});

test('contact page prefills persona and new fields are mailed', function () {
    Mail::fake();

    $this->withCookie('vumbi_persona', 'youth')->get('/contact')->assertOk()
        ->assertSee('name="persona" value="youth"', false);

    $this->post('/contact', [
        'name' => 'Jane', 'email' => 'jane@example.com', 'inquiry_type' => 'travel',
        'message' => 'Hello there', 'consent' => 'on',
        'persona' => 'group', 'travel_month' => now()->addMonths(2)->format('Y-m'),
        'group_size' => 6, 'budget_range' => 'mid',
    ])->assertSessionHasNoErrors();

    Mail::assertSent(ContactFormSubmitted::class, fn ($m) => $m->data['persona'] === 'group' && $m->data['group_size'] == 6 && $m->data['budget_range'] === 'mid');
});

test('contact rejects invalid persona and budget values', function () {
    $this->post('/contact', [
        'name' => 'Jane', 'email' => 'jane@example.com', 'inquiry_type' => 'travel',
        'message' => 'Hi', 'consent' => 'on', 'persona' => 'hacker', 'budget_range' => 'x', 'group_size' => 0,
    ])->assertSessionHasErrors(['persona', 'budget_range', 'group_size']);
});

test('sitemap lists trip pages', function () {
    $this->get('/sitemap.xml')->assertOk()->assertSee('/trips/adventure', false)->assertSee('/trips/groups', false);
});

test('prices on trip and services pages are shown in USD', function () {
    phaseFourPackage(['title' => 'Ksh Trip', 'price' => 45000, 'audiences' => ['group']]);

    $this->get('/trips/groups')->assertOk()->assertSee('From $349')->assertDontSee('KSh');
    $this->get('/services')->assertOk()->assertSee('From $349');
});

test('budget bands use USD thresholds applied to KSh prices and the form labels match', function () {
    expect(\App\Support\Persona::bandForPrice(32000))->toBe('budget')
        ->and(\App\Support\Persona::bandForPrice(90500))->toBe('premium')
        ->and(\App\Support\Persona::budgetRangeLabels()['mid'])->toBe('$250 to $700');

    $this->get('/contact')->assertOk()->assertSee('Under $250');
});

test('studio enquiries go to the studio inbox and travel enquiries to the travel inbox', function () {
    Mail::fake();
    $base = ['name' => 'Jo', 'email' => 'jo@example.com', 'message' => 'Hello', 'consent' => 'on'];

    $this->post('/contact', $base + ['inquiry_type' => 'web_dev'])->assertSessionHasNoErrors();
    Mail::assertSent(ContactFormSubmitted::class, fn ($m) => $m->hasTo('studio@vumbiventures.com'));

    $this->post('/contact', $base + ['inquiry_type' => 'travel'])->assertSessionHasNoErrors();
    Mail::assertSent(ContactFormSubmitted::class, fn ($m) => $m->hasTo('africa@vumbiventures.com'));
});

test('the budget-band migration rebases old USD-derived bands', function () {
    $a = phaseFourPackage(['price' => 12000]);                       // KSh 12,000
    \Illuminate\Support\Facades\DB::table('partner_packages')->where('id', $a->id)->update(['budget_band' => 'budget']);
    $b = phaseFourPackage(['price' => 50000]);
    \Illuminate\Support\Facades\DB::table('partner_packages')->where('id', $b->id)->update(['budget_band' => 'premium']); // old USD result for 50,000

    (include database_path('migrations/2026_10_27_100000_rebase_budget_bands_to_ksh.php'))->up();

    expect($a->fresh()->budget_band)->toBe('budget')->and($b->fresh()->budget_band)->toBe('mid');
});

test('the USD band migration moves bands that matched the old KSh result and leaves hand-set ones', function () {
    $auto = phaseFourPackage(['price' => 31000]);     // $240: budget now, mid under the old KSh bands
    $manual = phaseFourPackage(['price' => 31000]);
    \Illuminate\Support\Facades\DB::table('partner_packages')->where('id', $auto->id)->update(['budget_band' => 'mid']);
    \Illuminate\Support\Facades\DB::table('partner_packages')->where('id', $manual->id)->update(['budget_band' => 'premium']);

    (include database_path('migrations/2026_10_28_120000_rebase_budget_bands_to_usd.php'))->up();

    expect($auto->fresh()->budget_band)->toBe('budget')->and($manual->fresh()->budget_band)->toBe('premium');
});

test('Money converts stored KSh to whole dollars at the configured rate', function () {
    config(['currency.kes_per_usd' => 100]);

    expect(\App\Support\Money::usd(45000))->toBe('$450')
        ->and(\App\Support\Money::usd(149))->toBe('$1')
        ->and(\App\Support\Money::kesFromUsd(250))->toBe(25000.0);

    config(['currency.kes_per_usd' => 0]);
    expect(\App\Support\Money::rate())->toBe(129.0);
});

test('the tours price filter is entered in dollars and matches stored KSh prices', function () {
    phaseFourPackage(['title' => 'Cheap One', 'price' => 12900, 'slug' => 'cheap-one']);   // $100
    phaseFourPackage(['title' => 'Pricey One', 'price' => 129000, 'slug' => 'pricey-one']); // $1,000

    $this->get('/tours?min_price=500')->assertOk()->assertSee('Pricey One')->assertDontSee('Cheap One');
    $this->get('/tours?max_price=500')->assertOk()->assertSee('Cheap One')->assertDontSee('Pricey One');
});
