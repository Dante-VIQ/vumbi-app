<?php

return [
    /*
    | Destinations pre-built by `php artisan discover:warm` so visitors get an
    | instant guide instead of waiting 15-45 seconds. Edit this list freely.
    */
    'warm_destinations' => [
        'Maasai Mara', 'Nairobi', 'Mombasa', 'Diani Beach', 'Amboseli', 'Lake Nakuru',
        'Lake Naivasha', 'Tsavo', 'Samburu', 'Lamu', 'Mount Kenya', "Hell's Gate",
        'Laikipia', 'Watamu', 'Malindi', 'Zanzibar', 'Serengeti', 'Ngorongoro',
        'Arusha', 'Kilimanjaro', 'Tarangire', 'Lake Manyara', 'Dar es Salaam', 'Kigali',
        'Volcanoes National Park', 'Bwindi', 'Entebbe', 'Jinja', 'Kampala', 'Queen Elizabeth National Park',
    ],

    // A guide built within this many days is considered fresh and skipped.
    'warm_fresh_days' => 14,
];
