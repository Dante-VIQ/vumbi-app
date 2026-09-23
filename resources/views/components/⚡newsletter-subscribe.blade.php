<?php

use Livewire\Component;
use App\Models\NewsletterSubscriber;

new class extends Component {
    public $email;
    public $message;
    public $messageType;

    // 'dark' (original footer/hero styling) or 'light' (for cards on a light background)
    public string $variant = 'dark';

    // Which spot on the page this instance lives in — purely for analytics,
    // e.g. 'footer', 'sidebar', 'inline', 'endpost'. Doesn't affect rendering.
    public string $placement = 'unknown';

    protected $rules = [
        'email' => 'required|email|unique:newsletter_subscribers,email',
    ];

    public function subscribe()
    {
        $this->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        $email = $this->email;

        NewsletterSubscriber::create([
            'email' => $email,
            'subscribed_at' => now(),
        ]);

        $this->message = 'Thank you for subscribing! You\'ll receive our next Field Notes edition.';
        $this->messageType = 'success';
        $this->email = '';

        $this->dispatch('newsletter-subscribed', placement: $this->placement);

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
    @endphp

    @if($message)
        <div
            class="p-3 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} mb-3">
            {{ $message }}
        </div>
    @endif

    <form wire:submit.prevent="subscribe" class="flex">
        <input type="email" wire:model="email" placeholder="Your email"
            class="{{ $inputClasses }}"
            required>
        <button type="submit"
            class="bg-[#8B5A2B] px-4 py-2 rounded-r-lg hover:bg-[#6B421F] transition disabled:opacity-50"
            wire:loading.attr="disabled">
            <span wire:loading.remove>→</span>
            <span wire:loading class="inline-block animate-spin">⌛</span>
        </button>
    </form>
    @error('email') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
</div>