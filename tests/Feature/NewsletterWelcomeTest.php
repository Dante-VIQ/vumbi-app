<?php

use App\Mail\NewsletterWelcome;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    RateLimiter::clear('newsletter-subscribe:127.0.0.1');
    $this->withoutVite();
});

it('sends one welcome email to a new subscriber', function () {
    Mail::fake();

    Livewire::test('newsletter-subscribe')
        ->set('email', 'reader@example.com')
        ->call('subscribe')
        ->assertSet('messageType', 'success');

    Mail::assertSent(NewsletterWelcome::class, 1);
    Mail::assertSent(NewsletterWelcome::class, fn ($mail) => $mail->hasTo('reader@example.com'));
});

it('does not email an address that is already on the list', function () {
    Mail::fake();
    NewsletterSubscriber::create(['email' => 'dup@example.com', 'subscribed_at' => now()]);

    Livewire::test('newsletter-subscribe')->set('email', 'dup@example.com')->call('subscribe');

    Mail::assertNothingSent();
});

it('does not email bot submissions', function () {
    Mail::fake();

    Livewire::test('newsletter-subscribe')
        ->set('email', 'bot@example.com')->set('website', 'spam')->call('subscribe');

    Mail::assertNothingSent();
});

it('keeps the signup when the email cannot be sent', function () {
    Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('smtp down'));

    Livewire::test('newsletter-subscribe')
        ->set('email', 'reader@example.com')
        ->call('subscribe')
        ->assertSet('messageType', 'success');

    expect(NewsletterSubscriber::where('email', 'reader@example.com')->exists())->toBeTrue();
});

it('puts the checklist, articles and a signed unsubscribe link in the email', function () {
    $subscriber = NewsletterSubscriber::create(['email' => 'reader@example.com', 'subscribed_at' => now()]);
    $mail = new NewsletterWelcome($subscriber);

    $mail->assertSeeInHtml(url('/safari-checklist'), false);
    $mail->assertSeeInHtml(url('/blog/32'), false);
    $mail->assertSeeInText(url('/safari-checklist'));
    $mail->assertSeeInHtml('signature=', false);
    $mail->assertSeeInText('Unsubscribe:');

    $headers = $mail->headers()->text;
    expect($headers['List-Unsubscribe'])->toContain('/newsletter/unsubscribe/' . $subscriber->id)
        ->and($headers['List-Unsubscribe-Post'])->toBe('List-Unsubscribe=One-Click');
});

it('sends replies to the africa@ inbox', function () {
    $subscriber = NewsletterSubscriber::create(['email' => 'reader@example.com', 'subscribed_at' => now()]);

    Mail::fake();
    Mail::to($subscriber->email)->send(new NewsletterWelcome($subscriber));

    Mail::assertSent(NewsletterWelcome::class, fn ($mail) => $mail->hasReplyTo('africa@vumbiventures.com'));
});

it('refuses an unsigned unsubscribe link', function () {
    $subscriber = NewsletterSubscriber::create(['email' => 'reader@example.com', 'subscribed_at' => now()]);

    $this->get('/newsletter/unsubscribe/' . $subscriber->id)->assertForbidden();
    $this->post('/newsletter/unsubscribe/' . $subscriber->id)->assertForbidden();

    expect(NewsletterSubscriber::count())->toBe(1);
});

it('asks for confirmation on GET and only removes the subscriber on POST', function () {
    $subscriber = NewsletterSubscriber::create(['email' => 'reader@example.com', 'subscribed_at' => now()]);
    $url = URL::signedRoute('newsletter.unsubscribe', ['id' => $subscriber->id]);

    $this->get($url)->assertOk()->assertSee('Yes, unsubscribe me');
    expect(NewsletterSubscriber::count())->toBe(1);

    $this->post($url)->assertOk()->assertSee('You are unsubscribed');
    expect(NewsletterSubscriber::count())->toBe(0);

    // Opening the same link again is harmless.
    $this->get($url)->assertOk()->assertSee('You are unsubscribed');
    $this->post($url)->assertOk()->assertSee('You are unsubscribed');
});
