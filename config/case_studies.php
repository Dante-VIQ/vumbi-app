<?php

/*
|--------------------------------------------------------------------------
| Studio case studies
|--------------------------------------------------------------------------
|
| One entry per client build, rendered on /studio and /studio/case-studies/{slug}.
|
| Optional fields that are only shown when filled in (leave empty rather than
| guess): `results` (list of ['value' => '40%', 'label' => 'fewer no-shows']),
| `quote` (['text' => '', 'name' => '', 'role' => '']), `stack`, `timeline`.
| Only add results and quotes that the client has confirmed.
|
*/

return [
    'seattle-mobility-solutions' => [
        'name' => 'Seattle Mobility Solutions',
        'industry' => 'Non-emergency medical transportation (NEMT)',
        'market' => 'United States',
        'url' => 'https://mobilityseattle.com',
        'url_label' => 'mobilityseattle.com',
        'summary' => 'A specialized Non-Emergency Medical Transportation (NEMT) dispatch and fleet engine built for US healthcare corridors. Integrates wheelchair/gurney accessibility routing, facility-to-facility patient scheduling, automated dispatching algorithms, and trip log management.',
        'tags' => ['Fleet Dispatch', 'NEMT Logistics', 'Driver Portal'],
        'brief' => 'An NEMT operator needs to move patients between homes, clinics and facilities with the right vehicle for each person (wheelchair, gurney, ambulatory), on time, with a record of every trip. Running that on phone calls and spreadsheets does not scale, and an online presence that does not rank means new facility referrals never find the business.',
        'built' => [
            'A single architecture, proposed and built by us, that brings the operator\'s services together',
            'Dispatch and fleet engine with accessibility-aware routing (wheelchair, gurney, ambulatory)',
            'Facility-to-facility patient scheduling and automated dispatch',
            'Driver portal for assignments and trip records',
            'Trip log management designed around healthcare record-keeping needs',
            'SEO foundation: pillar pages and local service pages aimed at facility and patient searches',
        ],
        'engineering' => [
            'Migrated from WordPress: cleaned up the legacy URL redirect debt so existing search equity was not lost.',
            'Worked within shared cPanel hosting limits, including a constrained queue-worker setup, instead of requiring a costly infrastructure change.',
            'Delivered reusable frontend components so new service and location pages can ship without a developer rebuilding layout each time.',
        ],
        'stack' => ['Laravel'],
        'results' => [
            ['value' => '3+ hrs', 'label' => 'saved every day by bringing their services together on one architecture'],
        ],
        'quote' => null,
    ],

    'ride-aide-llc' => [
        'name' => 'Ride Aide LLC',
        'industry' => 'Medical and senior transportation',
        'market' => 'United States',
        'url' => 'https://rideaidellc.com',
        'url_label' => 'rideaidellc.com',
        'summary' => 'An end-to-end transportation booking platform designed for medical appointments and senior transport. Features live GPS ride tracking, dynamic distance-based fare estimation, automated SMS notifications for patients, and driver shift optimization.',
        'tags' => ['Live GPS Tracking', 'Ride Booking', 'Automated Dispatch'],
        'brief' => 'Seniors and patients need a simple way to book a ride to an appointment, know the price before they commit, and know when the driver is arriving. The business needs to assign drivers and shifts without constant phone coordination.',
        'built' => [
            'Online ride booking for medical appointments and senior transport',
            'Distance-based fare estimation shown before booking',
            'Live GPS ride tracking',
            'Automated SMS notifications to patients',
            'Driver shift management and dispatch',
        ],
        'engineering' => [],
        'stack' => [],
        'results' => [
            ['value' => '20+', 'label' => 'rides every 14 days, from online bookings and direct calls'],
        ],
        'quote' => null,
    ],

    'gb-handyman-llc' => [
        'name' => 'GB Handyman LLC',
        'industry' => 'Home services',
        'market' => 'United States',
        'url' => 'https://gbhandymanllc.com',
        'url_label' => 'gbhandymanllc.com',
        'summary' => 'A home services booking and business management hub. Homeowners request estimates, schedule contractor site visits, receive digital invoices, and track job progress.',
        'tags' => ['Quote Engine', 'Job CRM', 'Invoicing'],
        'brief' => 'A handyman business runs on estimates, site visits and invoices. When these live in texts and notebooks, quotes get lost, follow-ups slip and customers are left guessing about progress.',
        'built' => [
            'Online estimate requests',
            'Scheduling of contractor site visits',
            'Digital invoicing',
            'Job tracking so customers can follow progress',
            'Business-side job CRM',
        ],
        'engineering' => [],
        'stack' => [],
        'results' => [
            ['value' => '15', 'label' => 'clients, consistently'],
        ],
        'quote' => null,
    ],

    'amazing-palace' => [
        'name' => 'Amazing Palace',
        'industry' => 'Retail and e-commerce',
        'market' => 'United States',
        'url' => 'https://amazingpalace.com',
        'url_label' => 'amazingpalace.com',
        'summary' => 'A full-stack luxury retail and e-commerce portal with a product catalog, online reservations, inventory control, and secure payment checkout.',
        'tags' => ['E-Commerce', 'Payment Gateway', 'Inventory Management'],
        'brief' => 'A luxury retailer needs a storefront that presents products well, takes reservations and payments securely, and keeps stock levels accurate behind the scenes.',
        'built' => [
            'Product catalog showcase',
            'Online reservation system',
            'Inventory control',
            'Secure payment checkout integration',
            'Data protection compliance for the facility\'s clients',
        ],
        'engineering' => [],
        'stack' => [],
        'results' => [
            ['value' => '10+', 'label' => 'additional bookings'],
            ['value' => 'Compliant', 'label' => 'with data protection regulations for its clients'],
        ],
        'quote' => null,
    ],
];
