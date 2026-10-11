<?php

use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Artisan;

it('exports subscribers to a CSV with a header row', function () {
    NewsletterSubscriber::create(['email' => 'a@example.com', 'subscribed_at' => '2026-10-10 08:00:00']);
    NewsletterSubscriber::create(['email' => 'b@example.com', 'subscribed_at' => '2026-10-11 09:30:00']);

    $path = tempnam(sys_get_temp_dir(), 'nl');
    Artisan::call('newsletter:export', ['--path' => $path]);

    $rows = array_map('str_getcsv', file($path, FILE_IGNORE_NEW_LINES));
    unlink($path);

    expect($rows[0])->toBe(['email', 'subscribed_at'])
        ->and($rows)->toHaveCount(3)
        ->and($rows[1][0])->toBe('a@example.com')
        ->and($rows[2][0])->toBe('b@example.com');
});

it('skips addresses that could run as a spreadsheet formula', function () {
    NewsletterSubscriber::create(['email' => '=cmd@example.com', 'subscribed_at' => now()]);
    NewsletterSubscriber::create(['email' => 'ok@example.com', 'subscribed_at' => now()]);

    $path = tempnam(sys_get_temp_dir(), 'nl');
    Artisan::call('newsletter:export', ['--path' => $path]);
    $contents = file_get_contents($path);
    unlink($path);

    expect($contents)->toContain('ok@example.com')->not->toContain('=cmd@example.com');
});
