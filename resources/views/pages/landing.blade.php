<x-gallery-card />
    {{-- ================= TRUST BAR ================= --}}
    <section class="py-12 bg-white border-y border-black/5">
        <div class="container mx-auto px-6">
            <p class="text-center text-sm uppercase tracking-wider text-[#6B6B6B] mb-8">Our Travel Partners</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12 lg:gap-16">
                <img src="https://placehold.co/120x40/f5efe6/8B5A2B?text=BonusArrive.com" alt="BonusArrive.com"
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
    {{-- Popular East African Destinations --}}
<section class="container mx-auto px-6 py-16">
    <div class="flex items-end justify-between mb-10">
        <div>
            <h2 class="text-3xl md:text-4xl font-light text-raw-linen">Popular East African Destinations</h2>
            <p class="text-[#C4B9A6] mt-2">Click on any destination to explore hotels, tours, flights, and exclusive deals</p>
        </div>
        <a href="/discover" class="text-sunflare hover:text-white transition text-sm font-medium flex items-center gap-2">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    @php
        $eastAfricanDestinations = [
            [
                'name' => 'Maasai Mara',
                'location' => 'Kenya',
                'description' => 'Iconic savanna famous for the Great Migration and incredible wildlife.',
                'image' => '/images/maasai-mara.jpg',
                'link' => '/discover?search=' . urlencode('Maasai Mara')
            ],
            [
                'name' => 'Diani Beach',
                'location' => 'Kenya',
                'description' => 'Pristine white sand beaches with turquoise waters and luxury resorts.',
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'link' => '/discover?search=' . urlencode('Diani Beach')
            ],
            [
                'name' => 'Nakuru',
                'location' => 'Kenya',
                'description' => 'Home to Lake Nakuru National Park and thousands of flamingos.',
                'image' => '/images/nakuru.jpg',
                'link' => '/discover?search=' . urlencode('Nakuru')
            ],
            [
                'name' => 'Lamu Island',
                'location' => 'Kenya',
                'description' => 'Ancient Swahili town and UNESCO World Heritage site with rich culture.',
                'image' => '/images/lamu-island.jpg',
                'link' => '/discover?search=' . urlencode('Lamu Island')
            ],
            [
                'name' => 'Arusha',
                'location' => 'Tanzania',
                'description' => 'Gateway to Mount Kilimanjaro and the famous Serengeti.',
                'image' => '/images/arusha.jpg',
                'link' => '/discover?search=' . urlencode('Arusha')
            ],
            [
                'name' => 'Zanzibar',
                'location' => 'Tanzania',
                'description' => 'Spice islands with stunning beaches and vibrant Swahili culture.',
                'image' => '/images/zanzibar-beach.jpg',
                'link' => '/discover?search=' . urlencode('Zanzibar')
            ],
        ];
    @endphp

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($eastAfricanDestinations as $dest)
            <a href="{{ $dest['link'] }}" 
               class="group block bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-3xl overflow-hidden transition-all duration-300">

                <!-- Image -->
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ $dest['image'] }}" 
                         alt="{{ $dest['name'] }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                         loading="lazy">
                </div>

                <!-- Content -->
                <div class="p-6">
                    <h3 class="font-semibold text-2xl text-raw-linen group-hover:text-sunflare transition">
                        {{ $dest['name'] }}
                    </h3>
                    <p class="text-[#C4B9A6] mt-1 text-sm">{{ $dest['location'] }}</p>
                    
                    <p class="text-[#C4B9A6] mt-3 text-sm line-clamp-3">
                        {{ $dest['description'] }}
                    </p>

                    <div class="mt-6 text-sunflare text-sm font-medium flex items-center gap-2 group-hover:gap-3 transition-all">
                        See Hotels, Tours & Deals 
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        @endforeach
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

<script async src="https://tpscr.com/content?trs=465487&shmarker=677991&powered_by=true&campaign_id=108&promo_id=8412" charset="utf-8"></script>


    {{-- ================= FIELD NOTES (TRAVEL CONTENT FOR SEO) ================= --}}
    <section class="py-24 bg-white container mx-auto px-6 pb-24">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-between items-end mb-14">
                <div>
                    <h2 class="section-title">Field Notes</h2>
                    <p class="section-sub">Travel stories and insider tips from Africa</p>
                </div>
                <a href="/field-notes" class="text-[#8B5A2B] font-medium hover:underline mt-4 md:mt-0">Read all notes →</a>
            </div>
                @php
                    $blogs = App\Models\Blog::latest()->with('author')->take(3)->get();
                @endphp
        @if($blogs->count())
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                @foreach($blogs as $blog)
                    <article class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-xl hover:border-[#8B5A2B]/20 transition duration-300">
                        @if($blog->media_path)
                            <div class="h-52 overflow-hidden">
                                <img src="{{ asset($blog->media_path) }}"
                                    alt="{{ $blog->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-center justify-between text-xs text-[#6B6B6B]">
                                <span class="uppercase tracking-wider">{{ $blog->category }}</span>
                                <span>{{ $blog->reading_time ?? '5 min read' }}</span>
                            </div>

                            <h3 class="mt-3 text-xl font-semibold group-hover:text-[#8B5A2B] transition-colors">
                                <a href="{{ route('blog.show', $blog->id) }}">{{ $blog->title }}</a>
                            </h3>

                            <p class="text-sm text-[#5C5C5C] mt-3 line-clamp-3">
                                {{ Str::limit(strip_tags($blog->description), 120) }}
                            </p>

                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs text-[#6B6B6B]">
                                    {{ $blog->author->name ?? 'Field Writer' }}
                                </span>

                                <a href="{{ route('blog.show', $blog->id) }}"
                                   class="text-sm font-medium text-[#8B5A2B] hover:text-[#5C3A1E] transition">
                                    Read →
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 text-[#6B6B6B]">
                <p class="text-lg">No stories found.</p>
                <p class="text-sm mt-2">Try adjusting your search or filters.</p>
            </div>
        @endif
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
