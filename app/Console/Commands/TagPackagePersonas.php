<?php

namespace App\Console\Commands;

use App\Models\PartnerPackage;
use App\Support\PackageTagger;
use Illuminate\Console\Command;

class TagPackagePersonas extends Command
{
    protected $signature = 'packages:tag-personas
                            {--dry-run : Show the suggested tags without saving}
                            {--overwrite : Replace tags that are already set}';

    protected $description = 'Suggest persona, interest, budget and accommodation tags for existing tours';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $overwrite = (bool) $this->option('overwrite');
        $rows = [];
        $changed = 0;

        foreach (PartnerPackage::orderBy('id')->cursor() as $package) {
            $suggested = PackageTagger::suggest($package);
            $update = [];

            foreach (['audiences', 'interests'] as $field) {
                $current = $package->{$field} ?? [];
                if ($suggested[$field] !== [] && ($overwrite || $current === [])) {
                    $update[$field] = $suggested[$field];
                }
            }
            foreach (['budget_band', 'accommodation_style'] as $field) {
                if ($suggested[$field] !== null && ($overwrite || empty($package->{$field}))) {
                    $update[$field] = $suggested[$field];
                }
            }

            if ($update === []) {
                continue;
            }

            $changed++;
            $rows[] = [
                $package->id,
                mb_strimwidth($package->title, 0, 38, '...'),
                implode(',', $update['audiences'] ?? $package->audiences ?? []) ?: '-',
                implode(',', $update['interests'] ?? $package->interests ?? []) ?: '-',
                $update['budget_band'] ?? $package->budget_band ?? '-',
                $update['accommodation_style'] ?? $package->accommodation_style ?? '-',
            ];

            if (! $dry) {
                $package->update($update);
            }
        }

        if ($rows !== []) {
            $this->table(['ID', 'Title', 'Audiences', 'Interests', 'Budget', 'Stay'], $rows);
        }

        $this->info(($dry ? 'Would tag ' : 'Tagged ') . $changed . ' tour(s).');
        $this->line('These are suggestions from price, difficulty, group size and wording. Review them in the admin.');

        return self::SUCCESS;
    }
}
