<section class="hero relative flex items-center overflow-hidden py-12 md:py-16 lg:py-20">
    <!-- Background grain texture (keep your existing grain div) -->
    <div class="grain absolute inset-0 pointer-events-none"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-8 md:gap-12 lg:gap-16 items-center">
            
            <!-- Left Content Column -->
            <div class="space-y-5 sm:space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 text-xs sm:text-sm px-4 py-2 sm:px-5 sm:py-2.5 rounded-full bg-white/80 backdrop-blur-sm shadow-sm border border-white/40 mx-auto lg:mx-0">
                    <span class="w-2 h-2 rounded-full bg-[#8B5A2B]"></span>
                    <span class="text-[#2E241B] font-medium">Curated by Vumbi Ventures</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-semibold leading-[1.15] sm:leading-[1.1] tracking-tight text-[#1A1A1A]">
                    Discover Africa.
                    <br>
                    <span class="text-[#8B5A2B] relative inline-block">
                        Book extraordinary.
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-[#D98C5F]/30 rounded-full"></span>
                    </span>
                </h1>
                
                <p class="text-gray-600 text-base sm:text-lg lg:text-xl max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Handpicked hotels, safaris, and cultural experiences — powered by our network of trusted African travel partners.
                </p>
                
                <div class="flex flex-wrap gap-3 sm:gap-4 justify-center lg:justify-start pt-2">
                    <a href="{{ url('/discover') }}" 
                       class="btn-primary px-5 sm:px-7 py-3 rounded-xl text-sm sm:text-base inline-flex items-center gap-2 bg-[#8B5A2B] text-white hover:bg-[#6B3E1A] transition-all shadow-md hover:shadow-lg">
                        Find Your Destination
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 sm:w-[18px] sm:h-[18px]">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2a15 15 0 0 0 0 20 15 15 0 0 0 0-20z"/>
                            <path d="M2 12h20"/>
                        </svg>
                    </a>
                    <a href="#services" 
                       class="btn-secondary px-5 sm:px-7 py-3 rounded-xl text-sm sm:text-base inline-flex items-center gap-2 bg-white/90 border border-gray-300 text-gray-800 hover:bg-gray-100 transition-all">
                        Web Development Services
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 sm:w-[18px] sm:h-[18px]">
                            <polyline points="16 18 22 12 16 6"></polyline>
                            <polyline points="8 6 2 12 8 18"></polyline>
                        </svg>
                    </a>
                </div>
                
                <div class="flex flex-wrap justify-center lg:justify-start gap-6 sm:gap-8 lg:gap-12 pt-6 sm:pt-8">
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold text-[#8B5A2B] stat-number">200+</div>
                        <div class="text-xs sm:text-sm uppercase tracking-wider font-medium text-gray-500">Destinations</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold text-[#8B5A2B] stat-number">1k+</div>
                        <div class="text-xs sm:text-sm uppercase tracking-wider font-medium text-gray-500">Travelers</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold text-[#8B5A2B] stat-number">50+</div>
                        <div class="text-xs sm:text-sm uppercase tracking-wider font-medium text-gray-500">Partners</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Responsive Slider -->
            @php
                $headerMedia = App\Models\HeaderMedia::latest()->get();
            @endphp
            
            <div class="relative w-full max-w-lg mx-auto lg:mx-0 mt-8 lg:mt-0" id="hero-slider">
                <!-- Slides Container -->
                <div class="overflow-hidden rounded-2xl sm:rounded-[2rem] md:rounded-[2.5rem] shadow-xl sm:shadow-2xl" id="slides-container">
                    <div class="flex transition-transform duration-500 ease-out will-change-transform" id="slider-track">
                        
                        @if (!empty($headerMedia) && count($headerMedia) > 0)
                            @foreach ($headerMedia as $media)
                                <div class="w-full flex-shrink-0 px-2 sm:px-4 md:px-5 py-3 sm:py-4 md:py-5" data-slide>
                                    <div class="bg-white rounded-xl sm:rounded-2xl md:rounded-3xl overflow-hidden shadow-md transition-all hover:shadow-lg">
                                        <img src="{{ asset($media->media_path) }}" 
                                             alt="{{ $media->title }}"
                                             class="w-full h-64 sm:h-80 md:h-96 lg:h-[500px] object-cover rounded-2xl shadow-2xl"
                                             loading="eager">
                                        
                                        <div class="p-4 sm:p-5 md:p-6 text-center">
                                            <p class="font-semibold text-[#1A1A1A] text-base sm:text-lg leading-tight line-clamp-2">
                                                {{ $media->title }}
                                            </p>
                                            <a href="/discover?search={{ urlencode($media->title) }}" 
                                               class="inline-block mt-3 sm:mt-4 text-xs sm:text-sm font-medium text-[#8B5A2B] hover:text-[#6B3E1A] hover:underline transition-colors">
                                                View deal →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="w-full flex-shrink-0 px-2 sm:px-5 py-3 sm:py-5">
                                <div class="w-full h-[260px] sm:h-[320px] md:h-96 bg-gradient-to-br from-blue-900 to-gray-800 rounded-xl sm:rounded-2xl md:rounded-3xl flex items-center justify-center text-white">
                                    <div class="text-center px-4">
                                        <h2 class="text-xl sm:text-2xl font-bold">Gallery Coming Soon</h2>
                                        <p class="text-sm sm:text-base mt-2">Check back for amazing deals</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Dots Container -->
                <div class="flex justify-center gap-2 sm:gap-3 mt-4 sm:mt-6 md:mt-8" id="dots-container"></div>

                <!-- Navigation Arrows - absolutely positioned inside slider container -->
                <button id="prev-btn"
                    class="absolute left-0 sm:-left-3 md:-left-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm hover:bg-white shadow-md hover:shadow-lg w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg sm:rounded-xl md:rounded-2xl flex items-center justify-center text-base sm:text-lg md:text-xl text-gray-700 z-10 transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/50">
                    ←
                </button>
                
                <button id="next-btn"
                    class="absolute right-0 sm:-right-3 md:-right-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm hover:bg-white shadow-md hover:shadow-lg w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg sm:rounded-xl md:rounded-2xl flex items-center justify-center text-base sm:text-lg md:text-xl text-gray-700 z-10 transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/50">
                    →
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Additional Styles for smooth animations and stat numbers -->
<style>
    /* Fade-up animations (if you want them, else remove) */
    .fade-up {
        animation: fadeUp 0.6s ease-out forwards;
        opacity: 0;
    }
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    
    /* Stat number styling */
    .stat-number {
        font-feature-settings: "tnum";
        font-variant-numeric: tabular-nums;
    }
    
    /* Ensure line clamp works for titles */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Improved touch targets on mobile */
    @media (max-width: 640px) {
        #prev-btn, #next-btn {
            width: 36px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.95);
        }
        #dots-container button {
            min-width: 10px;
            min-height: 10px;
        }
    }
</style>

<!-- Slider JavaScript (same as before, fully functional) -->
<script>
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSlider);
    } else {
        initSlider();
    }

    function initSlider() {
        const sliderContainer = document.getElementById('hero-slider');
        if (!sliderContainer) return;
        const track = document.getElementById('slider-track');
        const slidesContainer = document.getElementById('slides-container');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const dotsContainer = document.getElementById('dots-container');
        if (!track || !slidesContainer) return;
        
        let slides = Array.from(document.querySelectorAll('[data-slide]'));
        const totalSlides = slides.length;
        
        if (totalSlides <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
            if (dotsContainer) dotsContainer.style.display = 'none';
            return;
        }
        
        let currentIndex = 0;
        let slideWidth = 0;
        let isTransitioning = false;
        let touchStartX = 0, touchEndX = 0;
        let resizeTimeout;
        
        function generateDots() {
            if (!dotsContainer) return;
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('button');
                dot.setAttribute('data-index', i);
                dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
                dot.className = `w-2 h-2 sm:w-2.5 sm:h-2.5 md:w-3 md:h-3 rounded-full transition-all duration-300 ${
                    i === currentIndex ? 'bg-[#8B5A2B] scale-110 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'
                }`;
                dot.addEventListener('click', () => {
                    if (!isTransitioning && i !== currentIndex) goToSlide(i);
                });
                dotsContainer.appendChild(dot);
            }
        }
        
        function updateDots() {
            if (!dotsContainer) return;
            const dots = dotsContainer.querySelectorAll('button');
            dots.forEach((dot, idx) => {
                if (idx === currentIndex) {
                    dot.classList.remove('bg-gray-300', 'hover:bg-gray-400');
                    dot.classList.add('bg-[#8B5A2B]', 'scale-110', 'shadow-sm');
                } else {
                    dot.classList.remove('bg-[#8B5A2B]', 'scale-110', 'shadow-sm');
                    dot.classList.add('bg-gray-300', 'hover:bg-gray-400');
                }
            });
        }
        
        function updateSliderPosition(animate = true) {
            if (!track || slideWidth <= 0) return;
            track.style.transition = animate ? 'transform 0.5s ease-out' : 'none';
            track.style.transform = `translateX(${-currentIndex * slideWidth}px)`;
            if (!animate) void track.offsetHeight;
            updateDots();
        }
        
        function updateSlideWidth() {
            if (slides.length && slides[0]) return slides[0].getBoundingClientRect().width;
            return slidesContainer ? slidesContainer.getBoundingClientRect().width : 0;
        }
        
        function recalcAndReposition() {
            const newWidth = updateSlideWidth();
            if (Math.abs(newWidth - slideWidth) > 1 || (slideWidth <= 0 && newWidth > 0)) {
                slideWidth = newWidth;
                if (currentIndex >= totalSlides) currentIndex = totalSlides - 1;
                if (currentIndex < 0) currentIndex = 0;
                updateSliderPosition(true);
            }
        }
        
        function goToSlide(index, animate = true) {
            if (isTransitioning) return;
            if (index >= totalSlides) index = 0;
            if (index < 0) index = totalSlides - 1;
            if (index === currentIndex) return;
            isTransitioning = true;
            currentIndex = index;
            updateSliderPosition(animate);
            setTimeout(() => { isTransitioning = false; }, 550);
        }
        
        function nextSlide() { if (!isTransitioning) goToSlide(currentIndex + 1); }
        function prevSlide() { if (!isTransitioning) goToSlide(currentIndex - 1); }
        
        // Touch events
        function handleTouchStart(e) { touchStartX = e.touches[0].clientX; }
        function handleTouchMove(e) {
            if (!touchStartX) return;
            touchEndX = e.touches[0].clientX;
            if (Math.abs(touchEndX - touchStartX) > 10) e.preventDefault();
        }
        function handleTouchEnd() {
            if (!touchStartX || !touchEndX) { touchStartX = 0; touchEndX = 0; return; }
            const delta = touchEndX - touchStartX;
            if (Math.abs(delta) > 50) delta > 0 ? prevSlide() : nextSlide();
            touchStartX = 0; touchEndX = 0;
        }
        
        function handleResize() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => { if (slides.length) recalcAndReposition(); }, 150);
        }
        
        function bindEvents() {
            if (prevBtn) prevBtn.addEventListener('click', prevSlide);
            if (nextBtn) nextBtn.addEventListener('click', nextSlide);
            if (track) {
                track.addEventListener('touchstart', handleTouchStart, { passive: false });
                track.addEventListener('touchmove', handleTouchMove, { passive: false });
                track.addEventListener('touchend', handleTouchEnd);
            }
            window.addEventListener('resize', handleResize);
        }
        
        function init() {
            generateDots();
            slideWidth = updateSlideWidth();
            if (slideWidth <= 0) {
                setTimeout(() => {
                    slideWidth = updateSlideWidth();
                    if (slideWidth > 0) { updateSliderPosition(false); bindEvents(); }
                }, 100);
            } else {
                updateSliderPosition(false);
                bindEvents();
            }
        }
        
        // Wait for images to load
        const images = track.querySelectorAll('img');
        let loaded = 0;
        if (images.length) {
            const onLoad = () => { if (++loaded === images.length) setTimeout(recalcAndReposition, 50); };
            images.forEach(img => {
                if (img.complete) onLoad();
                else { img.addEventListener('load', onLoad); img.addEventListener('error', onLoad); }
            });
        }
        init();
    }
})();
</script>