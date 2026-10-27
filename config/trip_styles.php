<?php

/*
| Copy and settings for the three trip-style landing pages (/trips/{slug}).
| Edit freely: nothing here is read from the database. Keys are URL slugs;
| `persona` links each page to the persona key used for tagging and ranking.
|
| Keep claims you cannot back up out of this file. Prices and trips on each page
| come from the tours you have tagged for that persona, not from this text.
*/

return [
    'adventure' => [
        'persona' => 'adventure',
        'name' => 'Adventure seekers',
        'badge' => 'For adventure seekers',
        'title' => 'Adventure Trips in Kenya & East Africa | Vumbi Ventures',
        'description' => 'Hiking, climbing and multi-day expeditions in Kenya and East Africa, with upfront starting prices and vetted local operators. Request a custom quote.',
        'h1' => "Adventure trips for people who'd rather be outside",
        'intro' => "Climb Mount Kenya, walk the Chogoria forest or pair a summit with a Maasai Mara safari. Tell us your experience and dates, and we'll match you with a vetted local operator.",
        'who' => [
            'Hikers and climbers looking for a summit or a multi-day route',
            'Travellers who like to earn their wildlife sightings',
            'First-timers who want honest advice on what is realistic',
        ],
        'good_to_know' => [
            "Check the difficulty level and number of days on each trip page before you ask for a quote.",
            'Altitude affects people differently. Ask us and your doctor about acclimatisation before booking a high-altitude climb.',
            'Seasons change what is possible on a mountain. Tell us your dates and we will say which months suit your route.',
        ],
        'faq' => [
            ['q' => 'How fit do I need to be?', 'a' => 'It depends on the trip. Each trip page shows a difficulty level and the number of days. Tell us your recent hiking experience and we will suggest options that suit you, including easier ones.'],
            ['q' => 'Can I combine a climb with a safari?', 'a' => 'Yes. Some trips pair a mountain with a Maasai Mara safari in one itinerary, and we can ask a partner to build a custom combination.'],
            ['q' => 'What is included in the price?', 'a' => "Each trip page lists what is included and excluded. If something you need is not listed, ask us before you book and we will check with the operator."],
            ['q' => 'When is the best time to go?', 'a' => 'It depends on the route. Tell us when you can travel and we will say what to expect that month. Our Field Notes also cover the seasons.'],
        ],
        'blog_keywords' => ['kilimanjaro', 'mount kenya', 'hik', 'climb', 'trek'],
        'cta' => 'Plan my adventure',
    ],

    'youth' => [
        'persona' => 'youth',
        'name' => 'Youth explorers',
        'badge' => 'For youth explorers',
        'title' => 'Budget Trips in Kenya & East Africa for Young Travellers | Vumbi Ventures',
        'description' => 'Affordable trips, coastal getaways and festivals in Kenya and East Africa, with starting prices shown upfront. Tell us your budget and get a custom quote.',
        'h1' => 'Budget-friendly trips for young explorers',
        'intro' => 'From the coast to cultural festivals, find trips that fit a student or first-job budget. We show starting prices upfront and tell you what is included.',
        'who' => [
            'Students and young professionals travelling on a budget',
            'Solo travellers and small groups of friends',
            'People who care more about the experience than the thread count',
        ],
        'good_to_know' => [
            'Starting prices are per person. The final price depends on dates, group size and what you choose to include.',
            'Travelling with friends can bring the per-person cost down. Ask us about sharing a vehicle.',
            "Tell us your real budget. We would rather suggest something that fits than something that doesn't.",
        ],
        'faq' => [
            ['q' => 'Can I travel solo?', 'a' => 'Yes. Tell us your dates and we will say which trips work for one person and what the cost looks like compared with sharing.'],
            ['q' => 'What does "from $X" mean?', 'a' => 'It is the starting price per person for that trip. Your final quote depends on your dates, group size and any extras.'],
            ['q' => 'Can you work with a small budget?', 'a' => "We will try. Tell us what you can spend and what matters most, and we will suggest what is realistic, or say so if it isn't."],
            ['q' => 'Are there festivals and cultural trips too?', 'a' => 'Yes. Browse the culture and festival trips on the discovery page, or ask us about a festival you have in mind.'],
        ],
        'blog_keywords' => ['budget', 'festival', 'chale wote', 'matatu', 'diani', 'zanzibar'],
        'cta' => 'Plan my trip',
    ],

    'groups' => [
        'persona' => 'group',
        'name' => 'Groups',
        'badge' => 'For groups',
        'title' => 'Group Trips & Private Safaris in Kenya & East Africa | Vumbi Ventures',
        'description' => 'Family, friends, school and company trips in Kenya and East Africa, with private vehicles, per-person prices and vetted local partners. Get a custom group quote.',
        'h1' => 'Group trips without the planning headache',
        'intro' => "Family reunions, friends' trips, school and company outings: tell us who is coming and when, and we will come back with options, vehicle choices and a per-person price.",
        'who' => [
            'Families and friends travelling together',
            'Schools, clubs and company or church groups',
            'Anyone who needs a private vehicle for a larger party',
        ],
        'good_to_know' => [
            'Prices are per person and change with group size, season and vehicle type.',
            'Tell us ages and any mobility needs up front so we can suggest suitable trips.',
            'Popular months fill up, so ask early if you have fixed dates.',
        ],
        'faq' => [
            ['q' => 'How does group pricing work?', 'a' => 'Trip prices are per person. The final quote depends on how many of you are travelling, your dates and the vehicle needed, so tell us the group size when you ask.'],
            ['q' => 'Can we have a private vehicle?', 'a' => 'We can ask our local transport partners for a private vehicle for your group. Tell us the group size and we will say what is possible.'],
            ['q' => 'Can you plan for children or older travellers?', 'a' => 'Yes. Tell us the ages and any needs and we will suggest trips and pacing that suit everyone, and say honestly when a trip is not a good fit.'],
            ['q' => 'How far ahead should we ask?', 'a' => 'As early as you can, especially for school holidays and migration season. We usually reply within a day.'],
        ],
        'blog_keywords' => ['family', 'first safari', 'group', 'wedding', 'planning'],
        'cta' => 'Plan our group trip',
    ],
];
