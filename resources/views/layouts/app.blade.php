<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ============================================================
         SEO — single source of truth via $seo (see SeoComposer).
         Pages set $seo_title / $seo_description / (optionally)
         $seo_og_title / $seo_og_description / $seo_canonical / $seo_keywords
         as @php vars before @section('content') opens.
         OG + Twitter derive from title/description automatically
         unless a page explicitly overrides them.
         ============================================================ --}}
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    @if(!empty($seo['keywords']))
        <meta name="keywords" content="{{ $seo['keywords'] }}">
    @endif
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <meta name="robots" content="{{ $seo['robots'] }}">
    <meta name="theme-color" content="#ffffff">

    {{-- Open Graph — derived from $seo, not independently yielded --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vumbi Ventures">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:title" content="{{ $seo['og_title'] }}">
    <meta property="og:description" content="{{ $seo['og_description'] }}">
    <meta property="og:image" content="{{ $seo['og_image'] }}">

    {{-- Twitter — derived from $seo, not independently yielded --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['og_title'] }}">
    <meta name="twitter:description" content="{{ $seo['og_description'] }}">
    <meta name="twitter:image" content="{{ $seo['og_image'] }}">

    {{-- Favicons --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo1.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo1.png') }}">

    {{-- Performance hints --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Structured Data (site-wide) --}}
    @php
        $organizationSchema = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => "Vumbi Ventures",
            "url" => url('/'),
            "logo" => asset('images/vumbi-ventures-logo.png'),
            "sameAs" => [
                "https://twitter.com/vumbiventures",
                "https://linkedin.com/company/vumbi-ventures",
                "https://instagram.com/vumbiventures",
            ],
        ];

        $websiteSchema = [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => "Vumbi Ventures",
            "url" => url('/'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => url('/discover') . "?q={search_term_string}",
                "query-input" => "required name=search_term_string",
            ],
        ];
    @endphp
    <script type="application/ld+json">
        {!! json_encode($organizationSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($websiteSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>

    {{-- Page-specific schema (e.g. Article, TouristTrip, FAQPage) --}}
    @stack('schema')

    {{-- Analytics — Ahrefs --}}
    <script>
        var ahrefs_analytics_script = document.createElement('script');
        ahrefs_analytics_script.async = true;
        ahrefs_analytics_script.src = 'https://analytics.ahrefs.com/analytics.js';
        ahrefs_analytics_script.setAttribute('data-key', '86XMcrcnBj1CKAdhUIRDSg');
        document.getElementsByTagName('head')[0].appendChild(ahrefs_analytics_script);
    </script>

    {{--
        REMOVED: an injected <script> loading https://emrldtp.cc/NDY1NDg3.js
        was previously here, disguised with WordPress-cache-plugin bypass
        attributes (nowprocket / data-wpfc-render / seraph-accel-crit) even
        though this is a Laravel app. That combination is a common malicious
        injection / malvertising pattern, not a real analytics or ad tag.
        Do NOT re-add anything like this without confirming the source and
        auditing how it got into the layout in the first place.
    --}}

    {{-- Styles --}}
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-[#1A1A1A] bg-white">

    {{-- Livewire Loading Overlay --}}
    <div wire:loading.flex class="fixed inset-0 bg-black/50 z-[9999] items-center justify-center hidden">
        <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-3">
            <svg class="animate-spin h-8 w-8 text-[#8B5A2B]" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="font-medium">Loading...</span>
        </div>
    </div>

    {{-- Navigation --}}
    <x-navigation />

    {{-- Main Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

    <div x-data="{ openGeneralBooking: false, search: '', packages: [] }">
        <!-- Floating button -->
        <button @click="openGeneralBooking = true"
            class="fixed bottom-6 right-6 bg-green-600 hover:bg-green-500 text-white p-4 rounded-full shadow-2xl z-40 transition transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
        </button>

        <!-- Modal -->
        <div x-show="openGeneralBooking" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-zinc-900 rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl border border-zinc-800"
                @click.away="openGeneralBooking = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Book a Trip</h3>
                    <button @click="openGeneralBooking = false" class="text-zinc-400 hover:text-white">✕</button>
                </div>
                <input type="text" x-model="search"
                    @input.debounce.300ms="fetch('/api/packages/search?q='+search).then(r=>r.json()).then(d=>packages=d)"
                    placeholder="Where do you want to go? (e.g. Maasai Mara)"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white placeholder-zinc-400 focus:border-green-400 outline-none mb-4">
                <div class="max-h-64 overflow-y-auto space-y-3">
                    <template x-for="pkg in packages" :key="pkg.id">
                        <div class="flex items-center justify-between p-3 bg-zinc-800 rounded-xl hover:bg-zinc-700 transition cursor-pointer"
                            @click="openGeneralBooking = false; $dispatch('open-booking', { packageId: pkg.id })">
                            <div>
                                <div x-text="pkg.title" class="font-medium"></div>
                                <div x-text="pkg.location" class="text-sm text-zinc-400"></div>
                            </div>
                            <span x-text="'$ ' + Number(pkg.price).toLocaleString()"
                                class="text-green-400 font-semibold"></span>
                        </div>
                    </template>
                    <div x-show="search && !packages.length" class="text-center text-zinc-500 py-4">
                        No packages found for this destination.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @livewireScripts
    @stack('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        window.addEventListener('open-booking', e => {
            document.querySelector('[x-data]').__x.$data.openForPackage(e.detail.packageId);
        });
    </script>

    {{-- Google Analytics --}}
    @production
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-JDQTSGVSYS"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', 'G-JDQTSGVSYS');
        </script>
    @endproduction

</body>
</html>