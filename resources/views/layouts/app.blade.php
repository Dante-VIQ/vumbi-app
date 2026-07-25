<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', config('app.name', 'Vumbi Ventures'))</title>
    <meta name="description" content="@yield('description', 'Discover Africa. Before You Travel. Real stories, live prices, hidden gems, and authentic experiences from Kenya to Cape Town.')">
    {{-- Meta Keywords (optional) --}}
    <meta name="keywords" content="@yield('keywords', 'Africa travel, Vumbi Ventures, authentic experiences')">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', $__env->yieldContent('title') ?: config('app.name'))">
    <meta property="og:description" content="@yield('og_description', $__env->yieldContent('description') ?: 'Discover Africa. Before You Travel.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', $__env->yieldContent('title') ?: config('app.name'))">
    <meta name="twitter:description" content="@yield('twitter_description', $__env->yieldContent('description') ?: 'Discover Africa. Before You Travel.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">

    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Robots --}}
    <meta name="robots" content="@yield('robots', 'index, follow')">

    {{-- Performance hints --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Structured Data (site-wide) --}}
    {{-- In your layout <head> --}}

    @php

        $schemas = [];
        // Organization (TravelAgency)
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'TravelAgency',
            'name' => 'Vumbi Ventures',
            'url' => url()->current(),
            'logo' => asset('images/logo1.png'),
            'description' =>
                'Custom African safaris, cultural tours, and authentic travel experiences across East Africa.',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Nakuru',
                'addressCountry' => 'KE',
            ],
            'telephone' => '+254-734-591543',
            'email' => 'info@vumbiventures.com',
            'priceRange' => "$$",
            'openingHours' => 'Mo-Fr 09:00-17:00',
            'sameAs' => [
                'https://twitter.com/vumbiventures',
                'https://linkedin.com/company/vumbi-ventures',
                'https://instagram.com/vumbiventures',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service',
                'availableLanguage' => ['English', 'Swahili'],
            ],
            'potentialAction' => [
                [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => url('/tours') . '?q={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
                [
                    '@type' => 'ReserveAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => url('/tours'),
                        'actionPlatform' => [
                            'http://schema.org/DesktopWebPlatform',
                            'http://schema.org/MobileWebPlatform',
                        ],
                    ],
                    'description' => 'Book an authentic African safari or cultural tour.',
                ],
            ],
        ];

        // WebSite schema
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Vumbi Ventures',
            'url' => url()->current(),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/tours') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

        // Child views can add more schemas by defining $pageSchemas
        // and we merge them here.
        if (isset($pageSchemas) && is_array($pageSchemas)) {
            $schemas = array_merge($schemas, $pageSchemas);
        }

    

    $breadcrumbItems = [
        ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => url('/')]
    ];

    // Add based on route
    if (request()->routeIs('singleblog')) {
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Blog", "item" => route('blog')];
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 3, "name" => $blog->title, "item" => url()->current()];
    } elseif (request()->routeIs('tours.show')) {
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Tours", "item" => route('tours.index')];
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 3, "name" => $package->title, "item" => url()->current()];
    } elseif (request()->routeIs('destinations.show')) {
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Destinations", "item" => route('destinations.index')];
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 3, "name" => $destination->name, "item" => url()->current()];
    } elseif (request()->routeIs('blog.index')) {
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Blog", "item" => url()->current()];
    } elseif (request()->routeIs('tours.index')) {
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Tours", "item" => url()->current()];
    } else {
        // Fallback: use page title
        $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => $pageName ?? 'Page', "item" => url()->current()];
    }

    $schemas[] = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => $breadcrumbItems
    ];

    @endphp


        
    {{-- Render all schemas --}}
    @foreach ($schemas as $schema)
        <script type="application/ld+json">
    @json($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
</script>
    @endforeach
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

    {{-- {!! seo() !!} --}}
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
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
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

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', 'G-JDQTSGVSYS');
        </script>
    @endproduction

</body>

</html>
