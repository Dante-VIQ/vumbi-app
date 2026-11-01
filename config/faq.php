<?php

/*
| Questions and answers for /faq, grouped by topic. The same text is published as FAQPage schema.
| Write answers that stand on their own (an AI assistant may quote one without the question) and
| only state things that are true today. Links: use plain URLs or route names via the controller.
| Prices are shown in USD at a fixed conversion of the partner's KSh price, so say "from" and "starting".
*/

return [
    'About Vumbi Ventures' => [
        ['q' => 'What is Vumbi Ventures?', 'a' => null, 'definition' => true],
        ['q' => 'What does Vumbi Ventures do?', 'a' => 'Vumbi Ventures helps people plan trips to Kenya and East Africa. You can take a one-minute quiz or search destinations to see trips that fit who is travelling, what you enjoy, your budget and how long you have. We connect you with local partners we vet, show starting prices up front, and reply to enquiries within 24 business hours. We also publish Field Notes, guides to African history, culture and destinations.'],
        ['q' => 'Where is Vumbi Ventures based?', 'a' => 'Vumbi Ventures is based in Nakuru, Kenya. We serve travellers from anywhere, and our trips are in Kenya and across East Africa.'],
        ['q' => 'What does "Vumbi" mean?', 'a' => '"Vumbi" is Swahili for dust. The name is a reminder to look closely at what others overlook: local guides, small places and stories that rarely make the guidebooks.'],
        ['q' => 'Does Vumbi Ventures operate the tours itself?', 'a' => 'No. Vumbi Ventures does not run the vehicles. We connect travellers with local transport and tour partners that we vet, so the trip itself is delivered by the partner.'],
        ['q' => 'What is Vumbi Tech Studio?', 'a' => 'Vumbi Tech Studio is a separate web development and digital growth service run alongside the travel business. It builds websites and web apps for brands. If you want to plan a trip, you do not need it: use the travel pages or the contact form.'],
    ],
    'Planning a trip' => [
        ['q' => 'How do I plan a trip with Vumbi Ventures?', 'a' => 'Start with the one-minute quiz or the destination search to see matching trips, or open a trip style page: adventure seekers, youth explorers or groups. When you are ready, send an enquiry with your dates, group size and budget, or message us on WhatsApp. We reply within 24 business hours.'],
        ['q' => 'What kinds of trips can I find?', 'a' => 'Trips are tagged by who they suit and what they involve: hikes and climbs, wildlife safaris, culture, beaches and festivals. We organise them for adventure seekers, youth and budget travellers, and groups such as families, friends, schools and teams.'],
        ['q' => 'How are your partners vetted?', 'a' => 'We work with licensed local transport partners, inspected 4x4s and vans, and certified driver-guides, and we show prices up front.'],
        ['q' => 'Can you help with a group or a custom itinerary?', 'a' => 'Yes. Tell us who is coming, your dates and budget through the contact form or WhatsApp, and we will come back with options and prices from partners.'],
        ['q' => 'Is there a free checklist for a first safari?', 'a' => 'Yes. The Kenya first-safari checklist is free on our site, and new Field Notes subscribers are sent a link to it.'],
    ],
    'Prices and booking' => [
        ['q' => 'What currency are your prices in?', 'a' => 'Prices on the site are shown in US dollars. Our partners price their trips in Kenya shillings, and we convert them at a fixed rate, so the amount you pay the partner can differ slightly from the figure shown. Always confirm the final price in your quote.'],
        ['q' => 'Are the listed prices final?', 'a' => 'Listed prices are starting prices for the trip as described. Dates, group size, accommodation and extras can change the total, so we confirm the full price with you before you commit.'],
        ['q' => 'How fast will I get a reply?', 'a' => 'We reply to enquiries within 24 business hours. WhatsApp is usually quicker for simple questions.'],
        ['q' => 'Does Vumbi Ventures earn commission on bookings?', 'a' => 'Some links on our site go to travel partners such as booking platforms. If you book through them we may earn a commission at no extra cost to you. This is explained in our terms of service.'],
    ],
    'Field Notes and the newsletter' => [
        ['q' => 'What are Field Notes?', 'a' => 'Field Notes are our guides and stories about African history, culture and destinations, written to help you understand a place before you travel.'],
        ['q' => 'How often will I get emails if I subscribe?', 'a' => 'About once a month, plus two short follow-up emails in your first week. Every email has an unsubscribe link.'],
    ],
];
