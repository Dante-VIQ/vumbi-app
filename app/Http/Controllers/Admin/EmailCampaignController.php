<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendCampaignEmail;
use App\Mail\CampaignEmail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignSend;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailCampaignController extends Controller
{
    public function index()
    {
        return view('admin.campaigns.index', [
            'campaigns' => EmailCampaign::latest()->paginate(20),
            'templates' => collect(config('email_templates'))->except('blank'),
            'subscriberCount' => NewsletterSubscriber::count(),
            'sequence' => NewsletterSubscriber::selectRaw('sequence_step, count(*) as total')
                ->groupBy('sequence_step')->pluck('total', 'sequence_step'),
        ]);
    }

    /** ?template=key starts from a built-in template; ?from=id duplicates an earlier campaign. */
    public function create(Request $request)
    {
        $campaign = new EmailCampaign(['audience' => 'all'] + config('email_templates.blank'));

        if ($request->filled('from')) {
            $source = EmailCampaign::findOrFail($request->integer('from'));
            $campaign->fill($source->only(['subject', 'preheader', 'body', 'cta_label', 'cta_url', 'audience']));
            $campaign->name = $source->name . ' (copy)';
        } elseif ($request->filled('template') && config()->has('email_templates.' . $request->query('template'))) {
            $campaign->fill(config('email_templates.' . $request->query('template')));
        }

        return view('admin.campaigns.form', ['campaign' => $campaign, 'audiences' => EmailCampaign::audiences()]);
    }

    public function store(Request $request)
    {
        $campaign = EmailCampaign::create($this->validated($request) + [
            'status' => EmailCampaign::DRAFT,
            'created_by' => $request->user()?->id,
        ]);

        return redirect()->route('campaigns.show', $campaign)->with('success', 'Draft saved. Send yourself a test before sending to the list.');
    }

    public function edit(EmailCampaign $campaign)
    {
        abort_unless($campaign->isDraft(), 403, 'Only drafts can be edited. Duplicate it to reuse the content.');

        return view('admin.campaigns.form', ['campaign' => $campaign, 'audiences' => EmailCampaign::audiences()]);
    }

    public function update(Request $request, EmailCampaign $campaign)
    {
        abort_unless($campaign->isDraft(), 403, 'Only drafts can be edited.');
        $campaign->update($this->validated($request));

        return redirect()->route('campaigns.show', $campaign)->with('success', 'Draft updated.');
    }

    public function show(EmailCampaign $campaign)
    {
        $counts = $campaign->sends()->selectRaw(
            'sum(case when sent_at is not null then 1 else 0 end) as sent, '
            . 'sum(case when failed_at is not null then 1 else 0 end) as failed, '
            . 'count(*) as total'
        )->first();

        return view('admin.campaigns.show', [
            'campaign' => $campaign,
            'audienceCount' => $campaign->audienceQuery()->count(),
            'sent' => (int) $counts->sent,
            'failed' => (int) $counts->failed,
            'total' => (int) $counts->total,
            'failures' => $campaign->sends()->whereNotNull('failed_at')->limit(10)->get(),
        ]);
    }

    /** The email exactly as subscribers will see it, for the preview iframe. */
    public function preview(EmailCampaign $campaign)
    {
        return response((new CampaignEmail($campaign, null, true))->render());
    }

    public function test(Request $request, EmailCampaign $campaign)
    {
        $data = $request->validate(['email' => 'required|email|max:255']);
        Mail::to($data['email'])->send(new CampaignEmail($campaign, null, true));

        return back()->with('success', "Test email sent to {$data['email']}.");
    }

    public function send(Request $request, EmailCampaign $campaign)
    {
        $request->validate(['confirm' => 'accepted']);

        // Claim the draft atomically so a double click cannot send twice.
        $claimed = EmailCampaign::whereKey($campaign->id)->where('status', EmailCampaign::DRAFT)
            ->update(['status' => EmailCampaign::SENDING, 'started_at' => now()]);
        if (! $claimed) {
            return back()->with('error', 'This campaign has already been sent.');
        }

        $campaign->refresh();
        $count = 0;

        $campaign->audienceQuery()->orderBy('id')->chunkById(200, function ($subscribers) use ($campaign, &$count) {
            foreach ($subscribers as $subscriber) {
                $send = EmailCampaignSend::firstOrCreate(
                    ['email_campaign_id' => $campaign->id, 'newsletter_subscriber_id' => $subscriber->id],
                    ['email' => $subscriber->email]
                );
                SendCampaignEmail::dispatch($send->id);
                $count++;
            }
        });

        $campaign->forceFill(['recipients_count' => $count])->save();

        if ($count === 0) {
            $campaign->forceFill(['status' => EmailCampaign::SENT, 'completed_at' => now()])->save();

            return back()->with('error', 'Nobody is in that audience yet, so nothing was sent.');
        }

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', "Queued for {$count} subscriber(s). A queue worker must be running for them to go out.");
    }

    public function destroy(EmailCampaign $campaign)
    {
        abort_unless($campaign->isDraft(), 403, 'Sent campaigns are kept as a record.');
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Draft deleted.');
    }

    private function validated(Request $request): array
    {
        $data = Validator::make($request->all(), [
            'name' => 'required|string|max:120',
            'subject' => 'required|string|max:150',
            'preheader' => 'nullable|string|max:150',
            'body' => 'required|string|max:20000',
            'cta_label' => 'nullable|string|max:60|required_with:cta_url',
            'cta_url' => ['nullable', 'string', 'max:500', 'required_with:cta_label', function ($attr, $value, $fail) {
                if ($value && ! preg_match('#^(/|https?://)#i', $value)) {
                    $fail('The button link must start with / (a page on this site) or https://.');
                }
            }],
            'audience' => ['required', 'in:' . implode(',', array_keys(EmailCampaign::audiences()))],
        ])->validate();

        return $data;
    }
}
