<?php

namespace App\Jobs;

use App\Models\City;
use App\Services\Builders\CityPageBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BuildCityDiscoveryPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 420;
    public int $backoff = 60;

    public function __construct(
        public readonly string $searchTerm
    ) {}

    public function handle(CityPageBuilder $builder): void
    {
        $searchTerm = trim($this->searchTerm);
        $slug = Str::slug($searchTerm);

        Log::info('City build job started', ['city' => $searchTerm]);

        $city = City::firstOrCreate(
            ['slug' => $slug],
            [
                'name'   => $searchTerm,
                'status' => 'building',
            ]
        );

        // All heavy lifting is delegated to the builder
        $builder->build($city);

        Log::info('City build job finished', ['city' => $searchTerm]);
    }
}