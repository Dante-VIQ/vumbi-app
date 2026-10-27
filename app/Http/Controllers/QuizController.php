<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcome;
use App\Models\NewsletterSubscriber;
use App\Models\QuizResponse;
use App\Support\Persona;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * "Find your trip" quiz: four taps, then straight into /discover with the answers applied.
 * Optionally joins the Field Notes list. Works without JavaScript.
 */
class QuizController extends Controller
{
    public function show(Request $request)
    {
        return view('pages.quiz', ['persona' => Persona::current($request)]);
    }

    public function submit(Request $request)
    {
        // Bots fill the hidden field: send them on their way without storing anything.
        if ((string) $request->input('website', '') !== '') {
            return redirect()->route('discover.index');
        }

        $data = $request->validate([
            'persona' => ['required', Rule::in(Persona::keys())],
            'interest' => ['nullable', Rule::in(array_keys(Persona::interests()))],
            'budget' => ['nullable', Rule::in(array_keys(Persona::budgetBands()))],
            'days' => ['nullable', Rule::in(array_keys(Persona::durations()))],
            'email' => ['nullable', 'email', 'max:255'],
            'consent' => ['nullable', 'in:1,on,true'],
        ]);

        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $subscribed = false;

        if ($email !== '' && $request->boolean('consent')) {
            $subscriber = NewsletterSubscriber::firstOrCreate(
                ['email' => $email],
                ['subscribed_at' => now(), 'persona' => $data['persona'], 'source' => 'quiz']
            );
            $subscribed = true;

            if ($subscriber->wasRecentlyCreated) {
                try {
                    Mail::to($subscriber->email)->send(new NewsletterWelcome($subscriber));
                } catch (\Throwable $e) {
                    report($e);
                }
            } elseif (! $subscriber->persona) {
                $subscriber->update(['persona' => $data['persona']]);
            }
        }

        QuizResponse::create([
            'persona' => $data['persona'],
            'interest' => $data['interest'] ?? null,
            'budget' => $data['budget'] ?? null,
            'days' => $data['days'] ?? null,
            'subscribed' => $subscribed,
        ]);

        Cookie::queue(Persona::COOKIE, $data['persona'], 60 * 24 * 365);

        $query = array_filter([
            'persona' => $data['persona'],
            'interest' => $data['interest'] ?? null,
            'budget' => $data['budget'] ?? null,
            'days' => $data['days'] ?? null,
            'quiz' => 1,
        ]);

        return redirect(route('discover.index', $query) . '#trips');
    }
}
