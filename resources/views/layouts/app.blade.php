<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vumbi-persona" content="{{ \App\Support\Persona::current() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', config('app.name', 'Vumbi Ventures')) - Authentic East African Safaris & Expeditions</title>
    <meta name="description" content="@yield('description', 'Custom African safaris, tour vehicle rentals, live itineraries, and authentic travel experiences across Kenya and East Africa.')">
    <meta name="keywords" content="@yield('keywords', 'Kenya safaris, Maasai Mara tours, vehicle rentals Nakuru, East Africa travel, Vumbi Ventures')">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', $__env->yieldContent('title') ?: config('app.name'))">
    <meta property="og:description" content="@yield('og_description', $__env->yieldContent('description') ?: 'Authentic African safaris and custom tour itineraries.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', $__env->yieldContent('title') ?: config('app.name'))">
    <meta name="twitter:description" content="@yield('twitter_description', $__env->yieldContent('description') ?: 'Authentic African safaris and custom tour itineraries.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Robots --}}
    <meta name="robots" content="@yield('robots', 'index, follow')">

    {{-- Performance hints --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Structured Data (site-wide) --}}
    @php
        $schemas = [];
        
        // Organization (TravelAgency). One stable @id so every page points at the same entity.
        $orgId = url('/') . '#organization';
        $seoSameAs = array_values(array_filter((array) config('seo.same_as')));
        $schemas[] = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'TravelAgency',
            '@id' => $orgId,
            'name' => config('seo.name'),
            'url' => url('/'),
            'logo' => asset('images/logo1.png'),
            'image' => asset('images/og-default.jpg'),
            'description' => config('seo.tagline'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => config('seo.locality'),
                'addressCountry' => config('seo.country'),
            ],
            'areaServed' => ['Kenya', 'Tanzania', 'Uganda', 'Rwanda'],
            'telephone' => config('seo.telephone') ?: null,
            'email' => config('seo.email') ?: null,
            'sameAs' => $seoSameAs ?: null,
            'contactPoint' => array_filter([
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => config('seo.email') ?: null,
                'telephone' => config('seo.telephone') ?: null,
                'availableLanguage' => ['English', 'Swahili'],
            ]),
        ]);

        // WebSite schema
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'name' => config('seo.name'),
            'url' => url('/'),
            'publisher' => ['@id' => $orgId],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/tours') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

        if (isset($pageSchemas) && is_array($pageSchemas)) {
            $schemas = array_merge($schemas, $pageSchemas);
        }

        $breadcrumbItems = [
            ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => url('/')]
        ];

        if (request()->routeIs('singleblog')) {
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Blog", "item" => route('blog')];
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 3, "name" => $blog->title ?? 'Post', "item" => url()->current()];
        } elseif (request()->routeIs('tours.show')) {
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Tours", "item" => route('tours.index')];
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 3, "name" => $package->title ?? 'Package', "item" => url()->current()];
        } elseif (request()->routeIs('destinations.show')) {
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Destinations", "item" => route('destinations.index')];
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 3, "name" => $destination->name ?? 'Destination', "item" => url()->current()];
        } elseif (request()->routeIs('blog.index')) {
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Blog", "item" => url()->current()];
        } elseif (request()->routeIs('tours.index')) {
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => "Tours", "item" => url()->current()];
        } else {
            $breadcrumbItems[] = ["@type" => "ListItem", "position" => 2, "name" => $pageName ?? 'Page', "item" => url()->current()];
        }

        $schemas[] = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $breadcrumbItems
        ];
    @endphp

    {{-- Render JSON-LD Schemas --}}
    @foreach ($schemas as $schema)
        <script type="application/ld+json">
            @json($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        </script>
    @endforeach
    @stack('schema')

    {{-- Analytics — Ahrefs --}}
    <script>
        var ahrefs_analytics_script = document.createElement('script');
        ahrefs_analytics_script.async = true;
        ahrefs_analytics_script.src = 'https://analytics.ahrefs.com/analytics.js';
        ahrefs_analytics_script.setAttribute('data-key', '86XMcrcnBj1CKAdhUIRDSg');
        document.getElementsByTagName('head')[0].appendChild(ahrefs_analytics_script);
    </script>

<script nowprocket data-noptimize="1" data-cfasync="false" data-wpfc-render="false" seraph-accel-crit="1" data-no-defer="1" data-cmp-ab="2">
  (function () {
      var script = document.createElement("script");
      script.async = 1;
      script.setAttribute("data-cmp-ab","2");
      script.src = 'https://emrldtp.cc/NDY1NDg3.js?t=465487';
      document.head.appendChild(script);
  })();
</script>

<script async defer src="https://widget.getyourguide.com/dist/pa.umd.production.min.js" data-gyg-partner-id="4W8QSG6"></script>
    {{-- Styles --}}
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased text-[#1A1A1A] bg-white">

    {{-- Livewire Loading Overlay --}}
    <div wire:loading.flex class="fixed inset-0 bg-black/50 z-[9999] items-center justify-center hidden">
        <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-3">
            <svg class="animate-spin h-8 w-8 text-[#8B5A2B]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="font-medium text-slate-800">Loading expedition details...</span>
        </div>
    </div>

    {{-- Primary Travel Navigation --}}
    <x-navigation />

    {{-- Main Travel Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

    {{-- Floating Safari Inquiry & Package Finder Widget --}}
    <div x-data="{ openGeneralBooking: false, search: '', packages: [] }">
        <!-- Floating CTA Button -->
        <button @click="openGeneralBooking = true"
            class="fixed bottom-6 right-6 bg-[#8B5A2B] hover:bg-[#6e4620] text-white px-5 py-3.5 rounded-full shadow-2xl z-40 transition transform hover:scale-105 flex items-center gap-2 font-medium">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span>Plan Your Safari</span>
        </button>

        <!-- Travel Search & Booking Modal -->
        <div x-show="openGeneralBooking" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
            <div class="bg-slate-900 text-white rounded-2xl p-6 w-full max-w-lg shadow-2xl border border-slate-800"
                @click.away="openGeneralBooking = false">
                <div class="flex justify-between items-center mb-4 border-b border-slate-800 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-white">Find a Safari Package</h3>
                        <p class="text-xs text-slate-400">Search destinations, parks, or safari tours</p>
                    </div>
                    <button @click="openGeneralBooking = false" class="text-slate-400 hover:text-white p-1">✕</button>
                </div>
                
                <input type="text" x-model="search"
                    @input.debounce.300ms="fetch('/api/packages/search?q='+search).then(r=>r.json()).then(d=>packages=d)"
                    placeholder="Where do you want to go? (e.g. Maasai Mara, Nakuru)"
                    class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:border-amber-500 outline-none mb-4">
                
                <div class="max-h-64 overflow-y-auto space-y-3 pr-1">
                    <template x-for="pkg in packages" :key="pkg.id">
                        <div class="flex items-center justify-between p-3.5 bg-slate-800/80 rounded-xl hover:bg-slate-700 transition cursor-pointer border border-slate-700/50"
                            @click="openGeneralBooking = false; $dispatch('open-booking', { packageId: pkg.id })">
                            <div>
                                <div x-text="pkg.title" class="font-medium text-amber-400"></div>
                                <div x-text="pkg.location" class="text-xs text-slate-300 mt-0.5"></div>
                            </div>
                            <span x-text="'KSh ' + Number(pkg.price).toLocaleString()"
                                class="text-white font-bold bg-amber-600/30 px-3 py-1 rounded-lg border border-amber-500/40 text-sm"></span>
                        </div>
                    </template>
                    <div x-show="search && !packages.length" class="text-center text-slate-400 py-6 text-sm">
                        <p>No safari packages found matching your query yet. Tell us what you have in mind and we'll build a custom itinerary.</p>
                        <div class="flex justify-center gap-4 mt-3">
                            <a href="{{ route('contact') }}" data-gtag-event="no_results_quote_click" class="text-amber-400 hover:text-amber-300 font-semibold">Get a custom quote</a>
                            <a :href="'https://wa.me/{{ config('services.whatsapp.number') }}?text=' + encodeURIComponent('Hi Vumbi Ventures, I searched for ' + search + ' and would like help planning a safari.')"
                                target="_blank" rel="noopener"
                                data-gtag-event="whatsapp_click" data-gtag-params='{"placement":"search_modal_no_results"}'
                                class="text-green-400 hover:text-green-300 font-semibold">WhatsApp us</a>
                        </div>
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

    @include('partials.analytics-events')

</body>

</html>