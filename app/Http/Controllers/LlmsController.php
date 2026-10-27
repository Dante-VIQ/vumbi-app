<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\PartnerPackage;
use App\Support\TripStyles;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

/**
 * /llms.txt: a plain-markdown map of the site for AI tools (llmstxt.org format).
 * Generated from the database so it never goes stale. No AI provider has formally
 * committed to reading it, so treat it as a cheap extra, not a ranking lever.
 */
class LlmsController extends Controller
{
    public function __invoke()
    {
        $lines = [];
        $lines[] = '# ' . config('seo.name');
        $lines[] = '';
        $lines[] = '> ' . config('seo.tagline');
        $lines[] = '';
        $lines[] = 'Based in ' . config('seo.locality') . ', Kenya. Vumbi Ventures does not operate vehicles itself: it connects travellers with local partners it vets, and shows prices in Kenya shillings (KSh) up front.';
        $lines[] = '';
        $lines[] = '## Plan a trip';
        $lines[] = '- [Find your trip](' . route('discover.index') . '): search destinations and filter by interest, budget, duration and group size';
        $lines[] = '- [All trips](' . route('tours.index') . '): catalogue of partner trips with starting prices';
        foreach (TripStyles::all() as $slug => $style) {
            $lines[] = '- [' . $style['name'] . '](' . route('trips.show', $slug) . '): ' . Str::limit(strip_tags($style['description']), 140);
        }
        $lines[] = '';
        $lines[] = '## About';
        $lines[] = '- [About Vumbi Ventures](' . url('/about') . '): how the company works with vetted partners';
        $lines[] = '- [Contact](' . route('contact') . '): enquiries are answered within 24 business hours';
        $lines[] = '';

        $posts = Blog::latest()->take(25)->get(['id', 'slug', 'title', 'category', 'meta_description']);
        if ($posts->isNotEmpty()) {
            $lines[] = '## Field Notes and guides';
            foreach ($posts as $post) {
                $desc = $post->meta_description ? ': ' . Str::limit(strip_tags($post->meta_description), 140) : '';
                $lines[] = '- [' . $post->title . '](' . $post->url . ')' . $desc;
            }
            $lines[] = '';
        }

        $tours = PartnerPackage::active()->whereNotNull('slug')->latest()->take(25)->get(['title', 'slug', 'location', 'price']);
        if ($tours->isNotEmpty()) {
            $lines[] = '## Trips';
            foreach ($tours as $tour) {
                $lines[] = '- [' . $tour->title . '](' . route('tours.show', $tour->slug) . '): ' . $tour->location . ', from KSh ' . number_format((float) $tour->price);
            }
            $lines[] = '';
        }

        $lines[] = '## Optional';
        $lines[] = '- [Sitemap](' . url('/sitemap.xml') . ')';

        return response(implode("\n", $lines) . "\n", 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
