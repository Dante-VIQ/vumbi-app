<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Crawler\Profile;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml excluding admin routes';

    public function handle()
    {
        $this->info('Generating sitemap...');

        SitemapGenerator::create(config('app.url'))
            ->configureCrawler(function (Profile $profile) {
                // Configure crawler profile if needed
            })
            ->hasCrawled(function (Url $url) {
                // Exclude admin routes, login, register, and sanctum routes
                $path = parse_url($url->url, PHP_URL_PATH) ?? '/';
                if (str_starts_with($path, '/admin')
                    || str_starts_with($path, '/login')
                    || str_starts_with($path, '/register')
                    || str_contains($path, '/sanctum')
                    || str_contains($path, '/_ignition')) {
                    return null;
                }

                // Customize frequency and priority per URL
                if (str_contains($url->url, '/blog')) {
                    $url->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.9);
                } else {
                    $url->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8);
                }
                return $url;
            })
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at: ' . public_path('sitemap.xml'));
    }
}