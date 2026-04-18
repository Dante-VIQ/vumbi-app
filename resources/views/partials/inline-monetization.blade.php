    @if($dynamicHotels || $recommendedAffiliates->count() > 0)
        <section class="py-12 bg-indigo-night bg-opacity-30 border border-dust-mite rounded-2xl mt-12">
            <div class="container mx-auto px-6">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-light text-raw-linen mb-3">Recommended for This Story</h3>
                    <p class="text-[#C4B9A6]">Tailored experiences and booking options matched to this content</p>
                </div>

                <div class="grid lg:grid-cols-12 gap-8">

                    {{-- Dynamic Hotels from Travelpayouts API --}}
                    @if($dynamicHotels && count($dynamicHotels) > 0)
                        <div class="lg:col-span-7">
                            <h4 class="text-xl text-sunflare mb-6 flex items-center gap-2">
                                <i class="fas fa-hotel"></i> Hotels in {{ $detectedLocation }}
                            </h4>
                            <div class="grid md:grid-cols-2 gap-6">
                                @foreach($dynamicHotels as $hotel)
                                    <div class="bg-deep-earth rounded-xl overflow-hidden border border-dust-mite">
                                        @if($hotel['image'] ?? false)
                                            <img src="{{ $hotel['image'] }}" alt="{{ $hotel['name'] ?? 'Hotel' }}"
                                                class="w-full h-48 object-cover">
                                        @endif
                                        <div class="p-5">
                                            <h5 class="font-medium text-raw-linen">{{ $hotel['name'] ?? 'Luxury Stay' }}</h5>
                                            <p class="text-sm text-[#C4B9A6]">{{ $hotel['price'] ?? 'Best rates available' }}</p>
                                            <a href="{{ $hotel['url'] ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                                class="mt-4 block w-full text-center bg-sunflare text-deep-earth py-3 rounded-lg text-sm font-medium hover:bg-raw-linen transition">
                                                View & Book →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Static Affiliate Recommendations --}}
                    @if($recommendedAffiliates->count() > 0)
                        <div class="{{ $dynamicHotels ? 'lg:col-span-5' : 'lg:col-span-12' }}">
                            <h4 class="text-xl text-sunflare mb-6">More Ways to Explore</h4>
                            <div class="space-y-6">
                                @foreach($recommendedAffiliates as $aff)
                                    <div class="bg-deep-earth p-6 rounded-xl border border-dust-mite">
                                        <span
                                            class="text-xs uppercase tracking-widest text-terracotta">{{ strtoupper($aff->network) }}</span>
                                        <h5 class="font-medium text-raw-linen mt-2">{{ $aff->program_name }}</h5>
                                        <p class="text-sm text-[#C4B9A6] mt-1">{{ $aff->description }}</p>

                                        @if($aff->widget_code)
                                            <div class="mt-4">{!! $aff->widget_code !!}</div>
                                        @elseif($aff->affiliate_link)
                                            <a href="{{ $aff->affiliate_link }}" target="_blank" rel="nofollow sponsored"
                                                class="mt-4 inline-block bg-sunflare text-deep-earth px-6 py-3 rounded-lg text-sm font-medium">
                                                Explore Offers →
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <p class="text-center text-xs text-[#C4B9A6] mt-10">
                    These recommendations are dynamically matched to this story.
                    Affiliate links may earn us a small commission at no extra cost to you.
                </p>
            </div>
        </section>
    @endif
