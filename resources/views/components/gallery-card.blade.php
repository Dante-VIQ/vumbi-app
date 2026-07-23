<section class="hero relative min-h-[100dvh] flex items-center overflow-hidden bg-[#F8F5F2]">

    <!-- Subtle Grain / Texture Overlay -->
    <div
        class="grain absolute inset-0 pointer-events-none bg-[radial-gradient(#8B5A2B_0.8px,transparent_1px)] [background-size:40px_40px] opacity-10">
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-15 lg:pt-12">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 xl:gap-20 items-center">

            <!-- Left Content -->
            <div class="space-y-6 lg:space-y-8 text-center lg:text-left">
                <div
                    class="inline-flex items-center gap-2 text-sm font-medium tracking-widest px-5 py-2.5 rounded-3xl bg-white shadow-sm border border-[#8B5A2B]/20 mx-auto lg:mx-0">
                    <span class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#8B5A2B] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-[#8B5A2B]"></span>
                    </span>
                    <span class="text-[#2E241B]">Vumbi Ventures • East Africa</span>
                </div>

                <h1
                    class="text-5xl sm:text-6xl lg:text-7xl xl:text-[4.2rem] leading-[1.05] font-semibold tracking-tighter text-[#1A1A1A]">
                    Discover Africa.<br>
                    <span class="text-[#8B5A2B] relative">
                        Before You Travel.
                        <span class="absolute -bottom-2 left-0 h-[3px] w-full bg-[#D98C5F]/40 rounded"></span>
                    </span>
                </h1>

                <p class="max-w-lg mx-auto lg:mx-0 text-lg text-gray-600 leading-relaxed">
                    Real stories, live prices, hidden gems, and authentic experiences from Kenya to Cape Town.
                </p>

                <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                    <a href="{{ url('/discover') }}"
                        class="group px-8 py-4 bg-[#8B5A2B] hover:bg-[#6B3E1A] text-white rounded-2xl font-medium flex items-center gap-3 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Start Exploring
                        <span class="group-hover:rotate-45 transition-transform">→</span>
                    </a>

                    <a href="#trending"
                        class="px-8 py-4 border border-gray-300 hover:border-gray-400 bg-white rounded-2xl font-medium transition-all">
                        See Trending Destinations
                    </a>
                </div>

                <!-- Trust Stats -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-x-10 gap-y-6 pt-6">
                    <div>
                        <div class="text-4xl font-bold text-[#8B5A2B]">54</div>
                        <div class="text-sm uppercase tracking-widest text-gray-500">African Countries</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-[#8B5A2B]">{{ \App\Models\Blog::count() }}+</div>
                        <div class="text-sm uppercase tracking-widest text-gray-500">Stories Published</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-[#8B5A2B]">3</div>
                        <div class="text-sm uppercase tracking-widest text-gray-500">Booking Partners</div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Luxurious Slider -->
            <div class="relative" id="hero-slider">
                <div class="overflow-hidden rounded-3xl shadow-2xl" id="slides-container">
                    <div class="flex transition-transform duration-700 ease-out" id="slider-track">

                        {{-- @php
                            $headerMedia = App\Models\HeaderMedia::latest()->take(10)->get();
                        @endphp

                        @if($headerMedia->isNotEmpty())
                            @foreach($headerMedia as $media)
                                <div class="w-full flex-shrink-0 px-3" data-slide>
                                    <div class="bg-white rounded-3xl overflow-hidden">
                                        <img src="{{ asset($media->media_path) }}" alt="{{ $media->title }}"
                                            class="w-full aspect-[4/3] lg:aspect-[5/4] object-cover" loading="eager">

                                    </div>
                                </div>
                            @endforeach
                        @else
                            
                            <div class="w-full flex-shrink-0 px-3">
                                <div
                                    class="bg-gradient-to-br from-[#8B5A2B] to-[#D98C5F] rounded-3xl aspect-[5/4] flex items-center justify-center text-white text-center p-10">
                                    <div>
                                        <h2 class="text-3xl font-bold">Beautiful Africa Awaits</h2>
                                        <p class="mt-3 opacity-90">High-quality destinations coming soon</p>
                                    </div>
                                </div>
                            </div>
                        @endif --}}
                    </div>
                </div>

                <!-- Navigation -->
                <button id="prev-btn"
                    class="absolute -left-4 top-1/2 -translate-y-1/2 bg-white shadow-xl hover:bg-amber-50 w-12 h-12 rounded-2xl flex items-center justify-center text-2xl text-gray-700 transition-all active:scale-95 z-20">
                    ←
                </button>

                <button id="next-btn"
                    class="absolute -right-4 top-1/2 -translate-y-1/2 bg-white shadow-xl hover:bg-amber-50 w-12 h-12 rounded-2xl flex items-center justify-center text-2xl text-gray-700 transition-all active:scale-95 z-20">
                    →
                </button>

                <!-- Dots -->
                <div class="flex justify-center gap-3 mt-8" id="dots-container"></div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 hidden lg:flex flex-col items-center text-xs tracking-widest text-gray-400">
        <span>SCROLL TO EXPLORE</span>
        <div class="w-px h-12 bg-gradient-to-b from-transparent via-gray-300 to-transparent mt-3"></div>
    </div>
</section>