<?php

namespace App\Http\Controllers;

use App\Mail\SafariQuoteReceived;
use App\Models\SafariQuote;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    /**
     * Handle incoming Safari Quote requests.
     */
    public function sendQuote(Request $request)
    {
        // 1. Strict Request Validation
        $validated = $request->validate([
            'destination'  => ['nullable', 'string', 'max:255'],
            'name'         => ['required', 'string', 'max:150'],
            'email'        => ['required', 'email:rfc,dns', 'max:255'],
            'phone'        => ['required', 'string', 'max:30'],
            'travel_month' => ['required', 'date_format:Y-m'],
            'travelers'    => ['required', 'integer', 'min:1', 'max:100'],
            'notes'        => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            // 2. Optional: Persist to Database for CRM/Admin Tracking
            $quote = SafariQuote::create([
                'destination'  => $validated['destination'] ?? 'Unspecified',
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'phone'        => $validated['phone'],
                'travel_month' => $validated['travel_month'],
                'travelers'    => $validated['travelers'],
                'notes'        => $validated['notes'] ?? null,
                'status'       => 'pending',
            ]);

            // 3. Dispatch Email Notification to Operations Team
            Mail::to(config('mail.from.address', 'info@vumbiventures.com'))
                ->send(new SafariQuoteReceived($quote));

            return redirect()->back()->with('success', 'Your safari quote request has been sent! Our team will contact you shortly.');

        } catch (\Exception $e) {
            Log::error('Safari Quote Submission Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to process your request right now. Please try contacting us directly via WhatsApp.');
        }
    }
}