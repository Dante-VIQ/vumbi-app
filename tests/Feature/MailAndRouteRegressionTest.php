<?php

use App\Mail\AdminNewLeadNotification;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

it('accepts multiple admin recipient addresses in the notification envelope', function () {
    config()->set('mail.admin_email', ['africa@vumbiventures.com', 'vumbiventures@gmail.com']);

    $packageId = DB::table('partner_packages')->insertGetId([
        'location' => 'Maasai Mara',
        'title' => '3-Day Safari',
        'description' => 'Test package',
        'price' => 12000,
        'vehicle_type' => '4x4',
        'type' => 'safari',
        'active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $mail = new AdminNewLeadNotification(
        Lead::create([
            'first_name' => 'Test',
            'phone' => '+254700000000',
            'email' => 'traveller@example.com',
            'partner_package_id' => $packageId,
            'start_date' => '2026-11-15',
            'status' => 'new',
        ])
    );

    $to = $mail->envelope()->to;

    expect($to)
        ->toHaveCount(2)
        ->and($to[0]->address)->toBe('africa@vumbiventures.com')
        ->and($to[1]->address)->toBe('vumbiventures@gmail.com');
});

it('uses the existing destination route name for destination links', function () {
    expect(route('pages.destination', ['destination' => 'maasai-mara'], false))
        ->toBe('/destinations/maasai-mara');
});
