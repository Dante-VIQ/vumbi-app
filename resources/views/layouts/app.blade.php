<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Primary SEO --}}
    <title>@yield('title', 'Discover Africa Travel Guides & Trip Planning | Vumbi Ventures')</title>
    <meta name="description" content="@yield('description', 'Discover Africa through curated travel guides, destination insights, and personalized trip planning. Explore safaris, cities, beaches and hidden gems across Africa.')">
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
    <meta property="og:description" content="@yield('og_description', 'Explore Africa through curated destination guides, stories and personalized travel planning.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo1.png'))">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Discover Africa with Vumbi Ventures')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Curated African travel guides and personalized trip planning.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <script src="https://www.dwin2.com/pub.2580697.min.js"></script>

    <script>
  var ahrefs_analytics_script = document.createElement('script');
  ahrefs_analytics_script.async = true;
  ahrefs_analytics_script.src = 'https://analytics.ahrefs.com/analytics.js';
  ahrefs_analytics_script.setAttribute('data-key', '86XMcrcnBj1CKAdhUIRDSg');
  document.getElementsByTagName('head')[0].appendChild(ahrefs_analytics_script);
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
            <svg class="animate-spin h-8 w-8 text-[#8B5A2B]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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

    {{-- Scripts --}}
    @livewireScripts
    @stack('scripts')

    {{-- Google Analytics --}}
    @production
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JDQTSGVSYS"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-JDQTSGVSYS');
</script>
    @endproduction

</body>
</html>
