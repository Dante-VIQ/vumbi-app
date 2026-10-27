@extends('layouts.app')

@section('title', 'About Vumbi Ventures | Vetted Local Partners in Kenya & East Africa')
@section('description', 'Vumbi means dust. We find potential in what the world overlooks. Learn how we connect travellers with vetted local partners across East Africa.')
@section('keywords', 'about Vumbi Ventures, African travel company, sustainable tourism Africa, authentic travel, our mission')
@section('og_image', asset('images/about-og.jpg')) 

@push('styles')
<style>
    .dust-bg {
        background: radial-gradient(circle at 20% 50%, rgba(199, 181, 166, 0.12) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(139, 90, 43, 0.08) 0%, transparent 50%);
    }
</style>
@endpush

@push('schema')
@php
    $pageSchemas = [];

    $pageSchemas[] = [
        "@context" => "https://schema.org",
        "@type" => "AboutPage",
        "name" => "About Vumbi Ventures",
        "description" => "Learn about the team behind Vumbi Ventures and our mission to showcase authentic African travel experiences and the local partners who make them possible.",
        "url" => url()->current()
    ];
@endphp
@endpush

@section('content')
    {{-- ================= HERO SECTION ================= --}}
    <section class="relative pt-28 pb-16 lg:pt-36 lg:pb-24 bg-white overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full opacity-5 pointer-events-none">
            <i class="fas fa-wind text-[#8B5A2B] text-[220px] absolute top-12 right-12 rotate-12"></i>
        </div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 bg-[#E5E0D9]/60 text-[#8B5A2B] px-4 py-2 rounded-full text-xs sm:text-sm font-bold tracking-wider mb-6 border border-[#8B5A2B]/10">
                    <i class="fas fa-compass"></i>
                    <span>OUR STORY & IDENTITY</span>
                </div>
                
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-[#1A1A1A] tracking-tight leading-[1.1] mb-6">
                    Travel East Africa with <span class="text-[#8B5A2B]">people who know it</span>
                </h1>
                
                <p class="text-base sm:text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto font-normal">
                    <strong class="text-[#8B5A2B] font-bold">Vumbi</strong> means dust in Swahili. We find potential in what others overlook. From the heart of East Africa, we build remarkable travel experiences for overlooked places and the people who know them best.
                </p>
            </div>
        </div>
    </section>

    {{-- ================= DUST PHILOSOPHY SECTION ================= --}}
    <section class="py-16 lg:py-24 dust-bg border-y border-black/5">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                
                <!-- Philosophy Quote Box -->
                <div class="relative">
                    <div class="absolute -top-8 -left-8 w-32 h-32 bg-[#8B5A2B]/5 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-8 -right-8 w-48 h-48 bg-[#D98C5F]/10 rounded-full blur-2xl"></div>
                    
                    <div class="relative bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-black/5">
                        <div class="text-6xl sm:text-7xl mb-4 text-[#8B5A2B] opacity-20 font-serif">“</div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold mb-6 text-[#1A1A1A]">Why <span class="text-[#8B5A2B]">Dust?</span></h2>
                        <div class="space-y-4 text-gray-600 text-base sm:text-lg leading-relaxed">
                            <p>Dust is often ignored. It settles in overlooked places, travels unseen across roads and game reserves, and touches every corner of life. Many see dust as something insignificant or unwanted. We see it differently.</p>
                            <p>To us, dust represents reality — raw, unfiltered, grounded, and honest. It symbolizes forgotten destinations, local vehicle operators seeking fair connections, and genuine African journeys.</p>
                            <p class="font-bold text-[#1A1A1A] text-lg sm:text-xl pt-2 border-t border-slate-100">Vumbi Ventures exists for those exact spaces.</p>
                        </div>
                    </div>
                </div>

                <!-- Strategic Pillars -->
                <div class="space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-black/5 shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex items-start gap-5">
                            <div class="w-14 h-14 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center text-[#8B5A2B] flex-shrink-0 text-xl font-bold">
                                <i class="fas fa-[#8B5A2B] fa-eye"></i>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#1A1A1A] mb-1">We go where others don't look</h3>
                                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">From untamed reserve tracks to local community transport routes, we uncover unseen value.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-black/5 shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex items-start gap-5">
                            <div class="w-14 h-14 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center text-[#8B5A2B] flex-shrink-0 text-xl font-bold">
                                <i class="fas fa-microphone-alt"></i>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#1A1A1A] mb-1">We listen where others don't hear</h3>
                                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">The wisdom of local safari guides, village hosts, and regional fleet drivers direct our growth.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-black/5 shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex items-start gap-5">
                            <div class="w-14 h-14 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center text-[#8B5A2B] flex-shrink-0 text-xl font-bold">
                                <i class="fas fa-cubes"></i>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-[#1A1A1A] mb-1">We build where others don't try</h3>
                                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Whether it is building a network of vetted fleet partners or planning a route nobody has mapped, we take on the hard, real problems.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= HOW WE WORK / PEOPLE / PROOF ================= --}}
    @php
        $founder = config('about.founder', []);
        $showFounder = ! empty($founder['name']) && ! empty($founder['note']);
        $team = array_filter(config('about.team', []), fn ($m) => ! empty($m['name']));
        $tripCount = \App\Models\PartnerPackage::active()->count();
        $noteCount = \App\Models\Blog::count();
        $proof = collect([
            $tripCount > 0 ? ['value' => $tripCount, 'label' => \Illuminate\Support\Str::plural('Trip', $tripCount) . ' listed'] : null,
            $noteCount > 0 ? ['value' => $noteCount, 'label' => 'Field Notes published'] : null,
        ])->filter()->merge(config('about.proof', []))->values();
    @endphp
    <section class="py-16 lg:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
            <h2 class="text-3xl sm:text-5xl font-black text-[#1A1A1A] leading-tight text-center">
                How we <span class="text-[#8B5A2B]">work</span>
            </h2>
            <p class="mt-4 text-center text-gray-600 text-base sm:text-lg max-w-3xl mx-auto">
                We don't run the vehicles ourselves. We connect travellers with local partners we vet, and show prices up front.
            </p>
            <div class="mt-10 grid sm:grid-cols-2 gap-4">
                @foreach (['Licensed local transport partners', 'Inspected 4x4s and vans', 'Certified driver-guides', 'Upfront prices, no surprises'] as $item)
                    <div class="bg-[#F8F5F2] p-5 rounded-2xl border border-black/5 font-medium">&#10003; {{ $item }}</div>
                @endforeach
            </div>

            @if ($showFounder)
                <div class="mt-16 grid md:grid-cols-3 gap-8 items-center">
                    @if (! empty($founder['photo']))
                        <img src="{{ asset($founder['photo']) }}" alt="{{ $founder['name'] }}" class="rounded-2xl w-full object-cover" loading="lazy">
                    @endif
                    <div class="{{ ! empty($founder['photo']) ? 'md:col-span-2' : 'md:col-span-3' }}">
                        <h3 class="text-2xl font-bold">{{ $founder['name'] }}@if (! empty($founder['role'])) <span class="text-[#8B5A2B] text-base font-medium">, {{ $founder['role'] }}</span>@endif</h3>
                        <p class="mt-3 text-gray-600 leading-relaxed">{{ $founder['note'] }}</p>
                    </div>
                </div>
            @endif

            @if (count($team))
                <div class="mt-12 grid sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($team as $m)
                        <div class="bg-[#F8F5F2] p-5 rounded-2xl border border-black/5">
                            <p class="font-semibold">{{ $m['name'] }}</p>
                            @if (! empty($m['role']))<p class="text-sm text-[#8B5A2B]">{{ $m['role'] }}</p>@endif
                            @if (! empty($m['bio']))<p class="text-sm text-gray-600 mt-2">{{ $m['bio'] }}</p>@endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($proof->isNotEmpty())
                <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($proof as $item)
                        <div class="bg-[#F8F5F2] p-6 rounded-2xl text-center border border-black/5">
                            <div class="text-4xl font-black text-[#8B5A2B]">{{ $item['value'] }}</div>
                            <div class="text-xs font-bold uppercase tracking-wider text-gray-500 mt-1">{{ $item['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            <p class="mt-12 text-center text-sm text-gray-600">Based in Nakuru, Kenya. Planning a trip? See <a href="{{ route('services') }}" class="text-[#8B5A2B] font-medium underline">how we help each type of traveller</a>.</p>
        </div>
    </section>

    {{-- ================= PHILOSOPHY, MISSION, VISION ================= --}}
    <section class="py-16 lg:py-24 dust-bg border-t border-black/5">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
                <span class="text-xs font-bold text-[#8B5A2B] uppercase tracking-widest">Our Guiding Principles</span>
                <h2 class="text-3xl sm:text-5xl font-black text-[#1A1A1A] mt-2 mb-4">What Drives Our Work</h2>
                <p class="text-gray-600 text-base sm:text-lg">Every partnership we establish and itinerary we build is anchored by these core tenets.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                
                <!-- Philosophy Card -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-lg border border-black/5 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 bg-[#8B5A2B] rounded-2xl mb-6 flex items-center justify-center text-white text-2xl shadow-md">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-[#1A1A1A] mb-3">Our Philosophy</h3>
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-6">
                            True value is measured by usefulness, transparency, and tangible impact on local operators and global travelers alike.
                        </p>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-slate-100 text-xs sm:text-sm font-semibold text-slate-700">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-check-circle text-[#8B5A2B]"></i> Practical Utility
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-check-circle text-[#8B5A2B]"></i> Inclusive Access
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-check-circle text-[#8B5A2B]"></i> Lasting Community Impact
                        </div>
                    </div>
                </div>

                <!-- Mission Card -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-lg border border-black/5 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 bg-[#D98C5F] rounded-2xl mb-6 flex items-center justify-center text-white text-2xl shadow-md">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-[#1A1A1A] mb-3">Our Mission</h3>
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-6">
                            To connect travelers directly with verified regional vehicle partners, safari guides, and curated East African destinations.
                        </p>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-slate-100 text-xs sm:text-sm font-semibold text-slate-700">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-arrow-right text-[#D98C5F]"></i> Empower Local Fleet Owners
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-arrow-right text-[#D98C5F]"></i> Deliver Seamless Travel Deals
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-arrow-right text-[#D98C5F]"></i> Preserve Authentic Culture
                        </div>
                    </div>
                </div>

                <!-- Vision Card -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-lg border border-black/5 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 bg-[#2C5F2D] rounded-2xl mb-6 flex items-center justify-center text-white text-2xl shadow-md">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-[#1A1A1A] mb-3">Our Vision</h3>
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-6">
                            To become East Africa's most trusted way to plan and book a trip, linking local guides, drivers and operators with travellers worldwide.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs sm:text-sm font-bold text-slate-800">
                        <span>East Africa Roots</span>
                        <i class="fas fa-long-arrow-alt-right text-[#2C5F2D] text-lg"></i>
                        <span>Global Ecosystem</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= CORE VALUES SECTION ================= --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold text-[#8B5A2B] uppercase tracking-widest">What We Stand For</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#1A1A1A] mt-2">Our Core Values</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-[#F8F5F2] border border-black/5 text-center">
                    <div class="w-14 h-14 mx-auto bg-[#8B5A2B]/10 text-[#8B5A2B] rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="font-bold text-lg text-[#1A1A1A] mb-1">Purpose First</h3>
                    <p class="text-xs sm:text-sm text-gray-600">We prioritize meaningful impact over short-lived trends.</p>
                </div>

                <div class="p-6 rounded-2xl bg-[#F8F5F2] border border-black/5 text-center">
                    <div class="w-14 h-14 mx-auto bg-[#8B5A2B]/10 text-[#8B5A2B] rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="font-bold text-lg text-[#1A1A1A] mb-1">Radical Transparency</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Clear partner rates, fair terms, and honest travel recommendations.</p>
                </div>

                <div class="p-6 rounded-2xl bg-[#F8F5F2] border border-black/5 text-center">
                    <div class="w-14 h-14 mx-auto bg-[#8B5A2B]/10 text-[#8B5A2B] rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="font-bold text-lg text-[#1A1A1A] mb-1">Rooted in Africa</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Deep local understanding drives every project and safari itinerary.</p>
                </div>

                <div class="p-6 rounded-2xl bg-[#F8F5F2] border border-black/5 text-center">
                    <div class="w-14 h-14 mx-auto bg-[#8B5A2B]/10 text-[#8B5A2B] rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="fas fa-[#8B5A2B] fa-compass"></i>
                    </div>
                    <h3 class="font-bold text-lg text-[#1A1A1A] mb-1">Relentless Quality</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Vetted vehicles, expert guides, and clear, honest communication.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= CALL TO ACTION SECTION ================= --}}
    <section class="py-16 sm:py-20 bg-[#8B5A2B] text-white relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 max-w-3xl">
            <h2 class="text-3xl sm:text-5xl font-black mb-4 tracking-tight">Partner or Explore With Us</h2>
            <p class="text-base sm:text-lg text-white/90 mb-8 max-w-xl mx-auto">
                Whether you need a custom safari itinerary or want to partner with us as an operator, guide or vehicle owner, let's connect.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="https://wa.me/{{ config('services.whatsapp.number') }}?text=Hi%20Vumbi%20Ventures,%20I%20read%20your%20story%20and%20would%20like%20to%20connect."
                   target="_blank"
                   data-gtag-event="whatsapp_click" data-gtag-params='{"placement":"about_cta"}'
                   class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg">
                    <i class="fab fa-whatsapp text-xl"></i> Chat on WhatsApp
                </a>
                <a href="{{ url('/tours') }}" 
                   class="w-full sm:w-auto bg-white text-[#8B5A2B] hover:bg-slate-100 font-bold px-8 py-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg">
                    <span>Explore Safari Packages</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Additional about page initialization script
    });
</script>
@endpush