<?php

namespace App\Console\Commands;

use App\Support\Persona;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Day-30/60 review: counts of what visitors did, split by persona. Page views and
 * conversion rates live in Google Analytics; this covers what the database knows.
 */
class GrowthReview extends Command
{
    protected $signature = 'growth:review {--days=60 : How many days back}';

    protected $description = 'Summarise quiz answers, signups, enquiries and clicks by persona';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $since = now()->subDays($days);
        $this->info("Growth review: last {$days} days (since {$since->toDateString()})");

        $personas = array_merge(Persona::keys(), [null]);
        $count = function (string $table, ?string $persona, string $dateColumn = 'created_at') use ($since) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'persona')) {
                return '-';
            }
            $q = DB::table($table)->where($dateColumn, '>=', $since);
            $persona === null ? $q->whereNull('persona') : $q->where('persona', $persona);

            return $q->count();
        };

        $rows = [];
        foreach ($personas as $persona) {
            $rows[] = [
                $persona ? Persona::label($persona) : 'No persona',
                $count('quiz_responses', $persona),
                $count('newsletter_subscribers', $persona, 'subscribed_at'),
                $count('leads', $persona),
                $count('safari_quotes', $persona),
                $count('tour_clicks', $persona, 'clicked_at'),
            ];
        }
        $this->table(['Persona', 'Quiz finished', 'Newsletter signups', 'Booking leads', 'Quote requests', 'Affiliate clicks'], $rows);

        if (Schema::hasTable('newsletter_subscribers') && Schema::hasColumn('newsletter_subscribers', 'source')) {
            $bySource = DB::table('newsletter_subscribers')->where('subscribed_at', '>=', $since)
                ->select(DB::raw("COALESCE(source, 'unknown') as source"), DB::raw('COUNT(*) as signups'))
                ->groupBy('source')->orderByDesc('signups')->get();
            if ($bySource->isNotEmpty()) {
                $this->table(['Signup source', 'Signups'], $bySource->map(fn ($r) => [$r->source, $r->signups])->all());
            }

            $steps = DB::table('newsletter_subscribers')->where('subscribed_at', '>=', $since)
                ->select('sequence_step', DB::raw('COUNT(*) as people'))->groupBy('sequence_step')->orderBy('sequence_step')->get();
            if ($steps->isNotEmpty()) {
                $this->table(['Follow-up reached (1 = welcome only)', 'People'], $steps->map(fn ($r) => [$r->sequence_step, $r->people])->all());
            }
        }

        if (Schema::hasTable('quiz_responses')) {
            $quiz = DB::table('quiz_responses')->where('created_at', '>=', $since);
            $total = (clone $quiz)->count();
            if ($total > 0) {
                $joined = (clone $quiz)->where('subscribed', true)->count();
                $this->line(sprintf('Quiz: %d finished, %d (%.0f%%) joined the email list.', $total, $joined, $joined / $total * 100));
            }
        }

        $this->comment('Visits, conversion rates and experiment results are in Google Analytics. See docs/growth-testing.md.');

        return self::SUCCESS;
    }
}
