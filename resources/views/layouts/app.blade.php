<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Primary SEO --}}
    <title>@yield('title', 'Discover Africa Travel Guides & Trip Planning | Vumbi Ventures')</title>
    <meta name="description"
        content="@yield('description', 'Discover Africa through curated travel guides, destination insights, and personalized trip planning. Explore safaris, cities, beaches and hidden gems across Africa.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#ffffff">

    {{-- Performance hints --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo1.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo1.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo1.png') }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vumbi Ventures">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Discover Africa | Travel Guides & Trip Planning')">
    <meta property="og:description"
        content="@yield('og_description', 'Explore Africa through curated destination guides, stories and personalized travel planning.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo1.png'))">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Discover Africa with Vumbi Ventures')">
    <meta name="twitter:description"
        content="@yield('twitter_description', 'Curated African travel guides and personalized trip planning.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://www.dwin2.com/pub.2580697.min.js"></script>

    <!-- Default organisation + website schemas (always present) -->
<script type="application/ld+json">
@json($organizationSchema)
</script>
<script type="application/ld+json">
@json($websiteSchema)
</script>

<!-- Page‑specific schemas will be injected here -->
@stack('structured_data')

    <script>
        var ahrefs_analytics_script = document.createElement('script');
        ahrefs_analytics_script.async = true;
        ahrefs_analytics_script.src = 'https://analytics.ahrefs.com/analytics.js';
        ahrefs_analytics_script.setAttribute('data-key', '86XMcrcnBj1CKAdhUIRDSg');
        document.getElementsByTagName('head')[0].appendChild(ahrefs_analytics_script);
    </script>

<script nowprocket data-noptimize="1" data-cfasync="false" data-wpfc-render="false" seraph-accel-crit="1" data-no-defer="1">
  (function () {
      var script = document.createElement("script");
      script.async = 1;
      script.src = 'https://emrldtp.cc/NDY1NDg3.js?t=465487';
      document.head.appendChild(script);
  })();
</script>
    {{-- Styles --}}
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Structured Data --}}
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
                "https://instagram.com/vumbiventures"
            ]
        ];

        $websiteSchema = [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => "Vumbi Ventures",
            "url" => url('/'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => url('/discover') . "?q={search_term_string}",
                "query-input" => "required name=search_term_string"
            ]
        ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES) !!}
    </script>

    <script type="application/ld+json">
        {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES) !!}
    </script>

    @stack('schema')
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

<x-booking-modal />

    <button @click="openGeneralBooking = true"
        class="fixed bottom-6 right-6 bg-blue-600 text-white p-4 rounded-full shadow-lg z-40">
    Book a Trip
</button>

<div x-data="{ openGeneralBooking: false, search: '', packages: [] }"
     x-show="openGeneralBooking" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4" @click.away="openGeneralBooking = false">
        <h3 class="font-bold mb-4">Where do you want to go?</h3>
        <input type="text" x-model="search" @input.debounce.300ms="fetch(`/api/packages/search?q=${search}`).then(r=>r.json()).then(d=>packages=d)"
               placeholder="e.g. Maasai Mara, Diani, Nairobi" class="w-full border p-2 rounded mb-4">
        <div class="space-y-3">
            <template x-for="pkg in packages" :key="pkg.id">
                <div class="flex justify-between items-center border-b pb-2">
                    <div>
                        <span class="font-medium" x-text="pkg.title"></span>
                        <span class="text-sm text-gray-500" x-text="pkg.location"></span>
                    </div>
                    <button @click="openGeneralBooking = false; $dispatch('open-booking', { packageId: pkg.id })"
                            class="text-blue-600 text-sm">Select</button>
                </div>
            </template>
        </div>
        <button @click="openGeneralBooking = false" class="mt-4 px-4 py-2 border rounded">Close</button>
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