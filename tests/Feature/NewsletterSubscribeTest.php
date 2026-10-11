<?php

use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

beforeEach(fn () => RateLimiter::clear('newsletter-subscribe:127.0.0.1'));

it('saves a valid email as a subscriber', function () {
    Livewire::test('newsletter-subscribe')
        ->set('email', 'reader@example.com')
        ->call('subscribe')
        ->assertHasNoErrors()
        ->assertSet('messageType', 'success')
        ->assertSet('email', '')
        ->assertDispatched('newsletter-subscribed');

    expect(NewsletterSubscriber::where('email', 'reader@example.com')->exists())->toBeTrue();
});

it('normalises the email to lower case without spaces', function () {
    Livewire::test('newsletter-subscribe')
        ->set('email', '  Reader@Example.COM ')
        ->call('subscribe')
        ->assertHasNoErrors();

    expect(NewsletterSubscriber::pluck('email')->all())->toBe(['reader@example.com']);
});

it('rejects an invalid email', function () {
    Livewire::test('newsletter-subscribe')
        ->set('email', 'not-an-email')
        ->call('subscribe')
        ->assertHasErrors(['email']);

    expect(NewsletterSubscriber::count())->toBe(0);
});

it('answers a duplicate friendly, stores it once and does not count a new signup', function () {
    NewsletterSubscriber::create(['email' => 'dup@example.com', 'subscribed_at' => now()]);

    Livewire::test('newsletter-subscribe')
        ->set('email', 'DUP@example.com')
        ->call('subscribe')
        ->assertHasNoErrors()
        ->assertSet('messageType', 'success')
        ->assertNotDispatched('newsletter-subscribed');

    expect(NewsletterSubscriber::where('email', 'dup@example.com')->count())->toBe(1);
});

it('ignores bot submissions that fill the hidden field', function () {
    Livewire::test('newsletter-subscribe')
        ->set('email', 'bot@example.com')
        ->set('website', 'http://spam.example')
        ->call('subscribe');

    expect(NewsletterSubscriber::count())->toBe(0);
});

it('limits repeated attempts from one address', function () {
    $component = Livewire::test('newsletter-subscribe');

    foreach (range(1, 5) as $i) {
        $component->set('email', "person{$i}@example.com")->call('subscribe');
    }
    $component->set('email', 'person6@example.com')->call('subscribe')
        ->assertSet('messageType', 'error');

    expect(NewsletterSubscriber::count())->toBe(5);
});

it('shows the privacy note inside every signup box', function () {
    Livewire::test('newsletter-subscribe', ['variant' => 'light', 'placement' => 'sidebar'])
        ->assertSee('We respect your privacy. Unsubscribe at any time.');
});
