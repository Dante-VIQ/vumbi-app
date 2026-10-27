<?php

/*
| Optional content for the About page. Anything left empty is simply not shown,
| so nothing on the page is made up. Add real details here when you have them.
*/

return [
    // Founder note: shown only when both `name` and `note` are filled.
    'founder' => [
        'name' => null,
        'role' => null,
        'note' => null,
        'photo' => null,   // path inside /public, e.g. 'images/team/dante.jpg'
    ],

    // Extra team members: [['name' => '', 'role' => '', 'photo' => 'images/team/x.jpg']]
    'team' => [],

    // Real numbers you can stand behind: [['value' => '120+', 'label' => 'Travellers helped']]
    'proof' => [],
];
