<?php

namespace App\Console\Commands;

use App\Jobs\BuildCityDiscoveryPage;
use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class WarmDiscoveryCities extends Command
{
    protected $signature = 'discover:warm
                            {--limit=30 : Maximum number of destinations to queue}
                            {--dry-run : List what would be queued without queuing it}';

    protected $description = 'Queue builds for the top destinations so /discover answers instantly';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));
        $fresh = now()->subDays((int) config('discovery.warm_fresh_days', 14));
        $queued = 0;
        $skipped = 0;

        foreach (config('discovery.warm_destinations', []) as $name) {
            if ($queued >= $limit) {
                break;
            }

            $city = City::where('slug', Str::slug($name))->first();

            if ($city && $city->status === 'published' && $city->last_refreshed_at?->gt($fresh)) {
                $skipped++;
                continue;
            }

            $this->line(($this->option('dry-run') ? 'Would queue: ' : 'Queued: ') . $name);

            if (! $this->option('dry-run')) {
                BuildCityDiscoveryPage::dispatch($name)->onQueue('city-build');
            }

            $queued++;
        }

        $this->info("{$queued} queued, {$skipped} already fresh.");
        $this->line('Builds run on the city-build queue, so a queue worker must be running.');

        return self::SUCCESS;
    }
}
