@props([
    'title' => null,
    'placement' => 'blog',
    'blogId' => null,
])

@php
    $number = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));
    $message = $title
        ? 'Hi Vumbi Ventures, I just read "' . $title . '" and would like help planning a trip.'
        : 'Hi Vumbi Ventures, I would like help planning a trip.';
@endphp

@if ($number)
    <div {{ $attributes->class('bg-white rounded-2xl border border-black/5 p-5 shadow-sm') }}>
        <h3 class="font-semibold text-lg text-zinc-900 mb-1">Planning a trip like this?</h3>
        <p class="text-sm text-zinc-600 mb-4 leading-relaxed">
            Tell us where and when. A real person replies on WhatsApp, usually within a day.
        </p>
        <a href="https://wa.me/{{ $number }}?text={{ rawurlencode($message) }}"
            target="_blank"
            rel="noopener"
            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-xl font-semibold transition flex justify-center items-center gap-2 text-sm"
            data-gtag-event="whatsapp_click"
            data-gtag-params="{{ json_encode(['placement' => $placement, 'blog_id' => $blogId]) }}">
            Chat with us on WhatsApp
        </a>
    </div>
@endif
