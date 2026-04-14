<?php

namespace Database\Seeders;

use App\Models\AffiliateProgram;
use Illuminate\Database\Seeder;

class AffiliateProgramSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== TRAVELPAYOUTS PROGRAMS ====================

        // --- Tours & Activities ---
        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Viator',
            'type' => 'tour',
            'keywords' => json_encode([
                'safari', 'game drive', 'guided tour', 'excursion', 'day trip',
                'cultural tour', 'historical site', 'wildlife viewing', 'national park'
            ]),
            'description' => 'Book authentic African safaris, cultural tours, and wildlife excursions with Viator.',
            'priority' => 85,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'GetYourGuide',
            'type' => 'tour',
            'keywords' => json_encode([
                'activity', 'experience', 'city tour', 'nature walk', 'museum',
                'local guide', 'village visit', 'cooking class', 'dhow cruise'
            ]),
            'description' => 'Discover unique African experiences – from spice tours in Zanzibar to gorilla trekking in Rwanda.',
            'priority' => 85,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Klook',
            'type' => 'tour',
            'keywords' => json_encode([
                'attraction ticket', 'theme park', 'snorkeling', 'boat trip',
                'waterfall', 'adventure sport', 'zip line', 'cultural show'
            ]),
            'description' => 'Book attraction tickets, day trips, and adventure activities across Africa.',
            'priority' => 80,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Tiqets',
            'type' => 'tour',
            'keywords' => json_encode([
                'museum ticket', 'art gallery', 'historical landmark',
                'palace', 'fort', 'monument', 'exhibition'
            ]),
            'description' => 'Skip-the-line tickets to Africa’s top museums, monuments, and cultural sites.',
            'priority' => 75,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'WeGoTrip',
            'type' => 'tour',
            'keywords' => json_encode([
                'self-guided tour', 'audio guide', 'walking tour',
                'mobile app', 'interactive map', 'heritage trail'
            ]),
            'description' => 'Self-guided audio tours and walking itineraries for independent explorers.',
            'priority' => 70,
            'active' => true,
        ]);

        // --- Hotels & Accommodation ---
        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Agoda',
            'type' => 'hotel',
            'keywords' => json_encode([
                'hotel', 'lodge', 'resort', 'guesthouse', 'boutique hotel',
                'beach resort', 'safari camp', 'vacation rental', 'apartment'
            ]),
            'description' => 'Find the best hotel deals, safari lodges, and beach resorts across Africa.',
            'priority' => 90,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Trip.com',
            'type' => 'hotel',
            'keywords' => json_encode([
                'accommodation', 'bed and breakfast', 'inn', 'motel',
                'budget stay', 'luxury hotel', 'family suite'
            ]),
            'description' => 'Compare hotel prices and book your perfect African stay – from budget to luxury.',
            'priority' => 85,
            'active' => true,
        ]);

        // --- Flights & Transport ---
        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Kiwi.com',
            'type' => 'flight',
            'keywords' => json_encode([
                'flight', 'air ticket', 'cheap flights', 'airline', 'airport transfer',
                'connecting flight', 'international flight', 'domestic flight'
            ]),
            'description' => 'Book cheap flights to African destinations with Kiwi.com’s smart search.',
            'priority' => 80,
            'active' => true,
        ]);

        // ==================== AWIN PROGRAMS ====================
        // (Fix types and keywords – previously insurance was wrong for GoWithGuide)

        AffiliateProgram::create([
            'network' => 'awin',
            'program_name' => 'GoWithGuide',
            'type' => 'tour',
            'keywords' => json_encode([
                'private guide', 'local tour guide', 'custom tour', 'bespoke experience',
                'personalized safari', 'city guide', 'translator'
            ]),
            'description' => 'Hire a local private guide for a truly personalised African adventure.',
            'affiliate_link' => 'https://www.awin1.com/cread.php?awinmid=87121&awinaffid=2580697',
            'priority' => 75,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'awin',
            'program_name' => 'World Nomads',
            'type' => 'insurance',
            'keywords' => json_encode([
                'travel insurance', 'medical cover', 'trip cancellation', 'lost luggage',
                'emergency evacuation', 'adventure sports cover'
            ]),
            'description' => 'Protect your African journey with flexible travel insurance from World Nomads.',
            'affiliate_link' => 'https://www.awin1.com/cread.php?awinmid=115943&awinaffid=2580697',
            'priority' => 70,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'awin',
            'program_name' => 'SafetyWing',
            'type' => 'insurance',
            'keywords' => json_encode([
                'nomad insurance', 'remote worker cover', 'long term travel',
                'digital nomad', 'global health insurance'
            ]),
            'description' => 'Travel medical insurance designed for digital nomads and long‑term travellers.',
            'affiliate_link' => 'https://www.awin1.com/cread.php?awinmid=51067&awinaffid=2580697',
            'priority' => 65,
            'active' => true,
        ]);

        // Optional: Add a generic placeholder for other Awin programs you join later
        // AffiliateProgram::create([...]);
    }
}