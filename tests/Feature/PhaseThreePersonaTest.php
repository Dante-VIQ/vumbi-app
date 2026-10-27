<?php

use App\Models\Lead;
use App\Models\PartnerPackage;
use App\Models\SafariQuote;
use App\Models\TourClick;
use App\Services\BookingService;
use App\Support\PackageTagger;
use App\Support\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

function personaTestPackage(array $attrs = []): PartnerPackage
{
    static $n = 0;
    $n++;

    return PartnerPackage::create(array_merge([
        'title' => "Test Trip {$n}",
        'location' => 'Kenya',
        'description' => 'A plain test trip.',
        'price' => 400,
        'type' => 'safari',
        'vehicle_type' => 'Land Cruiser',
        'active' => true,
    ], $attrs));
}

test('persona keys are normalised and bad values are rejected', function () {
    expect(Persona::normalize('Groups'))->toBe('group')
        ->and(Persona::normalize('adventure-seeker'))->toBe('adventure')
        ->and(Persona::normalize(' YOUTH '))->toBe('youth')
        ->and(Persona::normalize('hacker'))->toBeNull()
        ->and(Persona::normalize(null))->toBeNull();
});

test('prices map to budget bands', function () {
    expect(Persona::bandForPrice(8000))->toBe('budget')
        ->and(Persona::bandForPrice(29999.99))->toBe('budget')
        ->and(Persona::bandForPrice(30000))->toBe('mid')
        ->and(Persona::bandForPrice(89999))->toBe('mid')
        ->and(Persona::bandForPrice(90000))->toBe('premium')
        ->and(Persona::bandForPrice(0))->toBeNull()
        ->and(Persona::bandForPrice(null))->toBeNull();
});

test('filters are cleaned to known keys and values', function () {
    expect(Persona::sanitizeFilters([
        'interest' => 'hiking', 'budget' => 'nonsense', 'days' => 'week', 'group' => '6', 'audience' => 'youth', 'x' => 'y',
    ]))->toBe(['interest' => 'hiking', 'days' => 'week', 'group' => 6, 'audience' => 'youth'])
        ->and(Persona::sanitizeFilters(['group' => '-3', 'interest' => ['a']]))->toBe([]);
});

test('a new package gets a budget band from its price unless one is set', function () {
    expect(personaTestPackage(['price' => 12000])->budget_band)->toBe('budget')
        ->and(personaTestPackage(['price' => 120000])->budget_band)->toBe('premium')
        ->and(personaTestPackage(['price' => 12000, 'budget_band' => 'mid'])->budget_band)->toBe('mid');
});

test('tag filters narrow packages by audience, interest, budget, length and party size', function () {
    $hike = personaTestPackage(['audiences' => ['adventure'], 'interests' => ['hiking'], 'price' => 120000, 'duration_days' => 9, 'group_size_min' => 2, 'group_size_max' => 8]);
    $beach = personaTestPackage(['audiences' => ['youth'], 'interests' => ['beach'], 'price' => 10000, 'duration_days' => 3]);
    $family = personaTestPackage(['audiences' => ['group'], 'interests' => ['wildlife'], 'price' => 50000, 'duration_days' => 5, 'group_size_min' => 6, 'group_size_max' => 14]);

    $ids = fn (array $f) => PartnerPackage::tagged($f)->pluck('id')->sort()->values()->all();

    expect($ids(['audience' => 'adventure']))->toBe([$hike->id])
        ->and($ids(['interest' => 'beach']))->toBe([$beach->id])
        ->and($ids(['budget' => 'budget']))->toBe([$beach->id])
        ->and($ids(['days' => 'short']))->toBe([$beach->id])
        ->and($ids(['days' => 'week']))->toBe([$family->id])
        ->and($ids(['days' => 'long']))->toBe([$hike->id])
        ->and($ids(['group' => 12]))->toBe([$beach->id, $family->id])   // beach has no stated limit
        ->and($ids(['group' => 2]))->toBe([$hike->id, $beach->id])
        ->and($ids([]))->toBe([$hike->id, $beach->id, $family->id]);
});

test('ranking puts the persona first, untagged next and other personas last', function () {
    $other = personaTestPackage(['audiences' => ['youth']]);
    $untagged = personaTestPackage();
    $match = personaTestPackage(['audiences' => ['adventure', 'group']]);

    $ranked = Persona::rank(collect([$other, $untagged, $match]), 'adventure')->pluck('id')->all();

    expect($ranked)->toBe([$match->id, $untagged->id, $other->id])
        ->and(Persona::rank(collect([$other, $match]), null)->pluck('id')->all())->toBe([$other->id, $match->id]);
});

test('the tagger suggests sensible tags', function () {
    $mountKenya = personaTestPackage([
        'title' => 'Mount Kenya Climbing Adventure', 'description' => 'Summit trek via Sirimon, then Mara game drives.',
        'difficulty' => 'challenging', 'price' => 250000, 'group_size_max' => 6,
    ]);
    $camping = personaTestPackage([
        'title' => 'Budget Camping Safari', 'description' => 'Shared camping safari for backpackers.', 'price' => 17500,
    ]);

    $a = PackageTagger::suggest($mountKenya);
    $b = PackageTagger::suggest($camping);

    expect($a['audiences'])->toContain('adventure')
        ->and($a['interests'])->toContain('hiking', 'wildlife')
        ->and($a['budget_band'])->toBe('premium')
        ->and($b['audiences'])->toContain('youth')
        ->and($b['accommodation_style'])->toBe('camping')
        ->and($b['budget_band'])->toBe('budget');
});

test('the tag command fills empty tags, respects existing ones and supports dry runs', function () {
    $empty = personaTestPackage(['title' => 'Mount Kenya Trek', 'description' => 'Summit trek.', 'difficulty' => 'challenging']);
    $tagged = personaTestPackage(['title' => 'Mount Kenya Trek 2', 'description' => 'Summit trek.', 'audiences' => ['group']]);

    $this->artisan('packages:tag-personas', ['--dry-run' => true])->assertSuccessful();
    expect($empty->fresh()->audiences)->toBeNull();

    $this->artisan('packages:tag-personas')->assertSuccessful();
    expect($empty->fresh()->audiences)->toContain('adventure')
        ->and($tagged->fresh()->audiences)->toBe(['group']);   // not overwritten
});

test('discover remembers the persona and puts matching trips first', function () {
    $this->withoutVite();
    $youth = personaTestPackage(['title' => 'Youth Trip', 'audiences' => ['youth']]);
    $adventure = personaTestPackage(['title' => 'Adventure Trip', 'audiences' => ['adventure']]);

    $response = $this->get('/discover?persona=adventure')
        ->assertOk()
        ->assertCookie('vumbi_persona', 'adventure');

    $response->assertSeeInOrder(['Adventure Trip', 'Youth Trip']);

    // The cookie alone is enough on the next visit.
    $this->withCookie('vumbi_persona', 'youth')->get('/discover')
        ->assertOk()
        ->assertSeeInOrder(['Youth Trip', 'Adventure Trip']);

    $this->get('/discover?persona=all')->assertCookieExpired('vumbi_persona');
});

test('discover filters narrow the trips and an empty result still offers a way forward', function () {
    $this->withoutVite();
    personaTestPackage(['title' => 'Hike Trip', 'interests' => ['hiking']]);
    personaTestPackage(['title' => 'Beach Trip', 'interests' => ['beach']]);

    $this->get('/discover?interest=hiking')
        ->assertOk()
        ->assertSee('Hike Trip')
        ->assertDontSee('Beach Trip');

    $this->get('/discover?interest=festival')
        ->assertOk()
        ->assertSee('No trips match all of those filters yet.')
        ->assertSee('Popular Trips')
        ->assertSee('Hike Trip')
        ->assertSee(route('contact'), false);
});

test('an unknown persona or filter value is ignored', function () {
    $this->withoutVite();
    personaTestPackage(['title' => 'Any Trip']);

    $this->get('/discover?persona=hacker&budget=lol')
        ->assertOk()
        ->assertSee('Any Trip')
        ->assertCookieMissing('vumbi_persona');
});

test('discover cards trim long descriptions and show duration and audience', function () {
    $this->withoutVite();
    personaTestPackage([
        'title' => 'Wordy Trip', 'duration_days' => 4, 'audiences' => ['group'],
        'description' => implode(' ', array_fill(0, 60, 'word')) . ' LASTWORD',
    ]);

    $this->get('/discover')
        ->assertOk()
        ->assertSee('4 days')
        ->assertSee('Group traveller')
        ->assertDontSee('LASTWORD');
});

test('the tours page filters by persona tags', function () {
    $this->withoutVite();
    personaTestPackage(['title' => 'Only Youth Tour', 'audiences' => ['youth']]);
    personaTestPackage(['title' => 'Only Group Tour', 'audiences' => ['group']]);

    $this->get('/tours?audience=youth')
        ->assertOk()
        ->assertSee('Only Youth Tour')
        ->assertDontSee('Only Group Tour');
});

test('an affiliate click records the persona', function () {
    $pkg = personaTestPackage(['booking_type' => 'affiliate', 'affiliate_url' => 'https://partner.example/tour', 'affiliate_source' => 'gyg']);

    $this->withCookie('vumbi_persona', 'group')->get("/go/test/{$pkg->id}")->assertRedirect();

    expect(TourClick::latest('id')->first()->persona)->toBe('group');
});

test('a lead records the persona', function () {
    Mail::fake();
    $pkg = personaTestPackage();
    app()->instance('request', Request::create('/', 'GET', ['persona' => 'youth']));

    $lead = app(BookingService::class)->createLead([
        'partner_package_id' => $pkg->id, 'first_name' => 'Amina', 'phone' => '0700000000',
    ]);

    expect($lead->fresh()->persona)->toBe('youth');
});

test('the warm command queues stale destinations and skips with a dry run', function () {
    Bus::fake();

    $this->artisan('discover:warm', ['--dry-run' => true, '--limit' => 3])
        ->expectsOutputToContain('Would queue: Maasai Mara')
        ->assertSuccessful();
    Bus::assertNothingDispatched();

    $this->artisan('discover:warm', ['--limit' => 2])->assertSuccessful();
    Bus::assertDispatched(\App\Jobs\BuildCityDiscoveryPage::class, 2);
});

test('array-style query values cannot break discover or tours', function () {
    $this->withoutVite();
    personaTestPackage(['title' => 'Safe Trip']);

    $this->get('/discover?interest[]=x&budget[a]=b&group[]=1&persona[]=youth')->assertOk()->assertSee('Safe Trip');
    $this->get('/tours?interest[]=x&days[]=week&audience[]=youth')->assertOk();
});
