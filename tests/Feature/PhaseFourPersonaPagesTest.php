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
    phaseFourPackage(['title' => 'Tagged Hike', 'price' => 200, 'audiences' => ['adventure']]);
    phaseFourPackage(['title' => 'Tagged Climb', 'price' => 500, 'audiences' => ['adventure']]);
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
