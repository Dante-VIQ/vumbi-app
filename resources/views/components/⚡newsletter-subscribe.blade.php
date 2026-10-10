<?php

use Livewire\Component;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\NewsletterWelcome;

new class extends Component {
    public $email;
    public $message;
    public $messageType;

    // 'dark' (original footer/hero styling) or 'light' (for cards on a light background)
    public string $variant = 'dark';

    // Which spot on the page this instance lives in — purely for analytics,
    // e.g. 'footer', 'sidebar', 'inline', 'endpost'. Doesn't affect rendering.
    public string $placement = 'unknown';

    // Honeypot: hidden from people, filled in by bots.
    public string $website = '';

    public function subscribe()
    {
        $this->email = strtolower(trim((string) $this->email));

        $this->validate([
            'email' => 'required|email|max:255',
        ]);

        $successMessage = 'Thank you for subscribing! You\'ll receive our next Field Notes edition.';

        // Bots fill the hidden field: pretend it worked, store nothing.
        if ($this->website !== '') {
            $this->message = $successMessage;
            $this->messageType = 'success';
            $this->email = '';
            return;
        }

        $throttleKey = 'newsletter-subscribe:' . request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->message = 'Too many attempts. Please try again in a few minutes.';
            $this->messageType = 'error';
            return;
        }
        RateLimiter::hit($throttleKey, 600);

        // An address that is already on the list gets the same friendly
        // answer, so the form never reveals who is subscribed.
        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $this->email],
            ['subscribed_at' => now()]
        );

        $this->message = $successMessage;
        $this->messageType = 'success';
        $this->email = '';

        if ($subscriber->wasRecentlyCreated) {
            $this->dispatch('newsletter-subscribed', placement: $this->placement);

            // A mail problem must never cost us the signup: log it and carry on.
            try {
                Mail::to($subscriber->email)->send(new NewsletterWelcome($subscriber));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        // You could also trigger an event to send a welcome email
        // event(new NewsletterSubscribed($this->email));
    }
};
?>

<div>
    @php
        $isLight = $variant === 'light';
        $inputClasses = $isLight
            ? 'flex-1 px-3 py-2 rounded-l-lg bg-white border border-black/10 focus:outline-none focus:border-[#8B5A2B] text-[#1A1A1A] placeholder-[#9A9A9A]'
            : 'flex-1 px-3 py-2 rounded-l-lg bg-[#2A2A2A] border-0 focus:outline-none text-white placeholder-[#6B6B6B]';
        $errorClasses = $isLight ? 'text-rose-500 text-xs mt-1 block' : 'text-red-400 text-xs mt-1 block';
        $noteClasses = $isLight ? 'text-[#5C5C5C]' : 'text-[#C7B5A6]';
    @endphp

    @if($message)
        <div
            class="p-3 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} mb-3">
            {{ $message }}
        </div>
    @endif

    <form wire:submit.prevent="subscribe" class="flex">
        <input type="email" wire:model="email" placeholder="Your email" aria-label="Email address"
            class="{{ $inputClasses }}"
            required>
        <div class="hidden" aria-hidden="true">
            <input type="text" wire:model="website" name="website" tabindex="-1" autocomplete="off">
        </div>
        <button type="submit"
            class="bg-[#8B5A2B] px-4 py-2 rounded-r-lg hover:bg-[#6B421F] transition disabled:opacity-50"
            wire:loading.attr="disabled">
            <span wire:loading.remove>→</span>
            <span wire:loading class="inline-block animate-spin">⌛</span>
        </button>
    </form>
    @error('email') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
    <p class="text-xs mt-3 {{ $noteClasses }}">We respect your privacy. Unsubscribe at any time. <a href="{{ route('privacy') }}" class="underline hover:opacity-80">Privacy policy</a></p>
</div>