<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AiCrawlerReport extends Command
{
    protected $signature = 'ai:report {--days=30 : How many days back}';

    protected $description = 'Show which AI crawlers fetched the site and which pages they read most';

    public function handle(): int
    {
        $since = now()->subDays((int) $this->option('days'))->toDateString();

        $byBot = DB::table('ai_crawler_hits')->where('day', '>=', $since)
            ->select('bot', DB::raw('SUM(hits) as hits'), DB::raw('COUNT(DISTINCT path) as pages'))
            ->groupBy('bot')->orderByDesc('hits')->get();

        if ($byBot->isEmpty()) {
            $this->info('No AI crawler visits recorded in this period.');

            return self::SUCCESS;
        }

        $this->table(['Bot', 'Hits', 'Pages'], $byBot->map(fn ($r) => [$r->bot, $r->hits, $r->pages])->all());

        $top = DB::table('ai_crawler_hits')->where('day', '>=', $since)
            ->select('path', DB::raw('SUM(hits) as hits'))->groupBy('path')->orderByDesc('hits')->limit(15)->get();
        $this->table(['Most-read pages', 'Hits'], $top->map(fn ($r) => [$r->path, $r->hits])->all());

        return self::SUCCESS;
    }
}
