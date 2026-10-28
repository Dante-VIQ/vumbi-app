@extends('layouts.app')

@php
    $waNumber = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));
    $waText = \App\Support\Persona::whatsappMessage($persona, $style['name']);
    $pageSchemas = [[
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($style['faq'])->map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ])->all(),
    ]];
@endphp

@section('title', $style['title'])
@section('description', $style['description'])

@section('content')
<section class="relative overflow-hidden pt-24 pb-12 md:pt-32 md:pb-16">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(139,90,43,0.06),transparent_50%)]"></div>
    <div class="relative container mx-auto px-6 text-center max-w-4xl">
        <span class="inline-flex items-center gap-2 text-sm bg-white/70 border border-black/5 px-4 py-2 rounded-full shadow-sm">
            <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>{{ $style['badge'] }}
        </span>
        <h1 class="text-4xl md:text-5xl font-semibold mt-6 leading-tight tracking-tight">{{ $style['h1'] }}</h1>
        <p class="mt-4 text-[#5C5C5C] text-lg max-w-2xl mx-auto">{{ $style['intro'] }}</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="#trips" class="px-6 py-3 rounded-xl bg-[#8B5A2B] hover:bg-[#5C3A1E] text-white font-semibold transition">See matching trips</a>
            <a href="#enquire" class="px-6 py-3 rounded-xl bg-white border border-black/10 hover:border-[#8B5A2B] font-semibold transition">{{ $style['cta'] }}</a>
            @if ($waNumber)
                <a href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode($waText) }}" target="_blank" rel="noopener"
                    class="px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold transition"
                    data-gtag-event="whatsapp_click" data-gtag-params="{{ json_encode(['placement' => 'trip_page_' . $slug]) }}">WhatsApp us</a>
            @endif
        </div>
    </div>
</section>

<section class="container mx-auto px-6 py-8 max-w-5xl">
    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-black/5 p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-3">Who it's for</h2>
            <ul class="space-y-2 text-[#5C5C5C]">
                @foreach ($style['who'] as $item)<li class="flex gap-2"><span class="text-[#8B5A2B]">&bull;</span><span>{{ $item }}</span></li>@endforeach
            </ul>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-3">Good to know</h2>
            <ul class="space-y-2 text-[#5C5C5C]">
                @foreach ($style['good_to_know'] as $item)<li class="flex gap-2"><span class="text-[#8B5A2B]">&bull;</span><span>{{ $item }}</span></li>@endforeach
            </ul>
        </div>
    </div>
</section>

<section id="trips" class="container mx-auto px-6 py-12 max-w-5xl scroll-mt-20">
    <h2 class="text-2xl md:text-3xl font-semibold">{{ $isFallback ? 'Popular trips right now' : 'Trips that suit ' . strtolower($style['name']) }}</h2>
    @if ($isFallback)
        <p class="text-sm text-[#5C5C5C] mt-2">We're still tagging trips for this style, so these are our newest. Tell us what you want and we'll point you to the right one.</p>
    @elseif ($priceRange)
        <p class="text-sm text-[#5C5C5C] mt-2">
            {{ $priceRange['count'] }} {{ \Illuminate\Support\Str::plural('trip', $priceRange['count']) }},
            from {{ \App\Support\Money::usd($priceRange['min']) }}@if (\App\Support\Money::usd($priceRange['max']) !== \App\Support\Money::usd($priceRange['min'])) to {{ \App\Support\Money::usd($priceRange['max']) }}@endif per person.
        </p>
    @endif

    @if ($tours->isEmpty())
        <p class="mt-6 text-[#5C5C5C]">No trips are listed yet. Send us an enquiry below and we'll build one for you.</p>
    @else
        <div class="mt-6 grid md:grid-cols-3 gap-6">
            @foreach ($tours as $tour)
                @php $img = $tour->featured_image ? asset('storage/' . $tour->featured_image) : ($tour->image ?: asset('images/og-default.jpg')); @endphp
                <a href="{{ route('tours.show', $tour->slug) }}" class="group bg-white rounded-2xl border border-black/5 shadow-sm overflow-hidden hover:shadow-md transition flex flex-col"
                    data-gtag-event="trip_card_click" data-gtag-params="{{ json_encode(['placement' => 'trip_page_' . $slug, 'tour_id' => $tour->id]) }}">
                    <div class="h-44 bg-cover bg-center" style="background-image: url('{{ $img }}')"></div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-semibold text-lg group-hover:text-[#8B5A2B] transition">{{ $tour->title }}</h3>
                        <p class="text-xs text-[#5C5C5C] mt-1">
                            {{ $tour->location }}@if ($tour->duration_days) &middot; {{ $tour->duration_days }} {{ \Illuminate\Support\Str::plural('day', $tour->duration_days) }}@endif
                        </p>
                        @if (is_array($tour->included) && count($tour->included))
                            <ul class="mt-3 text-sm text-[#5C5C5C] space-y-1">
                                @foreach (array_slice($tour->included, 0, 3) as $inc)<li>&#10003; {{ is_string($inc) ? $inc : '' }}</li>@endforeach
                            </ul>
                        @endif
                        <p class="mt-auto pt-4 font-semibold text-[#8B5A2B]">From {{ \App\Support\Money::usd($tour->price) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
    <p class="mt-6"><a href="{{ route('tours.index', ['persona' => $persona]) }}" class="text-[#8B5A2B] font-medium hover:underline">Browse all trips &rarr;</a></p>
</section>

<section class="container mx-auto px-6 py-8 max-w-5xl">
    <div class="bg-white rounded-2xl border border-black/5 p-6 md:p-8 shadow-sm">
        <h2 class="text-xl font-semibold mb-3">How we vet partners</h2>
        <ul class="grid md:grid-cols-2 gap-3 text-[#5C5C5C]">
            <li>&#10003; Licensed local transport partners</li>
            <li>&#10003; Inspected 4x4s and vans</li>
            <li>&#10003; Certified driver-guides</li>
            <li>&#10003; Upfront prices, no surprises</li>
        </ul>
    </div>
</section>

<section id="enquire" class="container mx-auto px-6 py-12 max-w-3xl scroll-mt-20">
    <h2 class="text-2xl md:text-3xl font-semibold text-center">{{ $style['cta'] }}</h2>
    <p class="text-center text-[#5C5C5C] mt-2 mb-8">Tell us a little and we'll reply within 24 business hours.</p>
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
    @endif
    <div class="bg-white rounded-3xl border border-black/5 shadow-lg p-6 md:p-8">
        <x-travel-enquiry-form :persona="$persona" :placement="'trip_' . $slug" />
    </div>
</section>

<section class="container mx-auto px-6 py-8 max-w-3xl">
    <h2 class="text-2xl font-semibold mb-4">Frequently asked questions</h2>
    <div class="space-y-3">
        @foreach ($style['faq'] as $f)
            <details class="bg-white rounded-xl border border-black/5 p-4 group">
                <summary class="font-medium cursor-pointer">{{ $f['q'] }}</summary>
                <p class="mt-2 text-[#5C5C5C]">{{ $f['a'] }}</p>
            </details>
        @endforeach
    </div>
</section>

@if ($posts->isNotEmpty())
    <section class="container mx-auto px-6 py-8 max-w-5xl">
        <h2 class="text-2xl font-semibold mb-4">From our Field Notes</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach ($posts as $post)
                <a href="{{ $post->url }}" class="bg-white rounded-xl border border-black/5 p-4 hover:border-[#8B5A2B] transition">
                    <p class="font-medium">{{ $post->title }}</p>
                </a>
            @endforeach
        </div>
    </section>
@endif

<section class="container mx-auto px-6 pt-8 pb-20 max-w-5xl">
    <p class="text-sm text-[#5C5C5C] mb-3">Travelling differently?</p>
    <div class="flex flex-wrap gap-3">
        @foreach ($others as $otherSlug => $other)
            <a href="{{ route('trips.show', $otherSlug) }}" class="px-4 py-2 rounded-full bg-white border border-black/10 hover:border-[#8B5A2B] text-sm transition">{{ $other['name'] }}</a>
        @endforeach
    </div>
</section>
@endsection
