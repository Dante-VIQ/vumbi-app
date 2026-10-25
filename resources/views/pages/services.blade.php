@extends('layouts.app')

 
@section('title', 'African Safari & Travel Planning Services | Custom Itineraries | Vumbi Ventures')
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

{{-- ===================== HERO ===================== --}}
<section class="relative overflow-hidden pt-24 pb-12 md:pt-32 md:pb-16">
    <div class="absolute inset-0 grain"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(139,90,43,0.05),transparent_50%)]"></div>

    <div class="relative container mx-auto px-6 text-center max-w-4xl">
        <span class="inline-flex items-center gap-2 text-sm bg-white/70 backdrop-blur-sm border border-white/40 px-4 py-2 rounded-full shadow-sm">
            <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>
            What We Do
        </span>

        <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold mt-6 leading-tight tracking-tight">
            Plan your East Africa trip with <span class="text-[#8B5A2B] relative inline-block">
                people who know it
                <span class="absolute -bottom-1 left-0 w-full h-1 bg-[#D98C5F]/30 rounded-full"></span>
            </span>
        </h1>

        <p class="mt-4 text-[#5C5C5C] text-lg max-w-2xl mx-auto">
            Search destinations, compare starting prices, and book with vetted local operators, or ask us to put together a custom itinerary.
        </p>
    </div>
</section>

{{-- ===================== PRIMARY SERVICE: TRAVEL DISCOVERY ===================== --}}
<section id="travel" class="container mx-auto px-6 py-12 scroll-mt-20">
    <div class="glass-panel rounded-3xl p-8 md:p-12 shadow-xl">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <div class="inline-flex items-center gap-2 text-sm bg-[#8B5A2B]/10 text-[#8B5A2B] px-4 py-1.5 rounded-full mb-4">
                    <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>
                    Plan Your Trip
                </div>
                <h2 class="text-3xl md:text-4xl font-semibold leading-tight">
                    Discover Africa.
                    <br>
                    <span class="text-[#8B5A2B]">Book Extraordinary.</span>
                </h2>
                <p class="text-[#5C5C5C] mt-4 leading-relaxed">
                    Our curated travel platform connects you with handpicked hotels, safaris, cultural tours, and flights across Africa. We partner with trusted providers to bring you the best deals — all in one seamless search.
                </p>

                <ul class="mt-6 space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#8B5A2B] mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-[#5C5C5C]">Search destinations, safaris and cultural tours across Kenya and East Africa</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#8B5A2B] mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-[#5C5C5C]">Starting prices shown upfront, with booking through established partners</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#8B5A2B] mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-[#5C5C5C]">Local expertise — curated by Africans who know the continent</span>
                    </li>
                </ul>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ url('/discover') }}" class="btn-primary px-6 py-3 rounded-xl inline-flex items-center gap-2">
                        Start Exploring
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15 15 0 0 0 0 20 15 15 0 0 0 0-20z"/><path d="M2 12h20"/></svg>
                    </a>
                    <a href="{{ url('/discover') }}" class="btn-outline px-6 py-3 rounded-xl inline-flex items-center gap-2">
                        View Destinations
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg">
                    <img src="{{ asset('images/maasai-mara.jpg') }}" alt="Safari vehicle on the Maasai Mara plains" loading="lazy" class="w-full h-full object-cover">
                </div>
                {{-- Floating stat card --}}
                <div class="absolute -bottom-4 -left-4 bg-white/90 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-white/50">
                    <div class="text-2xl font-bold text-[#8B5A2B]">Upfront</div>
                    <div class="text-sm text-[#5C5C5C]">Starting prices</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== FINAL CTA ===================== --}}
<section class="py-20 bg-[#121212] text-white text-center relative overflow-hidden mt-12 rounded-t-[3rem]">
    <div class="absolute inset-0 grain opacity-[0.02]"></div>
    <div class="container mx-auto px-6 relative">
        <h2 class="text-3xl md:text-4xl font-semibold">Ready to get started?</h2>
        <p class="text-white/70 text-lg mt-3 max-w-xl mx-auto">
            Tell us when you want to travel and who is coming, and we'll come back with options and prices.
        </p>
        <div class="flex flex-wrap justify-center gap-4 mt-8">
            <a href="{{ url('/discover') }}" class="bg-[#8B5A2B] hover:bg-[#6B421F] px-6 py-3 rounded-xl font-medium transition">
                Find My Trip
            </a>
            <a href="{{ url('/contact') }}" class="bg-transparent border border-white/30 hover:bg-white/10 px-6 py-3 rounded-xl font-medium transition">
                Ask for a Custom Quote
            </a>
        </div>
    </div>
</section>

@endsection