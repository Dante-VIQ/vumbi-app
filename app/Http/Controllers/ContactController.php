<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    public function submit(Request $request)
{
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
    Mail::to('hello@vumbiventures.com')->send(new ContactFormSubmitted($validated));

    // Optionally store in database

    return redirect()->route('contact')->with('success', 'Thank you! We will get back to you within 24 hours.');
}

}
