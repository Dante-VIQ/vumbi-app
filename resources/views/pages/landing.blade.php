<div x-data="{ inquiryModal: false, selectedDestination: '' }">

    {{-- ================= 1. HERO SECTION (PARTNER & AGGREGATOR MODEL) ================= --}}
    <section x-data="{
                currentSlide: 0,
                totalSlides: {{ App\Models\HeaderMedia::count() > 0 ? App\Models\HeaderMedia::take(10)->count() : 1 }},
                next() { this.currentSlide = (this.currentSlide + 1) % this.totalSlides },
                prev() { this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides },
                timer: null
             }"
             x-init="timer = setInterval(() => next(), 5000)"
             @mouseenter="clearInterval(timer)"
             @mouseleave="timer = setInterval(() => next(), 5000)"
             class="hero relative min-h-[90vh] lg:min-h-[92vh] flex items-center overflow-hidden bg-[#F8F5F2]">

        <!-- Subtle Grain / Texture Overlay -->
        <div class="grain absolute inset-0 pointer-events-none bg-[radial-gradient(#8B5A2B_0.8px,transparent_1px)] [background-size:40px_40px] opacity-10"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-12 lg:py-16">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 xl:gap-20 items-center">

                <!-- Left Content Column -->
                <div class="space-y-6 lg:space-y-8 text-center lg:text-left">
                    
                    {{-- Live Partner Network Badge --}}
                    <div class="inline-flex items-center gap-2.5 text-xs sm:text-sm font-bold tracking-wider px-4 py-2 rounded-full bg-white shadow-sm border border-[#8B5A2B]/20 text-[#2E241B]">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-600"></span>
                        </span>
                        <span>VUMBI VENTURES • EAST AFRICA TRAVEL & TRANSPORT AGGREGATOR</span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl lg:text-7xl xl:text-[4.1rem] leading-[1.08] font-black tracking-tight text-[#1A1A1A]">
                        Discover Africa.<br>
                        <span class="text-[#8B5A2B] relative inline-block">
                            Book Verified Partner Deals.
                            <span class="absolute -bottom-1 left-0 h-[3px] w-full bg-[#D98C5F]/40 rounded"></span>
                        </span>
                    </h1>

                    <p class="max-w-lg mx-auto lg:mx-0 text-base sm:text-lg text-gray-600 leading-relaxed font-normal">
                        We connect you directly with vetted 4x4 transport operators, safari guides, and exclusive travel deals across Kenya and East Africa.
                    </p>

                    {{-- Direct Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start">
                        <button @click="inquiryModal = true; selectedDestination = 'Hero Section Inquiry'"
                                class="px-8 py-4 bg-[#8B5A2B] hover:bg-[#6B3E1A] text-white rounded-2xl font-bold flex items-center justify-center gap-3 transition-all duration-300 shadow-xl shadow-[#8B5A2B]/20 hover:scale-[1.02]">
                            <i class="fas fa-paper-plane text-sm"></i>
                            Get Custom Partner Quote
                        </button>

                        <a href="https://wa.me/254745781236?text=Hi%20Vumbi%20Ventures,%20I%20am%20inquiring%20about%20safari%20partner%20deals" 
                           target="_blank"
                           class="px-6 py-4 border border-emerald-600/30 bg-emerald-50 hover:bg-emerald-100 text-emerald-900 rounded-2xl font-bold transition-all flex items-center justify-center gap-2">
                            <i class="fab fa-whatsapp text-lg text-emerald-600"></i>
                            WhatsApp Chat
                        </a>
                    </div>

                    {{-- Partner Trust Metrics --}}
                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-black/5 max-w-lg mx-auto lg:mx-0">
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-[#8B5A2B]">Vetted</div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mt-0.5">Transport Partners</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-[#8B5A2B]">Best</div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mt-0.5">Affiliate Rates</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-[#8B5A2B]">100%</div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mt-0.5">Curated Trips</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Alpine Powered Slider -->
                <div class="relative w-full">
                    <div class="overflow-hidden rounded-3xl shadow-2xl bg-slate-100 border border-black/5">
                        <div class="flex transition-transform duration-700 ease-out"
                             :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">

                            @php
                                $headerMedia = App\Models\HeaderMedia::latest()->take(10)->get();
                            @endphp

                            @if($headerMedia->isNotEmpty())
                                @foreach($headerMedia as $media)
                                    <div class="w-full flex-shrink-0 relative">
                                        <img src="{{ asset($media->media_path) }}" 
                                             alt="{{ $media->title ?? 'Vumbi Ventures Partner Destinations' }}"
                                             class="w-full aspect-[4/3] lg:aspect-[5/4] object-cover" 
                                             loading="eager">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-6">
                                            <p class="text-white text-sm font-bold drop-shadow-md">
                                                {{ $media->title ?? 'Verified Safari & Tour Partners' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="w-full flex-shrink-0 relative">
                                    <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80" 
                                         alt="Maasai Mara Game Reserve"
                                         class="w-full aspect-[4/3] lg:aspect-[5/4] object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end p-6">
                                        <div>
                                            <span class="text-amber-400 text-xs font-bold uppercase tracking-widest">Kenya Partner Network</span>
                                            <p class="text-white text-base font-bold">Curated 4x4 Land Cruiser & Tour Van Bookings</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Navigation Controls -->
                    <button @click="prev()"
                            class="absolute -left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white shadow-xl text-slate-800 w-11 h-11 rounded-2xl flex items-center justify-center text-lg font-bold transition active:scale-95 z-20 backdrop-blur-sm border border-black/5"
                            aria-label="Previous Slide">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <button @click="next()"
                            class="absolute -right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white shadow-xl text-slate-800 w-11 h-11 rounded-2xl flex items-center justify-center text-lg font-bold transition active:scale-95 z-20 backdrop-blur-sm border border-black/5"
                            aria-label="Next Slide">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <!-- Dynamic Slide Dots -->
                    <div class="flex justify-center gap-2 mt-4">
                        <template x-for="i in totalSlides" :key="i">
                            <button @click="currentSlide = i - 1"
                                    :class="currentSlide === (i - 1) ? 'bg-[#8B5A2B] w-6' : 'bg-slate-300 w-2'"
                                    class="h-2 rounded-full transition-all duration-300"
                                    :aria-label="'Go to slide ' + i"></button>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= 2. LIVE NETWORK STATUS BAR ================= --}}
    <section class="py-6 bg-white border-y border-black/5">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs md:text-sm font-bold text-slate-800 uppercase tracking-wider">
                        Partner Availability: 4x4 Land Cruisers & Safari Vans Active
                    </span>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <i class="fas fa-shield-alt text-[#8B5A2B]"></i>
                    Verified Regional Operators & Affiliate Partners
                </div>
            </div>
        </div>
    </section>

    {{-- ================= 3. POPULAR CURATED PACKAGES & DEALS ================= --}}
    <section class="container mx-auto px-6 py-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold text-[#8B5A2B] uppercase tracking-widest">Featured Expeditions</span>
                <h2 class="section-title mt-1">Curated East African Safaris</h2>
                <p class="section-sub">Hand-picked itineraries fulfilled through our verified transport & tour partners.</p>
            </div>
            <a href="/tours" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 w-fit">
                Explore All Deals
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        @php
            $eastAfricanDestinations = [
                [
                    'name'        => 'Maasai Mara National Reserve',
                    'location'    => 'Kenya',
                    'description' => 'Great Migration game drives, big cat tracking, and luxury partner tented camps.',
                    'price'       => 'From $650 / person',
                    'image'       => '/images/maasai-mara.jpg',
                    'tag'         => 'Migration Season'
                ],
                [
                    'name'        => 'Lake Nakuru & Rift Valley',
                    'location'    => 'Kenya',
                    'description' => 'Rhino sanctuaries, flamingo lakes, and 1 to 3 day safari getaways with local drivers.',
                    'price'       => 'From $280 / person',
                    'image'       => '/images/nakuru.jpg',
                    'tag'         => 'Local Favorite'
                ],
                [
                    'name'        => 'Amboseli Kilimanjaro View',
                    'location'    => 'Kenya',
                    'description' => 'Massive elephant herds set against the backdrop of Mount Kilimanjaro.',
                    'price'       => 'From $520 / person',
                    'image'       => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=800&q=80',
                    'tag'         => 'Scenic Safari'
                ],
                [
                    'name'        => 'Zanzibar Beach & Spice Tour',
                    'location'    => 'Tanzania',
                    'description' => 'Combine mainland game drives with pristine white sand beach relaxation.',
                    'price'       => 'From $890 / person',
                    'image'       => '/images/zanzibar-beach.jpg',
                    'tag'         => 'Beach & Wildlife'
                ],
                [
                    'name'        => 'Diani Beach Getaway',
                    'location'    => 'Kenya Coast',
                    'description' => 'Turquoise Indian Ocean waters, water sports, and luxury beach resort affiliate deals.',
                    'price'       => 'From $350 / person',
                    'image'       => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'tag'         => 'Coastal Escape'
                ],
                [
                    'name'        => 'Serengeti & Ngorongoro Crater',
                    'location'    => 'Tanzania',
                    'description' => 'Unmatched predator density and the world’s largest intact volcanic caldera.',
                    'price'       => 'From $950 / person',
                    'image'       => '/images/arusha.jpg',
                    'tag'         => 'Bucket List'
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($eastAfricanDestinations as $dest)
                <div class="destination-card group block overflow-hidden flex flex-col justify-between bg-white rounded-2xl border border-black/5 shadow-sm hover:shadow-md transition">
                    <div>
                        <div class="aspect-[4/3] overflow-hidden relative">
                            <img src="{{ $dest['image'] }}"
                                 alt="{{ $dest['name'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                 loading="lazy"
                                 onerror="this.src='https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=800&q=80'">
                            <span class="absolute top-4 left-4 text-xs font-bold bg-[#8B5A2B] text-white px-3 py-1 rounded-full shadow-md">
                                {{ $dest['tag'] }}
                            </span>
                            <span class="absolute bottom-4 right-4 text-xs font-extrabold bg-slate-900/90 text-amber-400 px-3 py-1.5 rounded-lg backdrop-blur-md">
                                {{ $dest['price'] }}
                            </span>
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-xl text-[#1A1A1A] group-hover:text-[#8B5A2B] transition">
                                {{ $dest['name'] }}
                            </h3>
                            <p class="text-[#8B5A2B] text-xs font-semibold mt-1"><i class="fas fa-map-marker-alt"></i> {{ $dest['location'] }}</p>
                            <p class="text-sm text-[#5C5C5C] mt-3 leading-relaxed">
                                {{ $dest['description'] }}
                            </p>
                        </div>
                    </div>

                    {{-- High-Converting Action Row --}}
                    <div class="p-6 pt-0 flex items-center gap-2">
                        <button @click="inquiryModal = true; selectedDestination = '{{ $dest['name'] }}'"
                                class="w-full py-2.5 px-4 bg-[#8B5A2B] hover:bg-[#5C3A1E] text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Request Partner Quote
                        </button>
                        <a href="https://wa.me/254745781236?text=Hi%20Vumbi%20Ventures,%20I%20am%20interested%20in%20a%20partner%20safari%20to%20{{ urlencode($dest['name']) }}"
                           target="_blank"
                           class="py-2.5 px-3 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded-xl text-xs font-bold transition flex items-center justify-center"
                           title="Chat on WhatsApp">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================= 4. VALUE PROPOSITION ================= --}}
    <section class="py-16 bg-white border-y border-black/5">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="section-title">Why Book Through Vumbi Ventures</h2>
                <p class="section-sub">We bridge the gap between travelers, local vehicle operators, and top travel platforms.</p>
            </div>
            <div class="grid md:grid-cols-4 gap-6 text-center">
                <div class="p-6 rounded-2xl bg-[#FCFAF7] border border-amber-900/5">
                    <div class="w-12 h-12 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#8B5A2B] text-xl">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="font-bold text-[#1A1A1A]">Vetted Fleet Partners</h3>
                    <p class="text-xs text-[#5C5C5C] mt-2 leading-relaxed">We match your group with licensed local transport partners who own inspected 4x4 Land Cruisers & vans.</p>
                </div>
                <div class="p-6 rounded-2xl bg-[#FCFAF7] border border-amber-900/5">
                    <div class="w-12 h-12 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#8B5A2B] text-xl">
                        <i class="fas fa-[#8B5A2B] fa-tags"></i>
                    </div>
                    <h3 class="font-bold text-[#1A1A1A]">Curated Affiliate Deals</h3>
                    <p class="text-xs text-[#5C5C5C] mt-2 leading-relaxed">Access direct partner pricing and exclusive travel discounts via our global travel affiliate network.</p>
                </div>
                <div class="p-6 rounded-2xl bg-[#FCFAF7] border border-amber-900/5">
                    <div class="w-12 h-12 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#8B5A2B] text-xl">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3 class="font-bold text-[#1A1A1A]">Local Safari Guides</h3>
                    <p class="text-xs text-[#5C5C5C] mt-2 leading-relaxed">Every journey is paired with professional, certified East African driver-guides for game tracking.</p>
                </div>
                <div class="p-6 rounded-2xl bg-[#FCFAF7] border border-amber-900/5">
                    <div class="w-12 h-12 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#8B5A2B] text-xl">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="font-bold text-[#1A1A1A]">Dedicated Concierge</h3>
                    <p class="text-xs text-[#5C5C5C] mt-2 leading-relaxed">Continuous field support and custom itinerary planning directly from our Nakuru headquarters.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= 5. BLOG / FIELD NOTES ================= --}}
    @if(isset($blogs) && $blogs->count())
    <section class="py-16 bg-[#FCFAF7]">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <span class="text-xs font-bold text-[#8B5A2B] uppercase tracking-widest">Travel Insights</span>
                    <h2 class="section-title">Latest Field Notes</h2>
                </div>
                <a href="/blog" class="text-sm font-bold text-[#8B5A2B] hover:underline">Read All Articles →</a>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($blogs->take(3) as $blog)
                    <article class="insight-card p-5 bg-white rounded-2xl shadow-sm border border-black/5">
                        <h3 class="font-bold text-lg text-slate-900 hover:text-[#8B5A2B]">
                            <a href="{{ route('blog.show', $blog->id) }}">{{ $blog->title }}</a>
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                            {{ Str::limit(strip_tags($blog->description ?? $blog->excerpt ?? ''), 100) }}
                        </p>
                        <a href="{{ route('blog.show', $blog->id) }}" class="inline-block mt-4 text-xs font-bold text-[#8B5A2B]">
                            Read Field Note →
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ================= 6. FINAL CONVERSION CTA ================= --}}
    <section class="py-20 bg-slate-950 text-white text-center relative overflow-hidden">
        <div class="container mx-auto px-6 relative z-10 max-w-3xl">
            <span class="inline-block text-xs uppercase tracking-widest text-amber-400 font-bold mb-3">
                Plan Your Journey
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight">
                Let Us Match You with the Best Local Partners
            </h2>
            <p class="text-slate-400 text-base md:text-lg mt-4 max-w-xl mx-auto">
                Get a customized safari itinerary or vehicle transfer recommendation within 24 hours.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                <button @click="inquiryModal = true; selectedDestination = 'Custom General Inquiry'"
                        class="w-full sm:w-auto px-8 py-4 bg-[#8B5A2B] hover:bg-[#A87A4D] text-white font-bold rounded-xl transition shadow-lg shadow-[#8B5A2B]/30">
                    <i class="fas fa-calendar-alt mr-2"></i> Request Custom Itinerary Quote
                </button>
                <a href="https://wa.me/254745781236?text=Hi%20Vumbi%20Ventures,%20I%20would%20like%20to%20plan%20a%20safari" 
                   target="_blank"
                   class="w-full sm:w-auto px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp text-lg"></i> Chat Instantly on WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- ================= 7. RAPID INQUIRY MODAL ================= --}}
    <div x-show="inquiryModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" 
         x-cloak>
        
        <div @click.away="inquiryModal = false" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 md:p-8 relative">
            <button @click="inquiryModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 text-xl font-bold">
                &times;
            </button>

            <div class="mb-6">
                <span class="text-xs font-bold text-[#8B5A2B] uppercase tracking-wider">Fast Response Guaranteed</span>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">Partner Tour & Safari Quote</h3>
                <p class="text-xs text-slate-500">Inquiring for: <span class="font-bold text-slate-800" x-text="selectedDestination"></span></p>
            </div>

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="destination" x-model="selectedDestination">
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Your Full Name</label>
                    <input type="text" name="name" required class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#8B5A2B] outline-none" placeholder="e.g. Jane Doe">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                        <input type="email" name="email" required class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#8B5A2B] outline-none" placeholder="jane@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">WhatsApp / Phone</label>
                        <input type="tel" name="phone" required class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#8B5A2B] outline-none" placeholder="+254 7...">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Travel Month</label>
                        <input type="month" name="travel_month" required class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#8B5A2B] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Travelers Count</label>
                        <input type="number" min="1" name="travelers" value="2" required class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#8B5A2B] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Special Requests / Preferences</label>
                    <textarea name="notes" rows="2" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#8B5A2B] outline-none" placeholder="e.g. Need 4x4 Land Cruiser transfer from Nakuru, luxury lodge preferences..."></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 bg-[#8B5A2B] hover:bg-[#5C3A1E] text-white font-bold rounded-xl transition shadow-md">
                    Submit Safari Quote Request
                </button>
            </form>
        </div>
    </div>

</div>