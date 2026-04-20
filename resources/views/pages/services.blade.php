@extends('layouts.app')

@section('title', 'Services — Travel Discovery & Web Development | Vumbi Ventures')
@section('meta_description', 'Discover Africa with our curated travel platform, or grow your business with custom web development, media, and marketing solutions.')

@section('content')

@push('meta')
    <meta name="description" content="Discover Africa with our curated travel platform, or grow your business with custom web development, media, and marketing solutions.">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Services — Travel Discovery & Web Development | Vumbi Ventures">
    <meta property="og:description" content="Discover Africa with our curated travel platform, or grow your business with custom web development, media, and marketing solutions.">
    <meta property="og:type" content="website">
@endpush

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
            What We Offer
        </span>

        <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold mt-6 leading-tight tracking-tight">
            Two ways to <span class="text-[#8B5A2B] relative inline-block">
                grow
                <span class="absolute -bottom-1 left-0 w-full h-1 bg-[#D98C5F]/30 rounded-full"></span>
            </span>
            with us
        </h1>

        <p class="mt-4 text-[#5C5C5C] text-lg max-w-2xl mx-auto">
            Whether you're a traveler seeking authentic African experiences, or a business ready to build a powerful online presence — we've got you covered.
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
                    Primary Offering
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
                        <span class="text-[#5C5C5C]">200+ handpicked destinations across the continent</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#8B5A2B] mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-[#5C5C5C]">Best price guarantee with our partner network</span>
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
                    <img src="https://placehold.co/800x600/e8dfd5/8B5A2B?text=African+Safari" alt="African safari experience" class="w-full h-full object-cover">
                </div>
                {{-- Floating stat card --}}
                <div class="absolute -bottom-4 -left-4 bg-white/90 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-white/50">
                    <div class="text-2xl font-bold text-[#8B5A2B]">200+</div>
                    <div class="text-sm text-[#5C5C5C]">Destinations</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== SECONDARY SERVICE: WEB DEVELOPMENT ===================== --}}
<section id="web-dev" class="container mx-auto px-6 py-12 scroll-mt-20">
    <div class="text-center mb-10">
        <span class="inline-block text-sm text-[#8B5A2B] font-medium uppercase tracking-wider">Secondary Offering</span>
        <h2 class="section-title mt-2">Web Development & Digital Growth</h2>
        <p class="text-[#5C5C5C] max-w-2xl mx-auto mt-3">
            We build stunning websites, craft visual identities, and drive client acquisition for businesses ready to scale.
        </p>
    </div>

    {{-- Service Cards --}}
    <div class="grid md:grid-cols-3 gap-6">
        @php
            $devServices = [
                [
                    'icon' => '💻',
                    'title' => 'Custom Web Development',
                    'desc' => 'Responsive, high-performance websites and web applications tailored to your brand and goals.',
                    'features' => ['Laravel / PHP', 'Livewire & Alpine.js', 'E‑commerce', 'Booking Systems'],
                ],
                [
                    'icon' => '🎨',
                    'title' => 'Media & Visual Identity',
                    'desc' => 'Cohesive branding, photography, video, and design that tells your story beautifully.',
                    'features' => ['Logo & Branding', 'Photo / Video Production', 'Social Media Kits', 'UI/UX Design'],
                ],
                [
                    'icon' => '📈',
                    'title' => 'Marketing & Client Acquisition',
                    'desc' => 'Strategic campaigns that attract, convert, and retain your ideal customers.',
                    'features' => ['SEO & Content', 'Paid Ads (Google/Meta)', 'Email Automation', 'Analytics & Funnels'],
                ],
            ];
        @endphp

        @foreach($devServices as $service)
            <div class="service-card p-6 group">
                <div class="text-4xl mb-4">{{ $service['icon'] }}</div>
                <h3 class="text-xl font-semibold mb-2">{{ $service['title'] }}</h3>
                <p class="text-[#5C5C5C] text-sm leading-relaxed">{{ $service['desc'] }}</p>
                <ul class="mt-4 space-y-2">
                    @foreach($service['features'] as $feat)
                        <li class="flex items-center gap-2 text-sm text-[#5C5C5C]">
                            <span class="w-1.5 h-1.5 bg-[#8B5A2B] rounded-full"></span>
                            {{ $feat }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</section>

{{-- ===================== PACKAGES / PRICING ===================== --}}
<section class="container mx-auto px-6 py-12">
    <div class="text-center mb-10">
        <h2 class="section-title">Web Development Packages</h2>
        <p class="text-[#5C5C5C] max-w-2xl mx-auto mt-3">
            Flexible options for businesses at every stage. All packages include responsive design and SEO foundations.
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
        @php
            $packages = [
                [
                    'name' => 'Starter',
                    'price' => '$700',
                    'desc' => 'Perfect for small businesses needing a professional online presence.',
                    'features' => [
                        '5-Page Custom Website',
                        'Mobile Responsive',
                        'Basic SEO Setup',
                        'Contact Form Integration',
                        '2 Rounds of Revisions',
                        '30 Days Support',
                    ],
                    'featured' => false,
                ],
                [
                    'name' => 'Growth',
                    'price' => '$1250',
                    'desc' => 'For businesses ready to attract clients and showcase their work.',
                    'features' => [
                        'Up to 10 Pages',
                        'Advanced SEO',
                        'Blog or Portfolio Section',
                        'Social Media Integration',
                        'Basic Branding Package',
                        '3 Months Support',
                    ],
                    'featured' => true,
                ],
                [
                    'name' => 'Scale',
                    'price' => 'Custom',
                    'desc' => 'Complex web apps, e‑commerce, or ongoing marketing retainers.',
                    'features' => [
                        'Custom Web Application',
                        'E‑commerce / Booking Systems',
                        'Full Branding & Visuals',
                        'Marketing Strategy & Ads',
                        'Ongoing Maintenance',
                        'Priority Support',
                    ],
                    'featured' => false,
                ],
            ];
        @endphp

        @foreach($packages as $pkg)
            <div class="pricing-card p-6 {{ $pkg['featured'] ? 'featured' : '' }} relative">
                @if($pkg['featured'])
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#8B5A2B] text-white text-xs font-medium px-3 py-1 rounded-full">Most Popular</div>
                @endif
                <h3 class="text-xl font-semibold">{{ $pkg['name'] }}</h3>
                <div class="mt-2 text-3xl font-bold text-[#8B5A2B]">{{ $pkg['price'] }}</div>
                <p class="text-sm text-[#5C5C5C] mt-1">{{ $pkg['desc'] }}</p>
                <ul class="mt-6 space-y-3">
                    @foreach($pkg['features'] as $feat)
                        <li class="flex items-start gap-2 text-sm">
                            <svg class="w-4 h-4 text-[#8B5A2B] mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>{{ $feat }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ url('/contact') }}" class="block w-full mt-6 py-2.5 rounded-lg text-center font-medium {{ $pkg['featured'] ? 'btn-primary' : 'btn-outline' }}">
                    Get Started
                </a>
            </div>
        @endforeach
    </div>

    <p class="text-center text-sm text-[#5C5C5C] mt-8">
        Need something custom? <a href="{{ url('/contact') }}" class="text-[#8B5A2B] font-medium hover:underline">Let's talk</a>.
    </p>
</section>

{{-- ===================== PROCESS / HOW WE WORK ===================== --}}
<section class="container mx-auto px-6 py-12">
    <div class="text-center mb-10">
        <h2 class="section-title">How We Work</h2>
        <p class="text-[#5C5C5C] max-w-2xl mx-auto mt-3">A straightforward process from idea to launch — and beyond.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
        @php
            $steps = [
                ['number' => '01', 'title' => 'Discovery Call', 'desc' => 'We learn about your business, goals, and audience.'],
                ['number' => '02', 'title' => 'Proposal & Plan', 'desc' => 'A clear scope, timeline, and investment estimate.'],
                ['number' => '03', 'title' => 'Design & Build', 'desc' => 'Iterative development with regular feedback loops.'],
                ['number' => '04', 'title' => 'Launch & Grow', 'desc' => 'Deployment, training, and ongoing marketing support.'],
            ];
        @endphp

        @foreach($steps as $step)
            <div class="text-center p-4">
                <div class="text-3xl font-light text-[#D98C5F]/60">{{ $step['number'] }}</div>
                <h4 class="font-semibold mt-2">{{ $step['title'] }}</h4>
                <p class="text-sm text-[#5C5C5C] mt-1">{{ $step['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ===================== FINAL CTA ===================== --}}
<section class="py-20 bg-[#121212] text-white text-center relative overflow-hidden mt-12 rounded-t-[3rem]">
    <div class="absolute inset-0 grain opacity-[0.02]"></div>
    <div class="container mx-auto px-6 relative">
        <h2 class="text-3xl md:text-4xl font-semibold">Ready to get started?</h2>
        <p class="text-white/70 text-lg mt-3 max-w-xl mx-auto">
            Whether it's planning your next African adventure or building a website that works for you — we're here.
        </p>
        <div class="flex flex-wrap justify-center gap-4 mt-8">
            <a href="{{ url('/discover') }}" class="bg-[#8B5A2B] hover:bg-[#6B421F] px-6 py-3 rounded-xl font-medium transition">
                Explore Destinations
            </a>
            <a href="{{ url('/contact') }}" class="bg-transparent border border-white/30 hover:bg-white/10 px-6 py-3 rounded-xl font-medium transition">
                Discuss a Project
            </a>
        </div>
    </div>
</section>

@endsection