<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function submit(Request $request)
{
 $key = 'contact-form:' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 3)) {
        return response()->json([
            'message' => 'Too many submissions. Please try again later.'
        ], 429);
    }
  RateLimiter::hit($key, 600); // 600 seconds = 10 minutes
  
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string',
        'inquiry_type' => 'required|string',
        'subject' => 'nullable|string',
        'website' => 'nullable|url',
        'travel_details' => 'nullable|string|max:255',
        'travel_month' => ['nullable', 'date_format:Y-m'],
        'group_size' => 'nullable|integer|min:1|max:200',
        'budget_range' => ['nullable', \Illuminate\Validation\Rule::in(['budget', 'mid', 'premium'])],
        'persona' => ['nullable', \Illuminate\Validation\Rule::in(\App\Support\Persona::keys())],
        'message' => 'required|string',
        'consent' => 'accepted',
    ]);

    // Send email notification to admin
    // Studio enquiries (web_dev / seo) go to the Studio inbox; everything else to the travel inbox.
    $to = in_array($validated['inquiry_type'], ['web_dev', 'seo'], true)
        ? config('mail.studio_email', 'studio@vumbiventures.com')
        : 'africa@vumbiventures.com';
    Mail::to($to)->send(new ContactFormSubmitted($validated));

    // Optionally store in database

    // Return to the page the form was sent from (travel contact or Studio contact).
    return redirect()->back()->with('success', 'Thank you! We will get back to you within 24 hours.');
}

}
