<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Culture;
use App\Models\Destination;
use App\Models\PartnerPackage;
use Illuminate\Routing\Controller;

class SitemapController extends Controller
{
    public function index()
    {
        $cacheKey = 'sitemap_content';
        $cached = cache()->get($cacheKey);

        if ($cached && ! app()->environment('local')) {
            return response($cached, 200)->header('Content-Type', 'text/xml');
        }

        $staticPages = [
            (object) ['loc' => url('/'), 'lastmod' => now()->toDateString(), 'priority' => '1.0', 'changefreq' => 'weekly'],
            (object) ['loc' => url('/services'), 'lastmod' => now()->toDateString(), 'priority' => '0.8', 'changefreq' => 'monthly'],
            (object) ['loc' => url('/contact'), 'lastmod' => now()->toDateString(), 'priority' => '0.6', 'changefreq' => 'yearly'],
            (object) ['loc' => url('/discover'), 'lastmod' => now()->toDateString(), 'priority' => '0.9', 'changefreq' => 'weekly'],
            (object) ['loc' => url('/blog'), 'lastmod' => now()->toDateString(), 'priority' => '0.9', 'changefreq' => 'daily'],
            (object) ['loc' => url('/tours'), 'lastmod' => now()->toDateString(), 'priority' => '0.9', 'changefreq' => 'daily'],
            (object) ['loc' => url('/about'), 'lastmod' => now()->toDateString(), 'priority' => '0.9', 'changefreq' => 'daily'],
            (object) ['loc' => url('/destinations'), 'lastmod' => now()->toDateString(), 'priority' => '0.8', 'changefreq' => 'weekly'],
            (object) ['loc' => url('/culture'), 'lastmod' => now()->toDateString(), 'priority' => '0.8', 'changefreq' => 'weekly'],
            (object) ['loc' => url('/safari-checklist'), 'lastmod' => now()->toDateString(), 'priority' => '0.7', 'changefreq' => 'monthly'],
            (object) ['loc' => url('/privacy-policy'), 'lastmod' => now()->toDateString(), 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        $blogs = Blog::orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($post) {
                $post->loc = route('blog.show', $post->id);
                $post->lastmod = $post->updated_at->toDateString();
                $post->priority = '0.7';
                $post->changefreq = 'monthly';

                return $post;
            });

        // Tour pages are the pages that convert, so they must be crawlable.
        $tours = PartnerPackage::active()
            ->whereNotNull('slug')
            ->get()
            ->map(fn ($package) => (object) [
                'loc' => route('tours.show', $package->slug),
                'lastmod' => ($package->updated_at ?? now())->toDateString(),
                'priority' => '0.8',
                'changefreq' => 'weekly',
            ]);

        $destinations = Destination::whereNotNull('slug')
            ->get()
            ->map(fn ($destination) => (object) [
                'loc' => route('pages.destination', $destination->slug),
                'lastmod' => ($destination->updated_at ?? now())->toDateString(),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ]);

        $cultures = Culture::all()
            ->map(fn ($culture) => (object) [
                'loc' => route('pages.culture', $culture->id),
                'lastmod' => ($culture->updated_at ?? now())->toDateString(),
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ]);

        $all = collect($staticPages)
            ->merge($blogs)
            ->merge($tours)
            ->merge($destinations)
            ->merge($cultures);

        $view = view('sitemap', ['pages' => $all])->render();

        // Cache for 24 hours in production
        if (! app()->environment('local')) {
            cache()->put($cacheKey, $view, now()->addDay());
        }

        return response($view, 200)->header('Content-Type', 'text/xml');
    }
}
