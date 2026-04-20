@extends('layouts.app')

@section('title', 'Discover Africa with Vumbi Ventures | Book Hotels, Tours & Destinations')
@section('meta_description', 'Book handpicked African hotels, safaris, and cultural experiences. Vumbi Ventures partners with top travel providers to bring you authentic African adventures.')

    @push('meta')
        <meta name="description"
            content="Book handpicked African hotels, safaris, and cultural experiences. Vumbi Ventures partners with top travel providers to bring you authentic African adventures.">
        <meta name="keywords"
            content="Africa travel, book African hotels, safari booking, cultural tours Africa, African destinations, Vumbi Ventures">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="Discover Africa with Vumbi Ventures | Book Hotels, Tours & Destinations">
        <meta property="og:description" content="Book handpicked African hotels, safaris, and cultural experiences.">
        <meta property="og:image" content="{{ asset('images/discover-africa-og.jpg') }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Discover Africa with Vumbi Ventures">
        <meta name="twitter:description" content="Book handpicked African hotels, safaris, and cultural experiences.">
        <meta name="twitter:image" content="{{ asset('images/discover-africa-og.jpg') }}">
    @endpush

    @push('structured-data')
    @php
        $homepageSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'TravelAgency',
            '@id' => url('/') . '#homepage',
            'name' => 'Vumbi Ventures - Discover Africa Travel Platform',
            'description' => 'Discover and book authentic African travel experiences. We partner with top providers to bring you curated hotels, safaris, and cultural tours.',
            'url' => url('/'),
            "areaServed" => "Africa",
            "sameAs" => [
                "https://twitter.com/vumbiventures",
                "https://www.linkedin.com/company/vumbi-ventures"
            ],
            'isPartOf' => [
                '@id' => url('/') . '#website'
            ],
            'about' => [
                '@type' => 'Organization',
                '@id' => url('/') . '#organization'
            ]
        ];
    @endphp

    <script type="application/ld+json">
    {!! json_encode($homepageSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    @php
        $discoverpageSchema = [
             "@context"=> "https://schema.org",
              "@type"=> "WebSite",
              "url"=> "{{ url('/') }}",
              "potentialAction"=> [
                "@type" => "SearchAction",
                "target"=> "{{ url('/search') }}?q={search_term_string}",
                "query-input"=> "required name=search_term_string"
              ]
        ];
    @endphp

       <script type="application/ld+json">
    {!! json_encode($discoverpageSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

    @push('styles')
        <style>
            /* Same refined styles as before (keep all CSS from previous version) */
            :root {
                --earth: #8B5A2B;
                --earth-deep: #5C3A1E;
                --earth-soft: #A87A4D;
                --ink: #1A1A1A;
                --muted: #5C5C5C;
                --dust: #F5EFE6;
                --accent: #D98C5F;
                --glass-border: rgba(255, 255, 255, 0.3);
            }

            body {
                background: #FCFAF7;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                font-display: swap;
                color: var(--ink);
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            html {
                scroll-behavior: smooth;
            }

            :focus-visible {
                outline: 2px solid var(--earth);
                outline-offset: 2px;
            }

            .hero {
                min-height: 100vh;
                position: relative;
                overflow: hidden;
                background: radial-gradient(circle at 20% 30%, rgba(139, 90, 43, 0.06), transparent 45%),
                    radial-gradient(circle at 85% 70%, rgba(217, 140, 95, 0.08), transparent 50%),
                    linear-gradient(145deg, #FFFFFF 0%, #FCFAF7 100%);
            }

            .grain {
                position: absolute;
                inset: 0;
                background-image: url("https://grainy-gradients.vercel.app/noise.svg");
                opacity: 0.035;
                pointer-events: none;
                z-index: 0;
            }

            .hero-content {
                position: relative;
                z-index: 2;
            }

            .float {
                animation: float 7s ease-in-out infinite;
                will-change: transform;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0) rotate(0deg);
                }

                50% {
                    transform: translateY(-12px) rotate(0.2deg);
                }
            }

            .float-slow {
                animation: float 9s ease-in-out infinite;
            }

            .fade-up {
                opacity: 0;
                animation: fadeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @keyframes fadeUp {
                from {
                    opacity: 0;
                    transform: translateY(24px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .delay-1 {
                animation-delay: 0.1s;
            }

            .delay-2 {
                animation-delay: 0.2s;
            }

            .delay-3 {
                animation-delay: 0.3s;
            }

            .glass-panel {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                border: 1px solid var(--glass-border);
                box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08), 0 4px 18px rgba(0, 0, 0, 0.02);
            }

            .btn-primary {
                background: var(--earth);
                color: white;
                border: none;
                font-weight: 500;
                transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1);
                box-shadow: 0 6px 14px rgba(139, 90, 43, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .btn-primary:hover {
                background: var(--earth-deep);
                transform: translateY(-3px);
                box-shadow: 0 14px 24px rgba(92, 58, 30, 0.18);
            }

            .btn-secondary {
                background: white;
                color: var(--earth);
                border: 1px solid rgba(139, 90, 43, 0.2);
                font-weight: 500;
                transition: all 0.3s;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
            }

            .btn-secondary:hover {
                background: var(--earth);
                color: white;
                border-color: var(--earth);
                transform: translateY(-2px);
            }

            .btn-ghost {
                background: transparent;
                border: 1px solid rgba(0, 0, 0, 0.08);
                font-weight: 500;
                transition: all 0.25s;
                backdrop-filter: blur(4px);
            }

            .btn-ghost:hover {
                border-color: var(--earth);
                color: var(--earth);
                background: rgba(139, 90, 43, 0.02);
                transform: translateY(-2px);
            }

            .section-title {
                font-size: 2.25rem;
                font-weight: 600;
                letter-spacing: -0.02em;
                line-height: 1.2;
                color: var(--ink);
            }

            .section-sub {
                color: var(--muted);
                font-size: 1.125rem;
                margin-top: 0.5rem;
            }

            .destination-card,
            .service-card,
            .insight-card,
            .team-card {
                background: white;
                border-radius: 1.5rem;
                box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.04);
                border: 1px solid rgba(0, 0, 0, 0.02);
                transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1);
                backdrop-filter: blur(4px);
            }

            .destination-card:hover,
            .service-card:hover,
            .insight-card:hover,
            .team-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 28px 32px -16px rgba(139, 90, 43, 0.12);
                border-color: rgba(139, 90, 43, 0.15);
            }

            .stat-number {
                font-size: 2rem;
                font-weight: 600;
                color: var(--ink);
                line-height: 1.2;
            }

            .trust-logo {
                filter: grayscale(1) opacity(0.7);
                transition: filter 0.2s;
            }

            .trust-logo:hover {
                filter: grayscale(0) opacity(1);
            }

            @media (max-width: 768px) {
                .section-title {
                    font-size: 1.9rem;
                }

                .hero {
                    min-height: auto;
                    padding: 6rem 0 4rem;
                }
            }
        </style>
    @endpush

@section('content')

    <!-- Hero Section -->
       @include('pages.landing')

@endsection
