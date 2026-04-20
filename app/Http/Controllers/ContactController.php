<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    // routes/web.php


// app/Http/Controllers/ContactController.php
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

    return redirect()->route('contact')->with('success', 'Thank you! We'll get back to you within 24 hours.');
}
}
