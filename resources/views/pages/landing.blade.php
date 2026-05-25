{{-- ================= TRUST BAR ================= --}}
<section class="py-12 bg-white border-y border-black/5">
    <div class="container mx-auto px-6">
        <p class="text-center text-sm uppercase tracking-wider text-[#6B6B6B] mb-8">Our Trusted Travel Partners</p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16">
            {{-- Replace placeholders with real logos once downloaded --}}
            <img src="{{ asset('images/partners/bonusarrive-logo.png') }}"
                 alt="BonusArrive — Hotel Booking Partner"
                 class="trust-logo h-8 md:h-10 w-auto object-contain"
                 onerror="this.src='https://placehold.co/120x40/f5efe6/8B5A2B?text=BonusArrive'">
            <img src="{{ asset('images/partners/travelpayouts-logo.png') }}"
                 alt="Travel Payouts — Flights & Hotels"
                 class="trust-logo h-8 md:h-10 w-auto object-contain"
                 onerror="this.src='https://placehold.co/120x40/f5efe6/8B5A2B?text=TravelPayouts'">
            <img src="{{ asset('images/partners/awin-logo.png') }}"
                 alt="Awin — Affiliate Travel Network"
                 class="trust-logo h-8 md:h-10 w-auto object-contain"
                 onerror="this.src='https://placehold.co/120x40/f5efe6/8B5A2B?text=Awin'">
        </div>
    </div>
</section>

{{-- ================= STATS BAR ================= --}}
<section class="py-12 bg-[#FCFAF7]">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="stat-card">
                <div class="text-3xl font-bold text-[#8B5A2B]">54</div>
                <div class="text-sm text-[#5C5C5C] mt-1">African Countries</div>
            </div>
            <div class="stat-card">
                <div class="text-3xl font-bold text-[#8B5A2B]">{{ \App\Models\Blog::count() }}+</div>
                <div class="text-sm text-[#5C5C5C] mt-1">Field Notes Published</div>
            </div>
            <div class="stat-card">
                <div class="text-3xl font-bold text-[#8B5A2B]">3</div>
                <div class="text-sm text-[#5C5C5C] mt-1">Booking Partners</div>
            </div>
            <div class="stat-card">
                <div class="text-3xl font-bold text-[#8B5A2B]">1</div>
                <div class="text-sm text-[#5C5C5C] mt-1">Continent. Infinite Stories.</div>
            </div>
        </div>
    </div>
</section>

{{-- ================= FEATURED STORY ================= --}}
@if(isset($featuredBlog) && $featuredBlog)
<section class="container mx-auto px-6 py-16">
    <div class="flex items-center gap-3 mb-8">
        <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>
        <span class="text-sm uppercase tracking-widest text-[#8B5A2B] font-medium">Featured Story</span>
    </div>
    <div class="glass-panel rounded-3xl overflow-hidden">
        <div class="grid md:grid-cols-2 items-center">
            <div class="aspect-[4/3] md:aspect-auto md:h-full overflow-hidden">
                <img src="{{ asset($featuredBlog->media_path ?? 'images/og-default.jpg') }}"
                     alt="{{ $featuredBlog->title }}"
                     class="w-full h-full object-cover hover:scale-105 transition duration-700">
            </div>
            <div class="p-8 md:p-12">
                <span class="inline-block text-xs uppercase tracking-widest text-[#8B5A2B] font-medium mb-4 bg-[#F5EFE6] px-3 py-1 rounded-full">
                    {{ $featuredBlog->category ?? 'Field Notes' }}
                </span>
                <h2 class="text-2xl md:text-3xl font-semibold leading-snug text-[#1A1A1A]">
                    {{ $featuredBlog->title }}
                </h2>
                <p class="text-[#5C5C5C] mt-4 leading-relaxed">
                    {{ Str::limit(strip_tags($featuredBlog->description ?? $featuredBlog->excerpt ?? ''), 160) }}
                </p>
                <div class="flex items-center gap-4 mt-6">
                    <a href="{{ route('blog.show', $featuredBlog->id) }}"
                       class="btn-primary px-6 py-3 rounded-xl inline-flex items-center gap-2 text-sm">
                        Read Story
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                    <span class="text-xs text-[#6B6B6B]">{{ $featuredBlog->reading_time ?? '8 min read' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ================= POPULAR EAST AFRICAN DESTINATIONS ================= --}}
<section class="container mx-auto px-6 py-16">
    <div class="flex items-end justify-between mb-10">
        <div>
            <h2 class="section-title">Popular East African Destinations</h2>
            <p class="section-sub">Explore hotels, tours, flights and exclusive deals</p>
        </div>
        <a href="/discover"
           class="text-[#8B5A2B] hover:text-[#5C3A1E] transition text-sm font-medium flex items-center gap-2">
            View All
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>

    @php
        $eastAfricanDestinations = [
            [
                'name'        => 'Maasai Mara',
                'location'    => 'Kenya',
                'description' => 'The greatest wildlife spectacle on earth. Home of the Great Migration and Africa\'s most iconic savanna.',
                'image'       => '/images/maasai-mara.jpg',
                'link'        => '/discover?search=' . urlencode('Maasai Mara'),
                'tag'         => 'Safari'
            ],
            [
                'name'        => 'Zanzibar',
                'location'    => 'Tanzania',
                'description' => 'Spice island paradise. Ancient Stone Town, white sand beaches and the clearest water in the Indian Ocean.',
                'image'       => '/images/zanzibar-beach.jpg',
                'link'        => '/discover?search=' . urlencode('Zanzibar'),
                'tag'         => 'Beach'
            ],
            [
                'name'        => 'Diani Beach',
                'location'    => 'Kenya',
                'description' => 'Kenya\'s most beautiful coastline. Pristine white sand, turquoise water and world-class resorts.',
                'image'       => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'link'        => '/discover?search=' . urlencode('Diani Beach'),
                'tag'         => 'Beach'
            ],
            [
                'name'        => 'Lamu Island',
                'location'    => 'Kenya',
                'description' => 'An ancient Swahili town untouched by cars. UNESCO World Heritage. One of East Africa\'s best kept secrets.',
                'image'       => '/images/lamu-island.jpg',
                'link'        => '/discover?search=' . urlencode('Lamu Island'),
                'tag'         => 'Culture'
            ],
            [
                'name'        => 'Arusha',
                'location'    => 'Tanzania',
                'description' => 'Gateway to Kilimanjaro, the Serengeti and Ngorongoro Crater. The safari capital of East Africa.',
                'image'       => '/images/arusha.jpg',
                'link'        => '/discover?search=' . urlencode('Arusha'),
                'tag'         => 'Safari'
            ],
            [
                'name'        => 'Nakuru',
                'location'    => 'Kenya',
                'description' => 'Lake Nakuru National Park — flamingos, rhinos, lions and one of Kenya\'s most accessible wildlife sanctuaries.',
                'image'       => '/images/nakuru.jpg',
                'link'        => '/discover?search=' . urlencode('Nakuru'),
                'tag'         => 'Wildlife'
            ],
        ];
    @endphp

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($eastAfricanDestinations as $dest)
            <a href="{{ $dest['link'] }}"
               class="destination-card group block overflow-hidden">
                <div class="aspect-[4/3] overflow-hidden relative">
                    <img src="{{ $dest['image'] }}"
                         alt="{{ $dest['name'] }}, {{ $dest['location'] }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                         loading="lazy">
                    <span class="absolute top-4 left-4 text-xs font-medium bg-white/90 text-[#8B5A2B] px-3 py-1 rounded-full backdrop-blur-sm">
                        {{ $dest['tag'] }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-xl text-[#1A1A1A] group-hover:text-[#8B5A2B] transition">
                                {{ $dest['name'] }}
                            </h3>
                            <p class="text-[#5C5C5C] text-sm mt-0.5">{{ $dest['location'] }}</p>
                        </div>
                        <svg class="w-5 h-5 text-[#8B5A2B] mt-1 opacity-0 group-hover:opacity-100 transition -translate-x-2 group-hover:translate-x-0 duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </div>
                    <p class="text-sm text-[#5C5C5C] mt-3 leading-relaxed line-clamp-2">
                        {{ $dest['description'] }}
                    </p>
                    <div class="mt-5 text-sm font-medium text-[#8B5A2B] flex items-center gap-2">
                        See Hotels, Tours & Deals
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>

{{-- ================= WHY VUMBI VENTURES ================= --}}
<section class="py-20 bg-white border-y border-black/5">
    <div class="container mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <h2 class="section-title">Why travel with Vumbi Ventures</h2>
            <p class="section-sub">Africa told honestly. By Africans who know it.</p>
        </div>
        <div class="grid md:grid-cols-4 gap-6 text-center">
            <div class="p-6">
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🌍</span>
                </div>
                <h3 class="font-semibold text-[#1A1A1A]">Africa First</h3>
                <p class="text-sm text-[#5C5C5C] mt-2 leading-relaxed">Built by Africans, for people who want to experience the continent authentically.</p>
            </div>
            <div class="p-6">
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">✍️</span>
                </div>
                <h3 class="font-semibold text-[#1A1A1A]">Real Stories</h3>
                <p class="text-sm text-[#5C5C5C] mt-2 leading-relaxed">Not brochure copy. Field Notes from people who've been there, written to tell the truth.</p>
            </div>
            <div class="p-6">
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🔒</span>
                </div>
                <h3 class="font-semibold text-[#1A1A1A]">Trusted Partners</h3>
                <p class="text-sm text-[#5C5C5C] mt-2 leading-relaxed">We work with vetted booking partners so you get the best rates with confidence.</p>
            </div>
            <div class="p-6">
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🧭</span>
                </div>
                <h3 class="font-semibold text-[#1A1A1A]">Beyond the Obvious</h3>
                <p class="text-sm text-[#5C5C5C] mt-2 leading-relaxed">We cover what other travel sites miss — the hidden places, untold history, and real culture.</p>
            </div>
        </div>
    </div>
</section>

{{-- ================= AFRICAN HISTORY SPOTLIGHT ================= --}}
<section class="history-strip py-20">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-block text-xs uppercase tracking-widest text-[#D98C5F] font-medium mb-4">
                Untold Africa
            </span>
            <h2 class="text-3xl md:text-5xl font-semibold text-white leading-tight">
                The stories they forgot<br>to put in the history books.
            </h2>
            <p class="text-white/60 mt-6 text-lg leading-relaxed max-w-xl">
                Before colonial borders were drawn, Africa was home to some of the most advanced civilizations on earth. Emperors who sailed the Atlantic. Universities older than Oxford. Libraries with a million manuscripts. We're telling those stories.
            </p>
            <a href="{{ url('/blog') }}"
               class="inline-flex items-center gap-2 mt-8 bg-[#8B5A2B] hover:bg-[#A87A4D] text-white px-6 py-3.5 rounded-xl font-medium transition-all duration-300 hover:shadow-lg hover:shadow-[#8B5A2B]/30">
                Read Field Notes
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ================= TRAVEL PAYOUTS WIDGET ================= --}}
<section class="py-16 bg-[#FCFAF7]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-10">
            <h2 class="section-title">Find Flights to Africa</h2>
            <p class="section-sub">Search hundreds of airlines and compare prices instantly</p>
        </div>
        <script async src="https://tpscr.com/content?trs=465487&shmarker=677991&powered_by=true&campaign_id=108&promo_id=8412" charset="utf-8"></script>
    </div>
</section>

{{-- ================= FIELD NOTES ================= --}}
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap justify-between items-end mb-12">
            <div>
                <h2 class="section-title">Field Notes</h2>
                <p class="section-sub">Travel stories, African history and cultural deep-dives</p>
            </div>
            <a href="{{ url('/blog') }}"
               class="text-[#8B5A2B] font-medium hover:underline mt-4 md:mt-0 flex items-center gap-1 text-sm">
                Read all stories →
            </a>
        </div>

        @if(isset($blogs) && $blogs->count())
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                    <article class="insight-card group overflow-hidden">
                        @if($blog->media_path)
                            <div class="h-52 overflow-hidden">
                                <img src="{{ asset($blog->media_path) }}"
                                     alt="{{ $blog->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="h-52 bg-[#F5EFE6] flex items-center justify-center">
                                <span class="text-4xl">🌍</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center justify-between text-xs text-[#6B6B6B] mb-3">
                                <span class="uppercase tracking-wider bg-[#F5EFE6] text-[#8B5A2B] px-2 py-0.5 rounded-full">
                                    {{ $blog->category ?? 'Field Notes' }}
                                </span>
                                <span>{{ $blog->reading_time ?? '5 min read' }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-[#1A1A1A] group-hover:text-[#8B5A2B] transition-colors leading-snug">
                                <a href="{{ route('blog.show', $blog->id) }}">{{ $blog->title }}</a>
                            </h3>
                            <p class="text-sm text-[#5C5C5C] mt-3 leading-relaxed line-clamp-2">
                                {{ Str::limit(strip_tags($blog->description ?? $blog->excerpt ?? ''), 120) }}
                            </p>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs text-[#6B6B6B]">
                                    {{ $blog->author->name ?? 'Field Writer' }}
                                </span>
                                <a href="{{ route('blog.show', $blog->id) }}"
                                   class="text-sm font-medium text-[#8B5A2B] hover:text-[#5C3A1E] transition flex items-center gap-1">
                                    Read
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 text-[#6B6B6B]">
                <p class="text-lg">Stories coming soon.</p>
            </div>
        @endif
    </div>
</section>

{{-- ================= NEWSLETTER ================= --}}
<section class="newsletter-section py-20">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-2xl mx-auto text-center">
            <span class="inline-block text-xs uppercase tracking-widest text-white/60 font-medium mb-4">
                Stay in the loop
            </span>
            <h2 class="text-3xl md:text-4xl font-semibold text-white leading-tight">
                Africa in your inbox.<br>No spam. Just stories.
            </h2>
            <p class="text-white/70 mt-4 leading-relaxed">
                New destination guides, untold African history and travel deals — delivered when they're ready, not on a schedule.
            </p>
            <form class="mt-8 flex flex-col sm:flex-row gap-3 max-w-md mx-auto" onsubmit="handleNewsletter(event)">
                <input type="email"
                       placeholder="your@email.com"
                       required
                       class="flex-1 px-5 py-3.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:border-white/50 focus:bg-white/15 transition backdrop-blur-sm">
                <button type="submit"
                        class="px-6 py-3.5 bg-white text-[#8B5A2B] font-semibold rounded-xl hover:bg-[#F5EFE6] transition whitespace-nowrap">
                    Subscribe
                </button>
            </form>
            <p class="text-white/40 text-xs mt-4">No spam. Unsubscribe anytime.</p>
        </div>
    </div>
</section>

{{-- ================= FINAL CTA ================= --}}
<section class="py-24 bg-[#121212] text-white text-center relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02]"></div>
    <div class="container mx-auto px-6 relative">
        <span class="inline-block text-xs uppercase tracking-widest text-[#D98C5F] font-medium mb-6">
            Your Africa Awaits
        </span>
        <h2 class="text-4xl md:text-5xl font-semibold tracking-tight">
            Ready to explore Africa?
        </h2>
        <p class="text-white/60 text-lg md:text-xl mt-4 max-w-2xl mx-auto leading-relaxed">
            Browse our curated collection of destinations, hotels, safaris and cultural experiences across the continent.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
            <a href="{{ url('/discover') }}"
               class="inline-flex items-center gap-2 bg-[#8B5A2B] hover:bg-[#A87A4D] px-8 py-4 rounded-xl text-lg font-medium transition-all duration-300 shadow-lg shadow-[#8B5A2B]/20 hover:shadow-[#8B5A2B]/40 transform hover:-translate-y-1">
                Start Discovering
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15 15 0 0 0 0 20 15 15 0 0 0 0-20z"/><path d="M2 12h20"/></svg>
            </a>
            <a href="{{ url('/blog') }}"
               class="inline-flex items-center gap-2 border border-white/20 hover:border-white/40 px-8 py-4 rounded-xl text-lg font-medium transition-all duration-300 hover:bg-white/5">
                Read Field Notes
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>