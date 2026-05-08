<?php

namespace App\Jobs;

use App\Models\City;
use App\Services\Builders\CityPageBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class BuildCityDiscoveryPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;   // 10 minutes
    public int $backoff = 90;

    public function __construct(
        public readonly string $searchTerm
    ) {}

    public function handle(CityPageBuilder $builder): void
    {
        $slug = Str::slug($this->searchTerm);

        $city = City::firstOrCreate(
            ['slug' => $slug],
            ['name' => $this->searchTerm, 'status' => 'building']
        );

        // Skip if recently built
        if ($city->status === 'published' && $city->last_refreshed_at?->gt(now()->subHours(24))) {
            return;
        }

        $builder->build($city);
    }
}