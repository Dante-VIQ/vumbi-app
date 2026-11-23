<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $subscribers = NewsletterSubscriber::query()
            ->when($request->filled('q'), fn ($q) => $q->where('email', 'like', '%' . $request->query('q') . '%'))
            ->when($request->filled('persona'), fn ($q) => $q->where('persona', $request->query('persona')))
            ->latest('subscribed_at')->latest('id')->paginate(30)->withQueryString();

        return view('admin.subscribers.index', [
            'subscribers' => $subscribers,
            'total' => NewsletterSubscriber::count(),
            'last30' => NewsletterSubscriber::where('subscribed_at', '>=', now()->subDays(30))->count(),
            'byPersona' => NewsletterSubscriber::selectRaw('persona, count(*) as total')->groupBy('persona')->pluck('total', 'persona'),
            'bySource' => NewsletterSubscriber::selectRaw('source, count(*) as total')->groupBy('source')->orderByDesc('total')->limit(6)->pluck('total', 'source'),
        ]);
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['email', 'persona', 'source', 'subscribed_at']);
            NewsletterSubscriber::orderBy('id')->chunkById(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    // Same guard as newsletter:export: never write something a spreadsheet would run as a formula.
                    if (preg_match('/^[=+\-@\t\r]/', $r->email)) {
                        continue;
                    }
                    fputcsv($out, [$r->email, $r->persona, $r->source, optional($r->subscribed_at)->toDateTimeString()]);
                }
            });
            fclose($out);
        }, 'subscribers-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
