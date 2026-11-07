<?php

namespace App\Http\Controllers;

use App\Mail\SafariQuoteClientConfirmation;
use App\Mail\SafariQuoteReceived;
use App\Models\SafariQuote;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class InquiryController extends Controller
{
    public function sendQuote(Request $request)
    {
        // 0. Honeypot: bots fill hidden fields, humans don't.
        if ($request->filled('website')) {
            return redirect()->route('home')->with('success', 'Thank you!');
        }

        // 1. Validate.
        $validated = $request->validate([
            'destination'  => ['nullable', 'string', 'max:255'],
            'name'         => ['required', 'string', 'max:150'],
            'email'        => ['required', 'email:rfc', 'max:255'],
            'phone'        => ['required', 'string', 'max:30', 'regex:/^[0-9+\-() ]{7,30}$/'],
            'travel_month' => ['required', 'date_format:Y-m'],
            'travelers'    => ['required', 'integer', 'min:1', 'max:100'],
            'notes'        => ['nullable', 'string', 'max:2000'],
        ], [
            'phone.regex' => 'Please enter a valid phone number (digits, +, -, spaces, parentheses).',
        ]);

        // 2. Prevent accidental double-submits from the same IP.
        $lock = Cache::lock('inquiry:' . $request->ip(), 10);
        if (! $lock->get()) {
            return redirect()->route('home')
                ->with('error', 'Please wait a moment before submitting again.');
        }

        // 3. Save first. If this fails, it's a real user-facing error.
        try {
            $quote = SafariQuote::create([
                'destination'  => $validated['destination'] ?? 'Unspecified',
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'phone'        => $validated['phone'],
                'travel_month' => $validated['travel_month'],
                'travelers'    => $validated['travelers'],
                'notes'        => $validated['notes'] ?? null,
                'status'       => 'pending',
                'persona'      => \App\Support\Persona::current($request),
                'ip_address'   => $request->ip(),
                'user_agent'   => substr((string) $request->userAgent(), 0, 512),
            ]);
        } catch (Throwable $e) {
            Log::error('Safari quote save failed', [
                'error'      => $e->getMessage(),
                'input_keys' => array_keys($request->except(['_token', '_method'])),
                'email_hash' => hash('sha256', (string) $request->input('email')),
            ]);

            $lock->release();

            return redirect()->route('home')
                ->withInput()
                ->with('error', 'Unable to save your request right now. Please try again or WhatsApp us on +254 734 591 543.');
        }

        // 4. Admin notification — failure here must NOT fail the user.
        $adminAddress = config('mail.admin_address');

        if (blank($adminAddress)) {
            Log::critical('No admin email configured; SafariQuote admin notification skipped.', [
                'quote_id' => $quote->id,
            ]);
        } else {
            try {
                Mail::to($adminAddress)->send(new SafariQuoteReceived($quote));
            } catch (Throwable $e) {
                Log::error('Safari quote admin mail failed', [
                    'quote_id' => $quote->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        // 5. Client confirmation — failure here must NOT fail the user either.
        try {
            Mail::to($quote->email)->send(new SafariQuoteClientConfirmation($quote));
        } catch (Throwable $e) {
            Log::error('Safari quote client mail failed', [
                'quote_id' => $quote->id,
                'email'    => $quote->email,
                'error'    => $e->getMessage(),
            ]);
        }

        $lock->release();

        return redirect()
            ->route('inquiry.thank-you', ['quote' => $quote->id])
            ->with('success', 'Your safari quote request has been sent!');
    }

    public function thankYou(SafariQuote $quote)
    {
        return view('inquiry.thank-you', compact('quote'));
    }
}