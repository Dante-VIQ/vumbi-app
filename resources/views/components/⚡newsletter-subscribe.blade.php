<?php

use Livewire\Component;

new class extends Component
{
     public $email;
    public $message;
    public $messageType;

    protected $rules = [
        'email' => 'required|email|unique:newsletter_subscribers,email',
    ];

    public function subscribe()
    {
        $this->validate();

        NewsletterSubscriber::create([
            'email' => $this->email,
            'subscribed_at' => now(),
        ]);

        $this->message = 'Thank you for subscribing! You\'ll receive our next Field Notes edition.';
        $this->messageType = 'success';
        $this->email = '';

        // You could also trigger an event to send a welcome email
        // event(new NewsletterSubscribed($this->email));
    }
};
?>

<div>
    @if($message)
        <div class="p-3 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} mb-3">
            {{ $message }}
        </div>
    @endif

    <form wire:submit.prevent="subscribe" class="flex">
        <input
            type="email"
            wire:model="email"
            placeholder="Your email"
            class="flex-1 px-3 py-2 rounded-l-lg bg-[#2A2A2A] border-0 focus:outline-none text-white placeholder-[#6B6B6B]"
            required
        >
        <button
            type="submit"
            class="bg-[#8B5A2B] px-4 py-2 rounded-r-lg hover:bg-[#6B421F] transition disabled:opacity-50"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>→</span>
            <span wire:loading class="inline-block animate-spin">⌛</span>
        </button>
    </form>
    @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
</div>
