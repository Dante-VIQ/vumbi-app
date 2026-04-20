 <section class="hero flex items-center">
        <div class="grain"></div>
        <div class="container mx-auto px-6 hero-content">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-6 fade-up">
                    <div
                        class="inline-flex items-center gap-2 text-sm px-5 py-2.5 rounded-full bg-white/80 backdrop-blur-sm shadow-sm border border-white/40 fade-up delay-1">
                        <span class="w-2 h-2 rounded-full bg-[#8B5A2B]"></span>
                        <span class="text-[#2E241B] font-medium">Curated by Vumbi Ventures</span>
                    </div>
                    <h1
                        class="text-5xl lg:text-6xl xl:text-7xl font-semibold leading-[1.1] tracking-tight text-[#1A1A1A] fade-up delay-1">
                        Discover Africa.
                        <br>
                        <span class="text-[#8B5A2B] relative inline-block">
                            Book extraordinary.
                            <span class="absolute -bottom-1 left-0 w-full h-1 bg-[#D98C5F]/30 rounded-full"></span>
                        </span>
                    </h1>
                    <p class="text-[#5C5C5C] text-lg lg:text-xl max-w-xl fade-up delay-2 leading-relaxed">
                        Handpicked hotels, safaris, and cultural experiences — powered by our network of trusted African
                        travel partners.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-3 fade-up delay-2">
                        <a href="{{ url('/discover') }}"
                            class="btn-primary px-7 py-3.5 rounded-xl text-base inline-flex items-center gap-2">
                            Find Your Destination
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 2a15 15 0 0 0 0 20 15 15 0 0 0 0-20z" />
                                <path d="M2 12h20" />
                            </svg>
                        </a>
                        <a href="#services"
                            class="btn-secondary px-7 py-3.5 rounded-xl text-base inline-flex items-center gap-2">
                            Web Development Services
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="16 18 22 12 16 6"></polyline>
                                <polyline points="8 6 2 12 8 18"></polyline>
                            </svg>
                        </a>
                    </div>
                    <div class="flex gap-8 lg:gap-12 pt-8 text-sm text-[#5C5C5C] fade-up delay-3">
                        <div>
                            <div class="stat-number">200+</div>
                            <div class="uppercase tracking-wider text-xs font-medium opacity-70">Destinations</div>
                        </div>
                        <div>
                            <div class="stat-number">1k+</div>
                            <div class="uppercase tracking-wider text-xs font-medium opacity-70">Travelers</div>
                        </div>
                        <div>
                            <div class="stat-number">50+</div>
                            <div class="uppercase tracking-wider text-xs font-medium opacity-70">Partners</div>
                        </div>
                    </div>
                </div>
                <div class="relative flex justify-center lg:justify-end fade-up delay-2">
                    <div class="glass-panel rounded-[2.5rem] p-5 md:p-6 shadow-2xl float max-w-md w-full">
                        {{-- Replace with actual destination image or video --}}
                        <img src="{{ asset('images/hero-destination.jpg') }}" alt="Serengeti sunset with safari vehicle"
                            class="rounded-3xl w-full h-[380px] md:h-[420px] object-cover shadow-inner" loading="eager"
                            width="600" height="420">
                        <div class="mt-5 text-center px-2">
                            <p class="font-semibold text-[#1A1A1A] text-lg">Serengeti Migration Safari</p>
                            <p class="text-sm text-[#6B6B6B] mt-1 flex items-center justify-center gap-2">
                                <span>From $2,450 pp</span><span class="w-1 h-1 bg-[#8B5A2B] rounded-full"></span>
                                <span>5 days</span>
                            </p>
                            <a href="/discover/serengeti"
                                class="inline-block mt-3 text-sm font-medium text-[#8B5A2B] hover:underline">View deal →</a>
                        </div>
                    </div>
                    <div
                        class="absolute -bottom-4 -right-2 md:-right-6 bg-white/80 backdrop-blur-xl shadow-xl rounded-2xl p-4 w-44 border border-white/50 float-slow">
                        <div class="text-xs uppercase tracking-wider text-[#8B5A2B] font-medium">Best Price</div>
                        <div class="font-bold text-xl text-[#1A1A1A] mt-1">Guarantee</div>
                        <div class="h-0.5 w-8 bg-[#D98C5F]/40 mt-2 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= TRUST BAR ================= --}}
    <section class="py-12 bg-white border-y border-black/5">
        <div class="container mx-auto px-6">
            <p class="text-center text-sm uppercase tracking-wider text-[#6B6B6B] mb-8">Our Travel Partners</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 lg:gap-16">
                <img src="https://placehold.co/120x40/f5efe6/8B5A2B?text=Booking.com" alt="Booking.com"
                    class="trust-logo h-8 md:h-10 w-auto object-contain">
                <img src="https://placehold.co/120x40/f5efe6/8B5A2B?text=Expedia" alt="Expedia"
                    class="trust-logo h-8 md:h-10 w-auto object-contain">
                <img src="https://placehold.co/120x40/f5efe6/8B5A2B?text=TripAdvisor" alt="TripAdvisor"
                    class="trust-logo h-8 md:h-10 w-auto object-contain">
                <img src="https://placehold.co/120x40/f5efe6/8B5A2B?text=Agoda" alt="Agoda"
                    class="trust-logo h-8 md:h-10 w-auto object-contain">
                <img src="https://placehold.co/120x40/f5efe6/8B5A2B?text=Airbnb" alt="Airbnb"
                    class="trust-logo h-8 md:h-10 w-auto object-contain">
            </div>
        </div>
    </section>

    {{-- ================= FEATURED DESTINATIONS (PRIMARY OFFERING) ================= --}}
    <section id="destinations" class="py-24 bg-[#FCFAF7]">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-between items-end mb-14">
                <div>
                    <h2 class="section-title">Where will you go?</h2>
                    <p class="section-sub">Handpicked African experiences — book with confidence</p>
                </div>
                <a href="{{ url('/discover') }}" class="text-[#8B5A2B] font-medium hover:underline mt-4 md:mt-0">View all
                    destinations →</a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $destinations = [
                        [
                            'name' => 'Serengeti Safari',
                            'location' => 'Tanzania',
                            'price' => 'From $2,450 pp',
                            'image' => 'https://placehold.co/600x400/e8dfd5/8B5A2B?text=Serengeti',
                            'link' => '/discover/serengeti'
                        ],
                        [
                            'name' => 'Zanzibar Beach Escape',
                            'location' => 'Zanzibar',
                            'price' => 'From $1,890 pp',
                            'image' => 'https://placehold.co/600x400/e8dfd5/8B5A2B?text=Zanzibar',
                            'link' => '/discover/zanzibar'
                        ],
                        [
                            'name' => 'Cape Town & Winelands',
                            'location' => 'South Africa',
                            'price' => 'From $1,650 pp',
                            'image' => 'https://placehold.co/600x400/e8dfd5/8B5A2B?text=Cape+Town',
                            'link' => '/discover/cape-town'
                        ],
                    ];
                @endphp
                @foreach($destinations as $dest)
                    <a href="{{ $dest['link'] }}" class="destination-card overflow-hidden group block">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ $dest['image'] }}" alt="{{ $dest['name'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-lg text-[#1A1A1A]">{{ $dest['name'] }}</h3>
                                    <p class="text-sm text-[#6B6B6B]">{{ $dest['location'] }}</p>
                                </div>
                                <span class="text-sm font-medium text-[#8B5A2B]">{{ $dest['price'] }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= SECONDARY OFFERING: WEB DEVELOPMENT SERVICES ================= --}}
    <section id="services" class="py-24 bg-white scroll-mt-16">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="section-title">We also build digital systems</h2>
                <p class="section-sub">Vumbi Ventures is the studio behind the platform — we create custom web solutions for
                    partners across Africa.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $services = [
                        [
                            'title' => 'Custom Web Development',
                            'desc' => 'Responsive, high-performance websites and web applications tailored to your needs.',
                            'icon' => '💻'
                        ],
                        [
                            'title' => 'Travel Tech Integration',
                            'desc' => 'Connect to booking APIs, build travel platforms, or integrate affiliate systems.',
                            'icon' => '🌍'
                        ],
                        [
                            'title' => 'Data & Mapping Systems',
                            'desc' => 'Structuring overlooked data into actionable intelligence — our specialty.',
                            'icon' => '📊'
                        ],
                    ];
                @endphp
                @foreach($services as $service)
                    <div class="service-card p-6 text-center group">
                        <div class="text-4xl mb-4">{{ $service['icon'] }}</div>
                        <h3 class="font-semibold text-lg text-[#1A1A1A]">{{ $service['title'] }}</h3>
                        <p class="text-sm text-[#5C5C5C] mt-2 leading-relaxed">{{ $service['desc'] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ url('/contact') }}"
                    class="btn-ghost px-7 py-3.5 rounded-xl text-base inline-flex items-center gap-2">
                    Discuss a Project
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ================= WHY BOOK WITH US? ================= --}}
    <section class="py-24 bg-[#FCFAF7]">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-3xl mb-3">🔒</div>
                    <h3 class="font-semibold text-lg">Best Price Guarantee</h3>
                    <p class="text-sm text-[#5C5C5C] mt-2">We match or beat any price from our partners.</p>
                </div>
                <div>
                    <div class="text-3xl mb-3">🤝</div>
                    <h3 class="font-semibold text-lg">Local Expertise</h3>
                    <p class="text-sm text-[#5C5C5C] mt-2">Curated by Africans who know the continent intimately.</p>
                </div>
                <div>
                    <div class="text-3xl mb-3">💬</div>
                    <h3 class="font-semibold text-lg">24/7 Support</h3>
                    <p class="text-sm text-[#5C5C5C] mt-2">We're here to help before, during, and after your trip.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FIELD NOTES (TRAVEL CONTENT FOR SEO) ================= --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-between items-end mb-14">
                <div>
                    <h2 class="section-title">Field Notes</h2>
                    <p class="section-sub">Travel stories and insider tips from Africa</p>
                </div>
                <a href="/field-notes" class="text-[#8B5A2B] font-medium hover:underline mt-4 md:mt-0">Read all notes →</a>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $notes = [
                        [
                            'title' => 'The Best Time to Visit the Serengeti (Month by Month)',
                            'date' => 'Mar 12, 2026',
                            'excerpt' => 'Plan your safari around the Great Migration with our detailed guide.',
                            'image' => 'https://placehold.co/600x400/f5efe6/8B5A2B?text=Serengeti+Guide'
                        ],
                        [
                            'title' => 'Zanzibar Beyond the Beaches: Stone Town & Spice Farms',
                            'date' => 'Feb 28, 2026',
                            'excerpt' => 'Discover the cultural heart of the Spice Island.',
                            'image' => 'https://placehold.co/600x400/f5efe6/8B5A2B?text=Zanzibar+Culture'
                        ],
                        [
                            'title' => 'How We Build Travel Systems for Overlooked Destinations',
                            'date' => 'Jan 15, 2026',
                            'excerpt' => 'Inside the Vumbi approach to travel intelligence.',
                            'image' => 'https://placehold.co/600x400/f5efe6/8B5A2B?text=Travel+Systems'
                        ],
                    ];
                @endphp
                @foreach($notes as $note)
                    <div class="insight-card overflow-hidden group">
                        <div class="aspect-[16/9] overflow-hidden">
                            <img src="{{ $note['image'] }}" alt="{{ $note['title'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-6">
                            <div class="text-xs uppercase tracking-wider text-[#8B5A2B] mb-2">{{ $note['date'] }}</div>
                            <h3 class="font-semibold text-lg text-[#1A1A1A] group-hover:text-[#8B5A2B] transition-colors">
                                {{ $note['title'] }}
                            </h3>
                            <p class="text-sm text-[#5C5C5C] mt-2 leading-relaxed">{{ $note['excerpt'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= TEAM (OPTIONAL, KEEP OR REMOVE) ================= --}}
    {{-- <section class="py-24 bg-[#FCFAF7]">
        ...
    </section> --}}

    {{-- ================= FINAL CTA ================= --}}
    <section class="py-24 bg-[#121212] text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02]"></div>
        <div class="container mx-auto px-6 relative">
            <h2 class="text-4xl md:text-5xl font-semibold tracking-tight">
                Ready to explore Africa?
            </h2>
            <p class="text-white/70 text-lg md:text-xl mt-4 max-w-2xl mx-auto">
                Browse our curated collection of hotels, safaris, and tours.
            </p>
            <a href="{{ url('/discover') }}"
                class="inline-flex items-center gap-2 mt-8 bg-[#8B5A2B] hover:bg-[#6B421F] px-8 py-4 rounded-xl text-lg font-medium transition-all shadow-lg shadow-[#8B5A2B]/20 hover:shadow-[#6B421F]/30 transform hover:-translate-y-1">
                Start Discovering
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 2a15 15 0 0 0 0 20 15 15 0 0 0 0-20z" />
                    <path d="M2 12h20" />
                </svg>
            </a>
            <p class="text-white/40 text-sm mt-8">Or <a href="{{ url('/contact') }}"
                    class="underline hover:text-white">inquire about web development →</a></p>
        </div>
    </section>
