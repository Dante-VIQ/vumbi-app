@extends('layouts.app')

@section('title', 'Vumbi Ventures — Discover Africa | Travel Guides, Safaris & Stories')
@section('meta_description', 'Discover Africa through curated travel guides, destination insights, safari planning and untold stories from the continent. Your Africa journey starts here.')

@push('meta')
    <meta name="keywords" content="africa travel, east africa safari, zanzibar, maasai mara, africa destinations, africa travel guide, kenya travel, tanzania safari, african history">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Vumbi Ventures — Discover Africa | Travel Guides, Safaris & Stories">
    <meta property="og:description" content="Discover Africa through curated travel guides, destination insights, safari planning and untold stories from the continent.">
    <meta property="og:image" content="{{ asset('images/discover-africa-og.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Vumbi Ventures — Discover Africa">
    <meta name="twitter:description" content="Curated travel guides, safari planning and untold stories from Africa.">
    <meta name="twitter:image" content="{{ asset('images/discover-africa-og.jpg') }}">
@endpush

@push('structured-data')
@php
    $homepageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'TravelAgency',
        '@id' => url('/') . '#homepage',
        'name' => 'Vumbi Ventures',
        'description' => 'Africa-first travel discovery platform. Curated destination guides, safari planning, cultural stories and booking for East Africa and beyond.',
        'url' => url('/'),
        'areaServed' => 'Africa',
        'sameAs' => [
            'https://twitter.com/vumbiventures',
            'https://www.linkedin.com/company/vumbi-ventures',
            'https://www.facebook.com/vumbiventures'
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($homepageSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
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
        --cream: #FCFAF7;
        --accent: #D98C5F;
        --glass-border: rgba(255,255,255,0.3);
    }

    body {
        background: var(--cream);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--ink);
        -webkit-font-smoothing: antialiased;
    }

    html { scroll-behavior: smooth; }

    :focus-visible {
        outline: 2px solid var(--earth);
        outline-offset: 2px;
    }

    /* Hero */
    .hero {
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        background: radial-gradient(circle at 20% 30%, rgba(139,90,43,0.06), transparent 45%),
                    radial-gradient(circle at 85% 70%, rgba(217,140,95,0.08), transparent 50%),
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

    .hero-content { position: relative; z-index: 2; }

    .fade-up {
        opacity: 0;
        animation: fadeUp 0.9s cubic-bezier(0.16,1,0.3,1) forwards;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    .float { animation: float 7s ease-in-out infinite; }
    .float-slow { animation: float 9s ease-in-out infinite; }

    @keyframes float {
        0%,100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-12px) rotate(0.2deg); }
    }

    /* Glass */
    .glass-panel {
        background: rgba(255,255,255,0.6);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid var(--glass-border);
        box-shadow: 0 20px 40px -12px rgba(0,0,0,0.08), 0 4px 18px rgba(0,0,0,0.02);
    }

    /* Buttons */
    .btn-primary {
        background: var(--earth);
        color: white;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.2,0.9,0.4,1);
        box-shadow: 0 6px 14px rgba(139,90,43,0.12);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .btn-primary:hover {
        background: var(--earth-deep);
        transform: translateY(-3px);
        box-shadow: 0 14px 24px rgba(92,58,30,0.18);
    }

    .btn-secondary {
        background: white;
        color: var(--earth);
        border: 1px solid rgba(139,90,43,0.2);
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-secondary:hover {
        background: var(--earth);
        color: white;
        transform: translateY(-2px);
    }

    .btn-ghost {
        background: transparent;
        border: 1px solid rgba(0,0,0,0.08);
        font-weight: 500;
        transition: all 0.25s;
    }
    .btn-ghost:hover {
        border-color: var(--earth);
        color: var(--earth);
        transform: translateY(-2px);
    }

    /* Typography */
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

    /* Cards */
    .destination-card, .insight-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.2,0.9,0.4,1);
    }
    .destination-card:hover, .insight-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 28px 32px -16px rgba(139,90,43,0.12);
        border-color: rgba(139,90,43,0.15);
    }

    /* Trust logos */
    .trust-logo {
        filter: grayscale(1) opacity(0.6);
        transition: filter 0.2s;
    }
    .trust-logo:hover { filter: grayscale(0) opacity(1); }

    /* History strip */
    .history-strip {
        background: linear-gradient(135deg, #1A0F00 0%, #2D1A0A 50%, #1A0F00 100%);
        position: relative;
        overflow: hidden;
    }
    .history-strip::before {
        content: 'AFRICA';
        position: absolute;
        right: -40px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 200px;
        font-weight: 900;
        color: rgba(139,90,43,0.06);
        letter-spacing: -10px;
        pointer-events: none;
    }

    /* Newsletter */
    .newsletter-section {
        background: linear-gradient(135deg, var(--earth) 0%, var(--earth-deep) 100%);
        position: relative;
        overflow: hidden;
    }
    .newsletter-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("https://grainy-gradients.vercel.app/noise.svg");
        opacity: 0.04;
    }

    /* Stats */
    .stat-card {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(139,90,43,0.1);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s;
    }
    .stat-card:hover {
        background: white;
        transform: translateY(-4px);
        box-shadow: 0 16px 24px rgba(139,90,43,0.08);
    }

    @media (max-width: 768px) {
        .section-title { font-size: 1.9rem; }
        .hero { min-height: auto; padding: 6rem 0 4rem; }
        .history-strip::before { font-size: 100px; }
    }
</style>
@endpush

@section('content')

{{-- ================= HERO ================= --}}
@include('pages.landing')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Swiper
    if (document.getElementById('heroSlider')) {
        const heroSwiper = new Swiper('#heroSlider', {
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            speed: 800,
            slidesPerView: 1,
            spaceBetween: 20,
            centeredSlides: true,
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            observer: true,
            observeParents: true,
        });
    }
});

// Newsletter handler
function handleNewsletter(e) {
    e.preventDefault();
    const email = e.target.querySelector('input[type="email"]').value;
    const btn = e.target.querySelector('button');
    btn.textContent = 'Subscribed ✓';
    btn.classList.add('bg-green-100', 'text-green-700');
    btn.disabled = true;
    // Wire up to your actual newsletter service here
    console.log('Newsletter signup:', email);
}
</script>
@endpush