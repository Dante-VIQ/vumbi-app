<?php

/*
| Entity facts used in structured data (Organization schema) and llms.txt.
| Keep these accurate and in sync with the Contact page: AI engines and Google
| cross-check them. Leave a value empty to omit it from the markup.
*/

return [
    'name' => 'Vumbi Ventures',
    // The one-paragraph answer to "What is Vumbi Ventures?". Used on About, the FAQ, schema and llms.txt.
    // Keep it factual and in the same words everywhere: AI engines repeat what they find consistently.
    'definition' => 'Vumbi Ventures is a Kenya-based travel discovery and storytelling company in Nakuru. It connects travellers with vetted local partners for trips across Kenya and East Africa, with prices shown up front, and publishes Field Notes: guides to the history, culture and places of Africa. Vumbi also runs Vumbi Tech Studio, a separate web development service.',
    'alternate_name' => 'Vumbi',
    'slogan' => 'Travel East Africa with people who know it',
    'knows_about' => ['Kenya safaris', 'East Africa travel planning', 'Hiking and climbing in Kenya', 'Budget and youth travel in East Africa', 'Group travel in East Africa', 'African history and culture', 'Local tour and transport partners'],
    'tagline' => 'Travel discovery and storytelling for Kenya and East Africa, connecting travellers with vetted local partners.',
    'email' => env('SEO_EMAIL', 'vumbiventures@gmail.com'),
    'telephone' => env('SEO_PHONE', '+254-734-591543'),
    'locality' => 'Nakuru',
    'country' => 'KE',
    // Only list profiles that exist. Add Facebook / TikTok URLs here once confirmed.
    'same_as' => array_values(array_filter(explode(',', (string) env('SEO_SAME_AS', 'https://instagram.com/vumbiventures')))),
    // Set false to disallow nothing; list user-agents here only if you decide to block a crawler.
    'ai_crawlers' => [
        'GPTBot' => 'OpenAI (training)',
        'OAI-SearchBot' => 'OpenAI (ChatGPT search)',
        'ChatGPT-User' => 'OpenAI (user-requested fetch)',
        'ClaudeBot' => 'Anthropic (training)',
        'Claude-SearchBot' => 'Anthropic (search)',
        'Claude-User' => 'Anthropic (user-requested fetch)',
        'PerplexityBot' => 'Perplexity (search index)',
        'Perplexity-User' => 'Perplexity (user-requested fetch)',
        'Google-Extended' => 'Google (Gemini training control)',
        'Applebot-Extended' => 'Apple (training control)',
        'Bytespider' => 'ByteDance',
        'CCBot' => 'Common Crawl',
        'Meta-ExternalAgent' => 'Meta',
        'Amazonbot' => 'Amazon',
    ],
];
