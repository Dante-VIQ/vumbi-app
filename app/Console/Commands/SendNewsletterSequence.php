<?php

namespace App\Console\Commands;

use App\Mail\NewsletterSequence;
use App\Models\NewsletterSubscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNewsletterSequence extends Command
{
    protected $signature = 'newsletter:sequence {--dry-run : List who would get an email without sending}';

    protected $description = 'Send welcome-sequence follow-ups: step 2 three days after signup, step 3 seven days after';

    /** step to send => days since signup it becomes due */
    private const DUE = [2 => 3, 3 => 7];

    public function handle(): int
    {
        $sent = 0;

        foreach (self::DUE as $step => $days) {
            NewsletterSubscriber::query()
                ->where('sequence_step', $step - 1)
                ->where('subscribed_at', '<=', now()->subDays($days))
                ->orderBy('id')
                ->chunkById(100, function ($subscribers) use ($step, &$sent) {
                    foreach ($subscribers as $subscriber) {
                        if ($this->option('dry-run')) {
                            $this->line("Would send step {$step} to {$subscriber->email}");
                            $sent++;

                            continue;
                        }

                        try {
                            Mail::to($subscriber->email)->send(new NewsletterSequence($subscriber, $step));
                            $subscriber->forceFill(['sequence_step' => $step, 'last_sequence_at' => now()])->save();
                            $sent++;
                        } catch (\Throwable $e) {
                            // Leave the step unchanged so the next run retries this person.
                            report($e);
                        }
                    }
                });
        }

        $this->info(($this->option('dry-run') ? 'Would send ' : 'Sent ') . "{$sent} email(s).");

        return self::SUCCESS;
    }
}
