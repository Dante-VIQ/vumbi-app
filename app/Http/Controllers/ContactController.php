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
        'message' => 'required|string',
        'consent' => 'accepted',
    ]);

    // Send email notification to admin
    Mail::to('africa@vumbiventures.com')->send(new ContactFormSubmitted($validated));

    // Optionally store in database

    return redirect()->route('contact')->with('success', 'Thank you! We will get back to you within 24 hours.');
}

}
