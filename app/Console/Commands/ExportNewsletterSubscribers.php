<?php

namespace App\Console\Commands;

use App\Models\NewsletterSubscriber;
use Illuminate\Console\Command;

class ExportNewsletterSubscribers extends Command
{
    protected $signature = 'newsletter:export {--path= : Where to write the CSV (default: storage/app/newsletter-subscribers.csv)}';

    protected $description = 'Export newsletter subscribers to a CSV for import into an email platform';

    public function handle(): int
    {
        $path = $this->option('path') ?: storage_path('app/newsletter-subscribers.csv');

        $handle = fopen($path, 'w');
        if ($handle === false) {
            $this->error("Could not open {$path} for writing.");

            return self::FAILURE;
        }

        fputcsv($handle, ['email', 'subscribed_at']);

        $written = 0;
        $skipped = 0;

        NewsletterSubscriber::orderBy('id')->chunkById(500, function ($subscribers) use ($handle, &$written, &$skipped) {
            foreach ($subscribers as $subscriber) {
                // Leading = + - @ can run as a formula if the file is opened in a spreadsheet.
                if (preg_match('/^[=+\-@]/', $subscriber->email)) {
                    $skipped++;

                    continue;
                }

                fputcsv($handle, [
                    $subscriber->email,
                    optional($subscriber->subscribed_at ?? $subscriber->created_at)->toIso8601String(),
                ]);
                $written++;
            }
        });

        fclose($handle);

        $this->info("Exported {$written} subscribers to {$path}.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} address(es) starting with = + - or @. Review them by hand.");
        }

        return self::SUCCESS;
    }
}
