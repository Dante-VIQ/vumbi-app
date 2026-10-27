<?php

/*
| The free resource offered to each persona on the quiz and in the welcome emails.
|
| Today every persona gets the Kenya first-safari checklist (a real page on the site).
| To give a persona its own download, set `url` to the page or file (for example
| asset('downloads/adventure-packing-list.pdf')) and update `title` and `description`.
| Only point at files that exist: nothing here is generated.
*/

return [
    'default' => [
        'title' => 'Kenya first-safari checklist',
        'description' => 'What to pack, book and ask before your first safari.',
        'route' => 'safari-checklist',
    ],

    // Optional per-persona overrides: 'adventure' | 'youth' | 'group' => ['title' =>, 'description' =>, 'url' => or 'route' =>]
    'personas' => [],
];
