@extends('layouts.app')

 
@section('title', 'Trips & Travel Planning in Kenya & East Africa | Vumbi Ventures')
@section('description', 'From private safaris in Kenya to cultural tours in Tanzania. We craft custom, authentic African travel experiences. Itineraries built for you.')
@section('keywords', 'African travel services, custom safari planning, Kenya tour operator, Tanzania travel agency, adventure travel Africa, group travel services, bespoke itineraries, Vumbi Ventures services')

@section('content')


@push('styles')
<style>
    :root {
        --earth: #8B5A2B;
        --earth-deep: #5C3A1E;
        --earth-soft: #A87A4D;
        --ink: #1A1A1A;
        --muted: #5C5C5C;
        --dust: #F5EFE6;
        --accent: #D98C5F;
    }

    body {
        background: #FCFAF7;
        font-family: 'Inter', sans-serif;
        color: var(--ink);
    }

    .grain {
        background-image: url("https://grainy-gradients.vercel.app/noise.svg");
        opacity: 0.03;
        pointer-events: none;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .btn-primary {
        background: var(--earth);
        color: white;
        border: none;
        font-weight: 500;
        transition: all 0.3s;
        box-shadow: 0 6px 14px rgba(139, 90, 43, 0.12);
    }
    .btn-primary:hover {
        background: var(--earth-deep);
        transform: translateY(-2px);
        box-shadow: 0 14px 24px rgba(92, 58, 30, 0.18);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--earth);
        color: var(--earth);
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-outline:hover {
        background: var(--earth);
        color: white;
    }

    .service-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s;
    }
    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 28px 32px -16px rgba(139, 90, 43, 0.12);
        border-color: rgba(139, 90, 43, 0.2);
    }

    .pricing-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }
    .pricing-card.featured {
        border: 2px solid var(--earth);
        box-shadow: 0 20px 30px -10px rgba(139, 90, 43, 0.15);
    }

    .section-title {
        font-size: 2.25rem;
        font-weight: 600;
        letter-spacing: -0.02em;
    }
    @media (max-width: 768px) {
        .section-title { font-size: 1.9rem; }
    }
</style>
@endpush

<section class="relative overflow-hidden pt-24 pb-10 md:pt-32 md:pb-12">
    <div class="relative container mx-auto px-6 text-center max-w-4xl">
        <span class="inline-flex items-center gap-2 text-sm bg-white/70 border border-black/5 px-4 py-2 rounded-full shadow-sm">
            <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>What we do
        </span>
        <h1 class="text-4xl md:text-5xl font-semibold mt-6 leading-tight tracking-tight">
            Plan your East Africa trip with <span class="text-[#8B5A2B]">people who know it</span>
        </h1>
        <p class="mt-4 text-[#5C5C5C] text-lg max-w-2xl mx-auto">
            We connect you with vetted local operators, show you starting prices up front, and help you plan a trip that fits how you like to travel.
        </p>
    </div>
</section>

<section id="travel" class="container mx-auto px-6 py-10 max-w-5xl scroll-mt-20">
    <h2 class="text-2xl md:text-3xl font-semibold text-center">Pick the way you travel</h2>
    <div class="mt-8 grid md:grid-cols-3 gap-6">
        @foreach ($styles as $slug => $st)
            @php $range = \App\Support\TripStyles::priceRange($slug); @endphp
            <a href="{{ route('trips.show', $slug) }}" class="service-card p-6 flex flex-col hover:shadow-lg transition"
                data-gtag-event="persona_card_click" data-gtag-params="{{ json_encode(['style' => $slug]) }}">
                <span class="text-xs font-medium text-[#8B5A2B]">{{ $st['badge'] }}</span>
                <h3 class="text-xl font-semibold mt-2">{{ $st['name'] }}</h3>
                <p class="text-sm text-[#5C5C5C] mt-2 flex-1">{{ $st['who'][0] ?? '' }}</p>
                @if ($range)
                    <p class="mt-4 text-sm font-semibold">From {{ \App\Support\Money::usd($range['min']) }}</p>
                @endif
                <span class="mt-3 text-sm font-medium text-[#8B5A2B]">See trips &rarr;</span>
            </a>
        @endforeach
    </div>
</section>

<section class="container mx-auto px-6 py-10 max-w-5xl">
    <h2 class="text-2xl md:text-3xl font-semibold text-center">How it works</h2>
    <div class="mt-8 grid md:grid-cols-3 gap-6">
        @foreach ([
            ['1', 'Tell us what you want', 'Pick a trip style, search destinations, or send us your dates and group size.'],
            ['2', 'Compare options', 'See starting prices and what is included from vetted local partners.'],
            ['3', 'Book with a local operator', 'We connect you directly. You get a reply within 24 business hours.'],
        ] as [$n, $t, $d])
            <div class="service-card p-6">
                <div class="w-9 h-9 rounded-full bg-[#8B5A2B]/10 text-[#8B5A2B] font-semibold flex items-center justify-center">{{ $n }}</div>
                <h3 class="font-semibold mt-3">{{ $t }}</h3>
                <p class="text-sm text-[#5C5C5C] mt-1">{{ $d }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="container mx-auto px-6 py-10 max-w-4xl">
    <div class="service-card p-8 text-center">
        <h2 class="text-2xl font-semibold">Not sure where to go?</h2>
        <p class="text-[#5C5C5C] mt-2">Search destinations and see what fits your dates, budget and group.</p>
        <a href="{{ route('discover.index') }}" class="inline-block mt-5 px-6 py-3 rounded-xl bg-[#8B5A2B] hover:bg-[#5C3A1E] text-white font-semibold transition">Explore destinations</a>
    </div>
</section>

<section class="container mx-auto px-6 pt-6 pb-20 max-w-4xl text-center">
    <h2 class="text-2xl font-semibold">Ready to plan?</h2>
    <p class="text-[#5C5C5C] mt-2">Tell us who's coming and when. We reply within 24 business hours.</p>
    <a href="{{ route('contact') }}" class="inline-block mt-5 px-6 py-3 rounded-xl bg-white border border-black/10 hover:border-[#8B5A2B] font-semibold transition">Request a custom quote</a>
</section>
@endsection
