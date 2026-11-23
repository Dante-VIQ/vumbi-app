<?php

/**
 * Starting points for email campaigns. The first three mirror the automated welcome sequence
 * (their static versions: the automated ones also pull in persona-specific trips). Body is Markdown.
 * Add your own here, or just duplicate a past campaign in the admin.
 */
return [
    'blank' => [
        'label' => 'Blank',
        'description' => 'Start from nothing.',
        'name' => '',
        'subject' => '',
        'preheader' => '',
        'body' => '',
        'cta_label' => '',
        'cta_url' => '',
    ],

    'welcome' => [
        'label' => 'Welcome + first-safari checklist',
        'description' => 'The email new subscribers get on day 0.',
        'name' => 'Welcome and checklist',
        'subject' => 'Your Kenya first-safari checklist (and what to read first)',
        'preheader' => 'The planning basics, plus three articles to start with.',
        'body' => "Hello,\n\nThank you for signing up. Here is the planning checklist we wish every first-time safari traveller had.\n\nIf you only read three things first:\n\n1. [How to plan your first safari in Kenya](/blog)\n2. [The best time to visit Kenya for a safari](/blog)\n3. [Browse all Field Notes](/blog)\n\nAlready thinking about a trip? Reply to this email and we will match you with vetted local partners and send a custom itinerary or quote within 24 hours.\n\nThe Vumbi Ventures team  \nNakuru, Kenya",
        'cta_label' => 'Open the checklist',
        'cta_url' => '/safari-checklist',
    ],

    'trips' => [
        'label' => 'Trips from our partners',
        'description' => 'The day-3 email, as a one-off to everyone.',
        'name' => 'Trips from our partners',
        'subject' => 'Trips from our local partners',
        'preheader' => 'Prices are per person, shown up front.',
        'body' => "Hello,\n\nHere are trips from the local partners we work with. Prices are per person, in US dollars, and shown up front.\n\nNot sure what fits? [Take the 1-minute quiz](/quiz) and we will point you to the right trips.",
        'cta_label' => 'Browse trips',
        'cta_url' => '/tours',
    ],

    'questions' => [
        'label' => 'Questions before you book?',
        'description' => 'The day-7 email: invites replies and WhatsApp messages.',
        'name' => 'Questions before you book',
        'subject' => 'Questions before you book? Ask us',
        'preheader' => 'A real person replies, usually within 24 hours.',
        'body' => "Hello,\n\nPlanning a trip usually raises the same questions: when to go, what it costs, what is included, and whether a partner is the right fit.\n\nReply to this email with yours. Tell us who is coming, roughly when, and what you would like to see, and we will send a custom itinerary or quote within 24 hours.\n\nThe Vumbi Ventures team  \nNakuru, Kenya",
        'cta_label' => 'Ask us a question',
        'cta_url' => '/contact',
    ],

    'digest' => [
        'label' => 'Field Notes update',
        'description' => 'A short note linking to your newest articles.',
        'name' => 'Field Notes update',
        'subject' => 'New from Field Notes',
        'preheader' => 'New stories from East Africa.',
        'body' => "Hello,\n\nHere is what we published recently:\n\n- [Article title](/blog)\n- [Article title](/blog)\n\nWrite one or two sentences here about why these are worth reading.\n\nThe Vumbi Ventures team  \nNakuru, Kenya",
        'cta_label' => 'Read Field Notes',
        'cta_url' => '/blog',
    ],

    'seasonal' => [
        'label' => 'Seasonal trip idea',
        'description' => 'For a holiday or season: a specific trip, a date, one action.',
        'name' => 'Seasonal trip idea',
        'subject' => 'Planning a trip for the holidays?',
        'preheader' => 'Tell us who is coming and when.',
        'body' => "Hello,\n\nIf you are planning a trip over the holidays, it is worth starting soon, since the most popular dates fill up first.\n\nTell us who is coming, roughly when, and your budget, and we will reply within 24 hours with options from partners we vet.\n\nThe Vumbi Ventures team  \nNakuru, Kenya",
        'cta_label' => 'Get a custom itinerary',
        'cta_url' => '/contact',
    ],
];
