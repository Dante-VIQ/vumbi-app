@extends('layouts.app')

@section('title', 'Vumbi Ventures - From overlooked places, we build remarkable solutions')
@section('description', 'Vumbi Ventures is a purpose-driven digital innovation company building practical technology
    solutions that empower individuals, strengthen businesses, and unlock opportunities for communities across Africa.')
@section('keywords', 'Vumbi Ventures, African innovation, tech startup, digital innovation, SkillDNA, Discover Africa,
    Nairobi, Accra')

@section('content')
    @include('pages.landing')
@endsection

@push('schema')
    @php
        $homepageSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => url('/') . '#webpage',
            'name' => 'Vumbi Ventures - Home',
            'description' =>
                'A purpose-driven digital innovation company building practical technology solutions that empower individuals, strengthen businesses, and unlock opportunities for communities across Africa.',
            'url' => url('/'),
            'mainEntity' => [
                '@id' => url('/') . '#organization',
            ],
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => url('/'),
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
{!! json_encode($homepageSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@push('styles')
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        .dust-bg {
            background: radial-gradient(circle at 20% 50%, rgba(199, 181, 166, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 90, 43, 0.1) 0%, transparent 50%);
        }
    </style>
@endpush

@push('scripts')
<script>
    (function() {
        const track = document.getElementById('heroSliderTrack');
        if (!track) return;

        const slides = track.children;
        const slideCount = slides.length;
        if (slideCount <= 1) return;

        let currentIndex = 0;
        let autoInterval = null;
        const autoDelay = 5000;

        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');
        const dots = document.querySelectorAll('.hero-dot');

        function updateSlider() {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            dots.forEach((dot, idx) => {
                if (idx === currentIndex) {
                    dot.classList.add('bg-white', 'w-4');
                    dot.classList.remove('bg-white/50', 'w-2');
                } else {
                    dot.classList.remove('bg-white', 'w-4');
                    dot.classList.add('bg-white/50', 'w-2');
                }
            });
        }

        function goToSlide(index) {
            if (index < 0) index = slideCount - 1;
            if (index >= slideCount) index = 0;
            currentIndex = index;
            updateSlider();
            resetAutoPlay();
        }

        function nextSlide() { goToSlide(currentIndex + 1); }
        function prevSlide() { goToSlide(currentIndex - 1); }

        function startAutoPlay() {
            if (autoInterval) clearInterval(autoInterval);
            autoInterval = setInterval(nextSlide, autoDelay);
        }

        function resetAutoPlay() {
            clearInterval(autoInterval);
            startAutoPlay();
        }

        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        dots.forEach(dot => {
            dot.addEventListener('click', (e) => {
                const idx = parseInt(e.currentTarget.getAttribute('data-slide-index'));
                if (!isNaN(idx)) goToSlide(idx);
            });
        });

        const container = document.querySelector('.relative.h-full.rounded-3xl');
        if (container) {
            container.addEventListener('mouseenter', () => clearInterval(autoInterval));
            container.addEventListener('mouseleave', startAutoPlay);
        }

        startAutoPlay();
        window.addEventListener('resize', () => updateSlider());
    })();
</script>
@endpush