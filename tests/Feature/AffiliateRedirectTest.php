<?php

use App\Models\PartnerPackage;
use App\Models\TourClick;
use Illuminate\Support\Facades\Schema;

function makePackage(array $overrides = []): PartnerPackage
{
    return PartnerPackage::create(array_merge([
        'location' => 'Maasai Mara',
        'title' => 'Mara Safari',
        'description' => 'Three days in the Mara.',
        'price' => 450,
        'vehicle_type' => 'safari van',
        'type' => 'safari',
        'active' => true,
        'booking_type' => 'affiliate',
        'affiliate_source' => 'orange_adventures',
        'affiliate_url' => 'https://partner.example/tours/mara',
    ], $overrides));
}

test('affiliate packages keep their affiliate fields when mass assigned', function () {
    $package = makePackage()->fresh();

    expect($package->isAffiliate())->toBeTrue()
        ->and($package->affiliate_url)->toBe('https://partner.example/tours/mara')
        ->and($package->affiliate_source)->toBe('orange_adventures');
});

test('/go redirects to the partner with utm tags and logs the click', function () {
    $package = makePackage();

    $response = $this->get(route('affiliate.redirect', ['orange_adventures', $package->id]), [
        'referer' => 'https://vumbiventures.com/blog/9',
    ]);

    $response->assertRedirect(
        'https://partner.example/tours/mara?utm_source=vumbiventures&utm_medium=referral&utm_campaign=orange_adventures'
    );
    $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');

    $click = TourClick::first();
    expect($click)->not->toBeNull()
        ->and($click->partner_package_id)->toBe($package->id)
        ->and($click->source)->toBe('orange_adventures')
        ->and($click->referring_url)->toBe('https://vumbiventures.com/blog/9')
        ->and($click->ip_hash)->toHaveLength(64);
});

test('/go keeps the partner query string and fragment and does not override their utm tags', function () {
    $package = makePackage([
        'affiliate_url' => 'https://partner.example/t?ref=abc&utm_source=partner#book',
    ]);

    $location = $this->get(route('affiliate.redirect', ['awin', $package->id]))
        ->headers->get('Location');

    expect($location)->toStartWith('https://partner.example/t?ref=abc&utm_source=partner&')
        ->and($location)->toContain('utm_medium=referral')
        ->and($location)->toContain('utm_campaign=orange_adventures')
        ->and($location)->toEndWith('#book')
        ->and(substr_count($location, 'utm_source='))->toBe(1);
});

test('/go still redirects the visitor when click logging fails', function () {
    $package = makePackage();
    Schema::drop('tour_clicks');

    $this->get(route('affiliate.redirect', ['orange_adventures', $package->id]))
        ->assertRedirectContains('https://partner.example/tours/mara');
});

test('/go sends manual packages to their tour page instead of failing', function () {
    $package = makePackage(['booking_type' => 'manual', 'affiliate_url' => null, 'slug' => 'mara-safari']);

    $this->get(route('affiliate.redirect', ['orange_adventures', $package->id]))
        ->assertRedirect(route('tours.show', 'mara-safari'));

    expect(TourClick::count())->toBe(0);
});

test('/go rejects non-http affiliate urls', function () {
    $package = makePackage([
        'affiliate_url' => 'javascript:alert(1)',
        'slug' => 'mara-safari',
    ]);

    $this->get(route('affiliate.redirect', ['orange_adventures', $package->id]))
        ->assertRedirect(route('tours.show', 'mara-safari'));
});

test('/go sends unknown or inactive packages to the tours index', function () {
    $inactive = makePackage(['active' => false]);

    $this->get(route('affiliate.redirect', ['orange_adventures', $inactive->id]))
        ->assertRedirect(route('tours.index'));

    $this->get(route('affiliate.redirect', ['orange_adventures', 99999]))
        ->assertRedirect(route('tours.index'));
});

test('/go returns 404 for a non-numeric id', function () {
    $this->get('/go/orange_adventures/not-a-number')->assertNotFound();
});
