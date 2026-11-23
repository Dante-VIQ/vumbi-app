<?php

namespace App\Jobs;

use App\Mail\CampaignEmail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignSend;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Sends one campaign email to one subscriber. The send row is the idempotency key:
 * a retry or a double dispatch never mails the same person twice.
 */
class SendCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $sendId) {}

    public function backoff(): array
    {
        return [60, 300];
    }

    public function handle(): void
    {
        $send = EmailCampaignSend::with('campaign')->find($this->sendId);
        if (! $send || $send->sent_at) {
            return;
        }

        $subscriber = NewsletterSubscriber::find($send->newsletter_subscriber_id);
        if (! $subscriber) { // unsubscribed since the send was queued
            $send->forceFill(['failed_at' => now(), 'error' => 'Unsubscribed before sending'])->save();
            $this->finalise($send->campaign);

            return;
        }

        try {
            Mail::to($subscriber->email)->send(new CampaignEmail($send->campaign, $subscriber));
        } catch (\Throwable $e) {
            $send->forceFill(['error' => mb_substr($e->getMessage(), 0, 500)])->save();
            throw $e; // let the queue retry
        }

        $send->forceFill(['sent_at' => now(), 'failed_at' => null, 'error' => null])->save();
        $this->finalise($send->campaign);
    }

    public function failed(\Throwable $e): void
    {
        $send = EmailCampaignSend::with('campaign')->find($this->sendId);
        if ($send && ! $send->sent_at) {
            $send->forceFill(['failed_at' => now(), 'error' => mb_substr($e->getMessage(), 0, 500)])->save();
            $this->finalise($send->campaign);
        }
    }

    private function finalise(EmailCampaign $campaign): void
    {
        $pending = $campaign->sends()->whereNull('sent_at')->whereNull('failed_at')->exists();
        if (! $pending && $campaign->status === EmailCampaign::SENDING) {
            $campaign->forceFill(['status' => EmailCampaign::SENT, 'completed_at' => now()])->save();
        }
    }
}
