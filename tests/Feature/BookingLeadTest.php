<?php

use App\Mail\AdminNewLeadNotification;
use App\Mail\CustomerBookingConfirmation;
use App\Models\Lead;
use App\Models\PartnerPackage;
use App\Services\BookingService;
use Illuminate\Support\Facades\Mail;

function bookingPackage(): PartnerPackage
{
    return PartnerPackage::create([
        'location' => 'Lake Nakuru',
        'title' => 'Nakuru Day Trip',
        'description' => 'Flamingos and rhinos.',
        'price' => 120,
        'vehicle_type' => 'safari van',
        'type' => 'tour',
        'active' => true,
    ]);
}

function leadData(PartnerPackage $package, array $overrides = []): array
{
    return array_merge([
        'partner_package_id' => $package->id,
        'first_name' => 'Amina',
        'phone' => '+254700000000',
        'email' => 'amina@example.com',
        'start_date' => '2026-12-20',
    ], $overrides);
}

test('a booking lead is saved and both emails are sent', function () {
    Mail::fake();
    $package = bookingPackage();

    $lead = app(BookingService::class)->createLead(leadData($package));

    expect($lead)->toBeInstanceOf(Lead::class)
        ->and(Lead::count())->toBe(1);

    Mail::assertSent(AdminNewLeadNotification::class, 1);
    Mail::assertSent(CustomerBookingConfirmation::class, fn ($mail) => $mail->hasTo('amina@example.com'));
});

test('no customer email is sent when the visitor gave no email address', function () {
    Mail::fake();

    app(BookingService::class)->createLead(leadData(bookingPackage(), ['email' => '']));

    Mail::assertSent(AdminNewLeadNotification::class, 1);
    Mail::assertNotSent(CustomerBookingConfirmation::class);
});

test('the lead is kept even if sending email throws', function () {
    Mail::shouldReceive('send')->andThrow(new RuntimeException('smtp down'));

    $lead = app(BookingService::class)->createLead(leadData(bookingPackage()));

    expect($lead->exists)->toBeTrue()
        ->and(Lead::count())->toBe(1);
});

test('lead emails render with the package details', function () {
    $lead = app(BookingService::class)->createLead(leadData(bookingPackage()));
    Mail::fake();

    $admin = new AdminNewLeadNotification($lead);
    $customer = new CustomerBookingConfirmation($lead);

    expect($admin->envelope()->subject)->toContain('Nakuru Day Trip')
        ->and($customer->envelope()->subject)->toContain('Nakuru Day Trip')
        ->and($admin->render())->toContain('Nakuru Day Trip')->toContain('Lake Nakuru')
        ->and($customer->render())->toContain('Nakuru Day Trip');
});
