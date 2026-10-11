<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;

class NewsletterUnsubscribeController extends Controller
{
    // Both routes are signed. GET only asks for confirmation, because mail
    // scanners and link previews open links and must not unsubscribe anyone.
    public function show(int $id)
    {
        return view('pages.newsletter-unsubscribe', [
            'state' => NewsletterSubscriber::whereKey($id)->exists() ? 'confirm' : 'done',
        ]);
    }

    public function destroy(int $id)
    {
        NewsletterSubscriber::whereKey($id)->delete();

        return view('pages.newsletter-unsubscribe', ['state' => 'done']);
    }
}
