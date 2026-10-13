import './bootstrap';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

import monthSelectPlugin from 'flatpickr/dist/plugins/monthSelect';
import 'flatpickr/dist/plugins/monthSelect/style.css';

window.Alpine = Alpine;
window.flatpickr = flatpickr;

document.addEventListener('alpine:init', () => {
    Alpine.data('travelMonthPicker', () => ({
        picker: null,

        init() {
            this.picker = flatpickr(this.$refs.input, {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: 'Y-m',      // value submitted to Laravel
                        altFormat: 'F Y',       // what the user sees: "April 2026"
                    }),
                ],
                minDate: 'today',               // no past months
                maxDate: new Date(new Date().setFullYear(new Date().getFullYear() + 2)),
                disableMobile: true,            // use Flatpickr even on phones — consistent UI
                allowInput: false,              // read-only, forces the picker
                defaultDate: this.$refs.input.value || null,
            });

            // Sync back to Alpine when user picks
            this.picker.config.onChange.push((_, str) => {
                this.$refs.input.value = str;
            });
        },

        reset() {
            if (this.picker) {
                this.picker.clear();
                this.$refs.input.value = '';
            }
        },
    }));

    Alpine.start();
});

document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('slider-track');
    const slides = document.querySelectorAll('#slider-track > div');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const dotsContainer = document.getElementById('dots-container');

    let currentIndex = 0;
    const slideCount = slides.length;

    if (slideCount === 0) return;

    // Create Dots
    function createDots() {
        dotsContainer.innerHTML = '';
        for (let i = 0; i < slideCount; i++) {
            const dot = document.createElement('button');
            dot.className = `w-3 h-3 rounded-full transition-all ${i === 0 ? 'bg-[#8B5A2B] scale-110' : 'bg-gray-300'}`;
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }
    }

    // Go to specific slide
    function goToSlide(index) {
        currentIndex = index;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        updateDots();
    }

    // Update active dot
    function updateDots() {
        const dots = dotsContainer.querySelectorAll('button');
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-[#8B5A2B]', i === currentIndex);
            dot.classList.toggle('scale-110', i === currentIndex);
            dot.classList.toggle('bg-gray-300', i !== currentIndex);
        });
    }

    // Next & Previous
    function nextSlide() {
        currentIndex = (currentIndex + 1) % slideCount;
        goToSlide(currentIndex);
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + slideCount) % slideCount;
        goToSlide(currentIndex);
    }

    // Event Listeners
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    // Auto-play (optional but recommended)
    let autoplayInterval;
    function startAutoplay() {
        autoplayInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }

    function stopAutoplay() {
        clearInterval(autoplayInterval);
    }

    // Touch swipe support (basic)
    let touchStartX = 0;
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        stopAutoplay();
    });

    track.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 50) nextSlide();
        if (touchEndX - touchStartX > 50) prevSlide();
        startAutoplay();
    });

    // Pause autoplay on hover
    const sliderContainer = document.getElementById('hero-slider');
    sliderContainer.addEventListener('mouseenter', stopAutoplay);
    sliderContainer.addEventListener('mouseleave', startAutoplay);

    // Initialize
    createDots();
    startAutoplay();
});
