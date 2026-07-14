<?php

namespace App\Console\Commands;

use App\Models\Culture;
use App\Models\Destination;
use Illuminate\Console\Command;

class BackfillSlugs extends Command
{
    protected $signature = 'app:backfill-slugs';
    protected $description = 'Generate slugs for existing Culture and Destination rows that don\'t have one yet';

    public function handle(): int
    {
        foreach ([Culture::class, Destination::class] as $model) {
            $rows = $model::whereNull('slug')->orWhere('slug', '')->get();

            $this->info("Backfilling {$rows->count()} {$model} rows...");

            foreach ($rows as $row) {
                // Uses generateUniqueSlug() from the HasSlug trait directly,
                // then saves quietly to avoid re-triggering the updating hook.
                $row->slug = $row->generateUniqueSlug();
                $row->saveQuietly();
            }
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}