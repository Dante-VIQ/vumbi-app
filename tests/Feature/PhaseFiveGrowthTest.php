<?php

use App\Mail\NewsletterSequence;
use App\Mail\NewsletterWelcome;
use App\Models\Blog;
use App\Models\NewsletterSubscriber;
use App\Models\PartnerPackage;
use App\Models\QuizResponse;
use App\Models\User;
use App\Support\Persona;
use App\Support\TripStyles;
use Illuminate\Support\Facades\Mail;

beforeEach(fn () => $this->withoutVite());

function p5Package(array $attrs = []): PartnerPackage
{
    static $n = 0;
    $n++;

    return PartnerPackage::create(array_merge([
        'title' => "P5 Trip {$n}", 'location' => 'Kenya', 'description' => 'Trip.', 'price' => 40000,
        'type' => 'safari', 'vehicle_type' => 'Land Cruiser', 'active' => true,
    ], $attrs));
}

test('the quiz page renders with all four questions', function () {
    $this->get('/quiz')->assertOk()->assertSee('Who is travelling?')->assertSee('What do you enjoy most?')
        ->assertSee('Under $250')->assertSee('How long?');
});

test('finishing the quiz stores anonymous answers, sets the persona and lands on filtered discovery', function () {
    $res = $this->post('/quiz', ['persona' => 'youth', 'interest' => 'beach', 'budget' => 'budget', 'days' => 'short']);

    $res->assertCookie('vumbi_persona', 'youth');
    expect($res->headers->get('Location'))->toContain('/discover?')->toContain('persona=youth')
        ->toContain('interest=beach')->toContain('budget=budget')->toContain('days=short')->toContain('#trips');
    expect(QuizResponse::first())->persona->toBe('youth')->interest->toBe('beach')->subscribed->toBeFalse();
});

test('the quiz rejects unknown answers and ignores bots', function () {
    $this->post('/quiz', ['persona' => 'hacker'])->assertSessionHasErrors('persona');
    $this->post('/quiz', ['persona' => 'youth', 'interest' => 'nope'])->assertSessionHasErrors('interest');

    $this->post('/quiz', ['persona' => 'youth', 'website' => 'spam.example'])->assertRedirect(route('discover.index'));
    expect(QuizResponse::count())->toBe(0);
});

test('quiz email signup needs consent, tags the persona and sends the welcome email once', function () {
    Mail::fake();

    $this->post('/quiz', ['persona' => 'group', 'email' => 'a@example.com']);   // no consent
    expect(NewsletterSubscriber::count())->toBe(0);

    $this->post('/quiz', ['persona' => 'group', 'email' => 'A@Example.com', 'consent' => '1']);
    $this->post('/quiz', ['persona' => 'group', 'email' => 'a@example.com', 'consent' => '1']);

    $sub = NewsletterSubscriber::first();
    expect(NewsletterSubscriber::count())->toBe(1)->and($sub->email)->toBe('a@example.com')
        ->and($sub->persona)->toBe('group')->and($sub->source)->toBe('quiz')->and($sub->sequence_step)->toBe(1);
    Mail::assertSent(NewsletterWelcome::class, 1);
    expect(QuizResponse::where('subscribed', true)->count())->toBe(2);
});

test('follow-ups go out on day 3 and day 7 only, once each, and not to unsubscribed or pre-existing people', function () {
    Mail::fake();
    p5Package(['title' => 'Group Safari', 'audiences' => ['group']]);

    $fresh = NewsletterSubscriber::create(['email' => 'new@example.com', 'subscribed_at' => now()->subDays(1), 'persona' => 'group']);
    $day3 = NewsletterSubscriber::create(['email' => 'd3@example.com', 'subscribed_at' => now()->subDays(3)->subHour(), 'persona' => 'group']);
    $legacy = NewsletterSubscriber::create(['email' => 'old@example.com', 'subscribed_at' => now()->subDays(60), 'sequence_step' => 3]);

    $this->artisan('newsletter:sequence')->assertSuccessful();

    Mail::assertSent(NewsletterSequence::class, 1);
    Mail::assertSent(NewsletterSequence::class, fn ($m) => $m->hasTo('d3@example.com') && $m->step === 2);
    expect($day3->fresh()->sequence_step)->toBe(2)->and($fresh->fresh()->sequence_step)->toBe(1)->and($legacy->fresh()->sequence_step)->toBe(3);

    // Running again the same day sends nothing new; a week on, step 3 is due.
    $this->artisan('newsletter:sequence');
    Mail::assertSent(NewsletterSequence::class, 1);

    $day3->update(['subscribed_at' => now()->subDays(8)]);
    $this->artisan('newsletter:sequence');
    Mail::assertSent(NewsletterSequence::class, fn ($m) => $m->hasTo('d3@example.com') && $m->step === 3);
    expect($day3->fresh()->sequence_step)->toBe(3);
});

test('follow-up emails use the persona trips, USD prices and an unsubscribe link', function () {
    p5Package(['title' => 'Group Safari', 'price' => 45000, 'audiences' => ['group']]);
    $sub = NewsletterSubscriber::create(['email' => 'g@example.com', 'subscribed_at' => now(), 'persona' => 'group']);

    $two = (new NewsletterSequence($sub, 2))->render();
    expect($two)->toContain('Group Safari')->toContain('$349')->toContain('/trips/groups')->toContain('unsubscribe');

    $three = (new NewsletterSequence($sub, 3))->render();
    expect($three)->toContain('Request a custom quote')->toContain('unsubscribe');
});

test('dry run lists who would be emailed and changes nothing', function () {
    Mail::fake();
    $sub = NewsletterSubscriber::create(['email' => 'd@example.com', 'subscribed_at' => now()->subDays(4)]);

    $this->artisan('newsletter:sequence --dry-run')->expectsOutputToContain('d@example.com')->assertSuccessful();
    Mail::assertNothingSent();
    expect($sub->fresh()->sequence_step)->toBe(1);
});

test('lead magnet defaults to the checklist and honours a persona override', function () {
    expect(\App\Support\LeadMagnet::for('youth')['url'])->toBe(route('safari-checklist'));

    config(['lead_magnets.personas.youth' => ['title' => 'Budget guide', 'description' => 'x', 'url' => 'https://example.com/g.pdf']]);
    expect(\App\Support\LeadMagnet::for('youth'))->toMatchArray(['title' => 'Budget guide', 'url' => 'https://example.com/g.pdf']);
});

test('two views of trips tagged for one persona create a soft guess that a real choice overrides', function () {
    $a = p5Package(['slug' => 'a-hike', 'audiences' => ['adventure']]);
    $b = p5Package(['slug' => 'b-hike', 'audiences' => ['adventure']]);

    $first = $this->get('/tours/a-hike');
    $first->assertCookieMissing('vumbi_persona_guess');

    $second = $this->withCookie('vumbi_seen', json_encode(['adventure' => 1]))->get('/tours/b-hike');
    $second->assertCookie('vumbi_persona_guess', 'adventure');

    $req = \Illuminate\Http\Request::create('/', 'GET', [], ['vumbi_persona_guess' => 'adventure']);
    expect(Persona::current($req))->toBe('adventure')->and(Persona::isInferred($req))->toBeTrue();

    $chosen = \Illuminate\Http\Request::create('/', 'GET', [], ['vumbi_persona_guess' => 'adventure', 'vumbi_persona' => 'youth']);
    expect(Persona::current($chosen))->toBe('youth')->and(Persona::isInferred($chosen))->toBeFalse();
});

test('people who already chose a persona are never guessed at', function () {
    p5Package(['slug' => 'c-hike', 'audiences' => ['adventure']]);

    $res = $this->withCookie('vumbi_persona', 'youth')->withCookie('vumbi_seen', json_encode(['adventure' => 5]))->get('/tours/c-hike');
    $res->assertCookieMissing('vumbi_persona_guess');
});

test('Field Notes put posts matching the persona first without dropping any', function () {
    $user = User::withoutEvents(fn () => User::factory()->create());
    $mk = fn ($t) => Blog::create(['user_id' => $user->id, 'category' => 'culture', 'title' => $t, 'description' => '<p>x</p>']);
    $wedding = $mk('Kenyan wedding etiquette');
    $climb = $mk('Climbing Mount Kenya');

    $ordered = TripStyles::prioritisePosts(collect([$wedding, $climb]), 'adventure');
    expect($ordered->pluck('id')->all())->toBe([$climb->id, $wedding->id])
        ->and(TripStyles::prioritisePosts(collect([$wedding, $climb]), null)->pluck('id')->all())->toBe([$wedding->id, $climb->id]);
});

test('growth review reports counts by persona', function () {
    QuizResponse::create(['persona' => 'youth']);
    NewsletterSubscriber::create(['email' => 'x@example.com', 'subscribed_at' => now(), 'persona' => 'youth', 'source' => 'quiz']);

    $this->artisan('growth:review --days=30')->expectsOutputToContain('Youth explorer')->expectsOutputToContain('quiz')->assertSuccessful();
});
