<?php

use App\Jobs\SendCampaignEmail;
use App\Mail\CampaignEmail;
use App\Models\EmailCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

function adminUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('master');

    return $user;
}

function campaign(array $attrs = []): EmailCampaign
{
    return EmailCampaign::create($attrs + [
        'name' => 'Holiday trips',
        'subject' => 'Planning a trip?',
        'body' => "Hello\n\nRead [our guide](/blog) and [Google](https://example.org).",
        'cta_label' => 'Get a quote',
        'cta_url' => '/contact',
        'audience' => 'all',
        'status' => 'draft',
    ]);
}

it('keeps campaign pages away from guests and non-admins', function () {
    $this->get('/admin/campaigns')->assertRedirect('/login');
    $this->get('/admin/subscribers')->assertRedirect('/login');
    $this->actingAs(User::factory()->create())->get('/admin/campaigns')->assertForbidden();
});

it('lists templates and past campaigns for an admin', function () {
    campaign(['name' => 'October note']);
    $this->actingAs(adminUser())->get('/admin/campaigns')
        ->assertOk()->assertSee('October note')->assertSee('Welcome + first-safari checklist')->assertSee('Duplicate');
});

it('prefills the form from a template and from an earlier campaign', function () {
    $admin = adminUser();
    $this->actingAs($admin)->get('/admin/campaigns/create?template=questions')
        ->assertOk()->assertSee('Questions before you book? Ask us');

    $old = campaign(['name' => 'Old one', 'subject' => 'Old subject line']);
    $this->actingAs($admin)->get('/admin/campaigns/create?from=' . $old->id)
        ->assertOk()->assertSee('Old one (copy)')->assertSee('Old subject line');
});

it('validates a campaign and saves a draft', function () {
    $admin = adminUser();
    $this->actingAs($admin)->post('/admin/campaigns', ['name' => '', 'subject' => '', 'body' => ''])
        ->assertSessionHasErrors(['name', 'subject', 'body']);
    $this->actingAs($admin)->post('/admin/campaigns', ['name' => 'x', 'subject' => 's', 'body' => 'b', 'audience' => 'all', 'cta_label' => 'Go', 'cta_url' => 'javascript:alert(1)'])
        ->assertSessionHasErrors('cta_url');

    $this->actingAs($admin)->post('/admin/campaigns', ['name' => 'x', 'subject' => 's', 'body' => 'b', 'audience' => 'youth'])
        ->assertRedirect();
    expect(EmailCampaign::first())->status->toBe('draft')->audience->toBe('youth');
});

it('renders markdown safely and tags own-site links for analytics', function () {
    $c = campaign(['body' => "Hi <script>alert(1)</script>\n\n[guide](/blog) [ext](https://example.org) [bad](javascript:alert(1))"]);
    $html = $c->bodyHtml();

    expect($html)->not->toContain('<script')
        ->and($html)->not->toContain('javascript:')
        ->and($html)->toContain('utm_medium=newsletter')
        ->and($html)->toContain('utm_campaign=holiday-trips')
        ->and($html)->toContain('href="https://example.org"');
    expect($c->ctaUrl())->toContain('/contact?utm_source=email');
});

it('sends a test email to the address given, without touching the list', function () {
    Mail::fake();
    $c = campaign();
    NewsletterSubscriber::create(['email' => 'sub@example.com', 'subscribed_at' => now()]);

    $this->actingAs(adminUser())->post("/admin/campaigns/{$c->id}/test", ['email' => 'me@example.com'])->assertRedirect();

    Mail::assertSent(CampaignEmail::class, fn ($m) => $m->hasTo('me@example.com') && $m->isTest);
    Mail::assertNotSent(CampaignEmail::class, fn ($m) => $m->hasTo('sub@example.com'));
    expect($c->fresh()->status)->toBe('draft');
});

it('sends to the whole list once, with a working unsubscribe link', function () {
    Mail::fake();
    $a = NewsletterSubscriber::create(['email' => 'a@example.com', 'subscribed_at' => now(), 'persona' => 'youth']);
    NewsletterSubscriber::create(['email' => 'b@example.com', 'subscribed_at' => now()]);
    $c = campaign();

    $this->actingAs(adminUser())->post("/admin/campaigns/{$c->id}/send", ['confirm' => 1])->assertRedirect();

    Mail::assertSent(CampaignEmail::class, 2);
    $c->refresh();
    expect($c->status)->toBe('sent')->and($c->recipients_count)->toBe(2)->and($c->completed_at)->not->toBeNull();
    expect($c->sends()->whereNotNull('sent_at')->count())->toBe(2);

    Mail::assertSent(CampaignEmail::class, function ($m) use ($a) {
        if (! $m->hasTo('a@example.com')) {
            return false;
        }
        $html = $m->render();

        return str_contains($m->unsubscribeUrl, '/newsletter/unsubscribe/' . $a->id)
            && str_contains($html, 'Unsubscribe') && str_contains($html, 'Nakuru, Kenya');
    });

    // A second send attempt is refused and mails nobody again.
    $this->actingAs(adminUser())->post("/admin/campaigns/{$c->id}/send", ['confirm' => 1])->assertSessionHas('error');
    Mail::assertSent(CampaignEmail::class, 2);
});

it('targets only the chosen audience', function () {
    Mail::fake();
    NewsletterSubscriber::create(['email' => 'y@example.com', 'subscribed_at' => now(), 'persona' => 'youth']);
    NewsletterSubscriber::create(['email' => 'g@example.com', 'subscribed_at' => now(), 'persona' => 'group']);
    $c = campaign(['audience' => 'youth']);

    $this->actingAs(adminUser())->post("/admin/campaigns/{$c->id}/send", ['confirm' => 1]);

    Mail::assertSent(CampaignEmail::class, 1);
    Mail::assertSent(CampaignEmail::class, fn ($m) => $m->hasTo('y@example.com'));
});

it('requires the confirm box and never sends a campaign twice via the job', function () {
    Mail::fake();
    NewsletterSubscriber::create(['email' => 'a@example.com', 'subscribed_at' => now()]);
    $c = campaign();

    $this->actingAs(adminUser())->post("/admin/campaigns/{$c->id}/send", [])->assertSessionHasErrors('confirm');
    Mail::assertNothingSent();

    Queue::fake();
    $this->actingAs(adminUser())->post("/admin/campaigns/{$c->id}/send", ['confirm' => 1]);
    Queue::assertPushed(SendCampaignEmail::class, 1);
    $sendId = $c->sends()->first()->id;

    Queue::except([]); // run the job directly, twice
    (new SendCampaignEmail($sendId))->handle();
    (new SendCampaignEmail($sendId))->handle();
    Mail::assertSent(CampaignEmail::class, 1);
});

it('skips someone who unsubscribed after the send was queued', function () {
    Mail::fake();
    $s = NewsletterSubscriber::create(['email' => 'gone@example.com', 'subscribed_at' => now()]);
    $c = campaign();
    Queue::fake();
    $this->actingAs(adminUser())->post("/admin/campaigns/{$c->id}/send", ['confirm' => 1]);
    $s->delete();

    (new SendCampaignEmail($c->sends()->first()->id))->handle();

    Mail::assertNothingSent();
    expect($c->fresh()->status)->toBe('sent');
    expect($c->sends()->first()->error)->toContain('Unsubscribed');
});

it('locks sent campaigns from editing and deleting but lets you duplicate them', function () {
    $admin = adminUser();
    $c = campaign(['status' => 'sent']);

    $this->actingAs($admin)->get("/admin/campaigns/{$c->id}/edit")->assertForbidden();
    $this->actingAs($admin)->delete("/admin/campaigns/{$c->id}")->assertForbidden();
    $this->actingAs($admin)->get("/admin/campaigns/{$c->id}")->assertOk()->assertSee('Duplicate as a new draft');
    $this->actingAs($admin)->get('/admin/campaigns/create?from=' . $c->id)->assertOk();
});

it('previews the email and shows the subscriber list with an export', function () {
    $admin = adminUser();
    NewsletterSubscriber::create(['email' => 'a@example.com', 'subscribed_at' => now(), 'persona' => 'youth', 'source' => 'footer']);
    NewsletterSubscriber::create(['email' => '=evil@example.com', 'subscribed_at' => now()]);
    $c = campaign();

    $this->actingAs($admin)->get("/admin/campaigns/{$c->id}/preview")->assertOk()->assertSee('Planning a trip?')->assertSee('Get a quote');
    $this->actingAs($admin)->get('/admin/subscribers')->assertOk()->assertSee('a@example.com')->assertSee('footer');
    $csv = $this->actingAs($admin)->get('/admin/subscribers/export')->assertOk()->streamedContent();
    expect($csv)->toContain('a@example.com')->not->toContain('=evil');
});
