<?php

use App\Models\PartnerPackage;

test('the sitemap lists active tour pages and index pages but not inactive tours', function () {
    cache()->forget('sitemap_content');

    PartnerPackage::create([
        'location' => 'Zanzibar', 'title' => 'Stone Town Walk', 'description' => 'x',
        'price' => 50, 'vehicle_type' => 'van', 'type' => 'tour', 'active' => true, 'slug' => 'stone-town-walk',
    ]);
    PartnerPackage::create([
        'location' => 'Zanzibar', 'title' => 'Hidden Draft', 'description' => 'x',
        'price' => 50, 'vehicle_type' => 'van', 'type' => 'tour', 'active' => false, 'slug' => 'hidden-draft',
    ]);

    $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

    expect($xml)->toContain(route('tours.show', 'stone-town-walk'))
        ->and($xml)->not->toContain('hidden-draft')
        ->and($xml)->toContain(url('/destinations'))
        ->and($xml)->toContain(url('/culture'));
});
