<?php

namespace Database\Seeders;

use App\Models\AffiliateProgram;
use Illuminate\Database\Seeder;

class AffiliateProgramSeeder extends Seeder
{
    public function run(): void
    {
        // Example Travelpayouts programs (add your real ones)
        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Booking.com / Hotellook',
            'type' => 'hotel',
            'keywords' => json_encode(['hotel', 'lodge', 'maasai mara', 'diani', 'nakuru', 'safari', 'luxury', 'accommodation']),
            'description' => 'Search and book hotels & lodges across Kenya and East Africa',
            'widget_code' => '<div class="tp-widget">...</div>', // paste your actual widget code here
            'priority' => 90,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Viator / GetYourGuide',
            'type' => 'tour',
            'keywords' => json_encode(['tour', 'safari', 'activity', 'excursion', 'cultural experience']),
            'description' => 'Book guided tours, safaris and activities',
            'priority' => 80,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'GetYourGuide',
            'type' => 'tour',
            'keywords' => json_encode(['tour', 'safari', 'activity', 'excursion', 'cultural experience']),
            'description' => 'Book guided tours, safaris and activities',
            'priority' => 80,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'travelpayouts',
            'program_name' => 'Agoda',
            'type' => 'tour',
            'keywords' => json_encode(['tour', 'safari', 'activity', 'excursion', 'cultural experience']),
            'description' => 'Book guided tours, safaris and activities',
            'priority' => 80,
            'active' => true,
        ]);

        // Add Awin examples
        AffiliateProgram::create([
            'network' => 'awin',
            'program_name' => 'GoWithGuide US',
            'type' => 'insurance',
            'keywords' => json_encode(['insurance', 'travel insurance', 'safety']),
            'description' => 'Protect your African adventure',
            'affiliate_link' => 'https://www.awin1.com/cread.php?awinmid=87121&awinaffid=2580697',
            'priority' => 60,
            'active' => true,
        ]);

                AffiliateProgram::create([
            'network' => 'awin',
            'program_name' => 'AWIN',
            'type' => 'insurance',
            'keywords' => json_encode(['insurance', 'travel insurance', 'safety']),
            'description' => 'Protect your African adventure',
            'affiliate_link' => 'https://www.awin1.com/cread.php?awinmid=87121&awinaffid=2580697',
            'priority' => 60,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'awin',
            'program_name' => 'normanwalsh',
            'type' => 'insurance',
            'keywords' => json_encode(['insurance', 'travel insurance', 'safety']),
            'description' => 'Protect your African adventure',
            'affiliate_link' => 'https://www.awin1.com/cread.php?awinmid=115943&awinaffid=2580697',
            'priority' => 60,
            'active' => true,
        ]);

        AffiliateProgram::create([
            'network' => 'awin',
            'program_name' => 'PANDA GOGO LIMITED',
            'type' => 'insurance',
            'keywords' => json_encode(['insurance', 'travel insurance', 'safety']),
            'description' => 'Protect your African adventure',
            'affiliate_link' => 'https://www.awin1.com/cread.php?awinmid=51067&awinaffid=2580697',
            'priority' => 60,
            'active' => true,
        ]);
        // Add more programs as you join them
    }
}
