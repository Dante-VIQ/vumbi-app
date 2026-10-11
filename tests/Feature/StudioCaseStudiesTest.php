<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

it('renders every configured case study', function () {
    foreach (config('case_studies') as $slug => $study) {
        $this->get(route('studio.case-study', $slug))
            ->assertOk()
            ->assertSee($study['name'])
            ->assertSee('What we built');
    }
});

it('returns 404 for an unknown case study', function () {
    $this->get('/studio/case-studies/does-not-exist')->assertNotFound();
});

it('links each client build from the studio page', function () {
    $response = $this->get('/studio')->assertOk();

    foreach (array_keys(config('case_studies')) as $slug) {
        $response->assertSee(route('studio.case-study', $slug), false);
    }
});

it('hides results and quote sections until they are filled in', function () {
    config(['case_studies.ride-aide-llc.results' => [], 'case_studies.ride-aide-llc.quote' => null]);

    $this->get(route('studio.case-study', 'ride-aide-llc'))
        ->assertDontSee('Results')
        ->assertDontSee('&ldquo;', false);
});

it('shows results and quote once provided', function () {
    config([
        'case_studies.ride-aide-llc.results' => [['value' => '3x', 'label' => 'test metric']],
        'case_studies.ride-aide-llc.quote' => ['text' => 'Great work', 'name' => 'A. Client', 'role' => 'Owner'],
    ]);

    $this->get(route('studio.case-study', 'ride-aide-llc'))
        ->assertSee('test metric')
        ->assertSee('Great work')
        ->assertSee('A. Client');
});

it('lists the studio and its case studies in the sitemap', function () {
    $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

    expect($xml)->toContain(url('/studio'));
    foreach (array_keys(config('case_studies')) as $slug) {
        expect($xml)->toContain(route('studio.case-study', $slug));
    }
});
