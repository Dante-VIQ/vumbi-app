<?php

/*
| Optional content for the About page. Anything left empty is simply not shown,
| so nothing on the page is made up. Add real details here when you have them.
*/

return [
    // Founder note: shown only when both `name` and `note` are filled.
    // DRAFT written from what is already public on the site. Edit it so it sounds like you,
    // add your surname if you want it shown, and replace the scenic image with a real photo of
    // yourself by setting `photo` (path inside /public, e.g. 'images/team/dante.jpg').
    'founder' => [
        'name' => 'Dante',
        'role' => 'Founder',
        'note' => "I'm a self-taught developer from Kenya, and I started Vumbi Ventures to make East Africa easier to plan and easier to understand.\n\n\"Vumbi\" is Swahili for dust. It is a reminder to look closely at what others walk past: the driver-guide who knows the road, the festival nobody wrote about, the history that never made it into the guidebook.\n\nSo the site does two things. It connects you with local partners we vet, with prices shown up front. And it publishes Field Notes about African history, culture and places, so you know what you are heading into before you go.\n\nIf you are planning a trip, tell us who is coming and when. We reply within 24 business hours.",
        'photo' => null,
        // Used while there is no portrait. A landscape, not a picture of the founder.
        'scene' => [
            'image' => 'https://images.unsplash.com/photo-1747348070600-879dd45881e3?fm=jpg&q=75&w=1200&auto=format&fit=crop',
            'alt' => 'Flamingos and herons wading in calm water at Lake Nakuru, Kenya',
            'caption' => 'Lake Nakuru, a short drive from our base in Nakuru.',
            'credit' => 'Simon Van Rompaey',
            'credit_url' => 'https://unsplash.com/photos/flamingos-and-herons-wading-in-calm-water-0gxSzq8m7ns',
        ],
    ],

    // Extra team members: [['name' => '', 'role' => '', 'photo' => 'images/team/x.jpg']]
    'team' => [],

    // Real numbers you can stand behind: [['value' => '120+', 'label' => 'Travellers helped']]
    'proof' => [],
];
