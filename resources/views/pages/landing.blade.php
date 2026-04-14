@extends('layouts.app')
@push('styles')
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.6;
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }

        .dust-bg {
            background: radial-gradient(circle at 20% 50%, rgba(199, 181, 166, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 90, 43, 0.1) 0%, transparent 50%);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #F9F5F0 0%, #FFFFFF 100%);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #F9F5F0;
        }

        ::-webkit-scrollbar-thumb {
            background: #8B5A2B;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6B421F;
        }
    </style>
@endpush
@push('schema')
    @php
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => url('/') . '#organization',
            'name' => 'Vumbi Ventures',
            'url' => url('/'),
            'logo' => asset('images/vumbi-ventures-logo.png'),
            'sameAs' => [
                'https://twitter.com/vumbiventures',
                'https://linkedin.com/company/vumbi-ventures',
                'https://instagram.com/vumbiventures',
            ],
            'description' => 'A purpose-driven digital innovation company built from the spirit of Africa.',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Nairobi',
                'addressCountry' => 'KE',
            ],
            'slogan' => 'From overlooked places, we build remarkable solutions.',
            'foundingDate' => '2024',
            'foundingLocation' => 'Nairobi, Kenya',
        ];
    @endphp
    <script type="application/ld+json">
                                        {!! json_encode($organizationSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
                                        </script>


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
@section('content')


    <!-- Loading Spinner for Livewire -->
    <div wire:loading.flex class="fixed inset-0 bg-black/50 z-[9999] items-center justify-center hidden">
        <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-3">
            <svg class="animate-spin h-8 w-8 text-[#8B5A2B]" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-[#1A1A1A] font-medium">Loading...</span>
        </div>
    </div>


    <!-- Hero Section -->
    <!-- Hero Section with Image Slider -->
    <!-- Hero Section with Dynamic Slider from HeaderMedia -->
    <section id="home"
        class="min-h-screen flex items-center justify-center dust-bg pt-10 relative overflow-hidden hero-gradient">
        <div class="container mx-auto px-6 py-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">

                <!-- Left: Text Content -->
                <div class="space-y-6">
                    <div class="inline-block">
                        <span class="bg-[#E5E0D9] text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-dust-storm mr-2"></i>From overlooked places
                        </span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold leading-tight">
                        We build
                        <span class="text-[#8B5A2B] relative">
                            remarkable
                            <span class="absolute bottom-2 left-0 w-full h-3 bg-[#D98C5F]/20 -z-10"></span>
                        </span>
                        <br>solutions
                    </h1>
                    <p class="text-xl text-[#6B6B6B] max-w-lg">
                        We build digital tools for Africa's overlooked communities, <br>
                        share authentic stories of it's empire and cultures, <br>
                        and help you explore East Africa - your way.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="#ecosystem"
                            class="bg-[#8B5A2B] text-white px-8 py-3 rounded-full hover:bg-[#6B421F] transition font-medium inline-flex items-center gap-2">
                            Explore Our Ecosystem <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="tel:+254734591543"
                            class="border-2 border-[#8B5A2B] text-[#8B5A2B] px-8 py-3 rounded-full hover:bg-[#8B5A2B] hover:text-white transition font-medium">
                            Call Now
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="flex gap-8 pt-8">
                        <div>
                            <div class="text-3xl font-bold text-[#8B5A2B]">3+</div>
                            <div class="text-sm text-[#6B6B6B]">Active Platforms</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-[#8B5A2B]">5+</div>
                            <div class="text-sm text-[#6B6B6B]">African Countries</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-[#8B5A2B]">100+</div>
                            <div class="text-sm text-[#6B6B6B]">Local Partners</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Dynamic Image Slider -->
                <div class="relative h-full rounded-3xl overflow-hidden shadow-2xl group">
                    {{-- Slider wrapper --}}
                    <div class="relative h-full rounded-3xl overflow-hidden shadow-2xl group">
                        {{-- Slider wrapper --}}
                        <div class="relative w-full h-full overflow-hidden">
                            {{-- Slides track --}}
                            <div id="heroSliderTrack" class="flex transition-transform duration-500 ease-out h-full">
                                @php
                                    $slides = \App\Models\HeaderMedia::latest()->get();
                                @endphp

                                @forelse($slides as $slide)
                                    <div class="w-full flex-shrink-0 h-full">
                                        @if($slide->media_path)
                                            {{-- Adjust asset() to match your public path --}}
                                            <img src="{{ asset($slide->media_path) }}" alt="{{ $slide->title ?? 'Hero image' }}"
                                                class="w-full h-96 object-cover">
                                        @else
                                            <img src="{{ asset('images/default-hero.jpg') }}" alt="Vumbi Ventures"
                                                class="w-full h-96 object-cover">
                                        @endif
                                    </div>
                                @empty
                                    {{-- Fallback if no slides exist --}}
                                    <div class="w-full flex-shrink-0 h-full">
                                        <img src="{{ asset('images/default-hero.jpg') }}" alt="Vumbi Ventures"
                                            class="w-full h-96 object-cover">
                                    </div>
                                @endforelse
                            </div>

                            {{-- Navigation Arrows (visible on hover) --}}
                            @if($slides->count() > 1)
                                <button id="prevSlide"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full p-2 backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </button>
                                <button id="nextSlide"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full p-2 backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>

                                {{-- Dots indicator --}}
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                                    @foreach($slides as $index => $slide)
                                        <button
                                            class="hero-dot w-2 h-2 rounded-full transition-all {{ $loop->first ? 'bg-white w-4' : 'bg-white/50' }}"
                                            data-slide-index="{{ $index }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Add this JavaScript at the bottom of your blade file or in a separate script section --}}


            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <a href="#about" class="flex flex-col items-center text-[#6B6B6B] hover:text-[#8B5A2B] transition">
                <span class="text-sm mb-2">Scroll</span>
                <i class="fas fa-chevron-down animate-bounce"></i>
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Our Story</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Our Name. <span class="text-[#8B5A2B]">Our Meaning.</span>
                    Our Identity.</h2>
                <p class="text-[#6B6B6B] text-lg">Vumbi means dust. We find potential in what the world overlooks.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1: Built From Africa -->
                <div class="bg-[#F9F5F0] p-8 rounded-2xl hover:shadow-xl transition group">
                    <div
                        class="w-16 h-16 bg-[#8B5A2B] rounded-full mb-6 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-globe-africa text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3">Built From Africa</h3>
                    <p class="text-[#6B6B6B]">Proudly inspired by Africa's creativity, resilience, and limitless
                        possibility. Our foundation is African.</p>
                </div>

                <!-- Card 2: Our Philosophy -->
                <div class="bg-[#F9F5F0] p-8 rounded-2xl hover:shadow-xl transition group">
                    <div
                        class="w-16 h-16 bg-[#8B5A2B] rounded-full mb-6 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-lightbulb text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3">Our Philosophy</h3>
                    <p class="text-[#6B6B6B] mb-4">Innovation measured by usefulness, accessibility, and positive
                        impact on people's lives.</p>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#8B5A2B] text-sm"></i>
                            <span class="text-sm">Usefulness</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#8B5A2B] text-sm"></i>
                            <span class="text-sm">Accessibility</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#8B5A2B] text-sm"></i>
                            <span class="text-sm">Impact</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Our Mission -->
                <div class="bg-[#F9F5F0] p-8 rounded-2xl hover:shadow-xl transition group">
                    <div
                        class="w-16 h-16 bg-[#8B5A2B] rounded-full mb-6 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-rocket text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3">Our Mission</h3>
                    <p class="text-[#6B6B6B]">Design meaningful digital solutions that solve real problems and create
                        opportunities for growth.</p>
                </div>
            </div>

            <!-- Dust Philosophy -->
            <div class="mt-16 grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-3xl font-bold mb-4">Why <span class="text-[#8B5A2B]">Dust?</span></h3>
                    <div class="space-y-4 text-[#6B6B6B]">
                        <p>Dust is often ignored. It settles in overlooked places, travels unseen, and touches every
                            corner of life. Many see dust as something insignificant, messy, or unwanted. We see it
                            differently.</p>
                        <p>To us, dust represents reality — raw, unfiltered, and honest. It symbolizes the places people
                            forget, the communities often overlooked, and the problems most companies never attempt to
                            solve.</p>
                        <p class="font-semibold text-[#1A1A1A]">Vumbi Ventures exists for those spaces.</p>
                    </div>
                </div>
                <div class="bg-[#F9F5F0] p-8 rounded-3xl">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-xl text-center">
                            <i class="fas fa-map-marked-alt text-3xl text-[#8B5A2B] mb-2"></i>
                            <p class="font-semibold">We go where others don't look</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl text-center">
                            <i class="fas fa-ear-listen text-3xl text-[#8B5A2B] mb-2"></i>
                            <p class="font-semibold">We listen where others don't hear</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl text-center col-span-2">
                            <i class="fas fa-hammer text-3xl text-[#8B5A2B] mb-2"></i>
                            <p class="font-semibold">We build where others don't try</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">What We Do</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Our <span class="text-[#8B5A2B]">Services</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Practical technology solutions that empower
                    individuals, strengthen businesses, and unlock opportunities.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Service 1: Digital Innovation Consulting --}}
                <div
                    class="bg-gradient-to-br from-[#2C2A24] to-[#8B5A2B] border border-dust-mite rounded-xl p-6 hover:border-sunflare transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-terracotta/20 rounded-xl mb-4 flex items-center justify-center group-hover:bg-terracotta transition">
                        <i class="fas fa-lightbulb text-2xl text-gray-200 group-hover:text-raw-linen transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-raw-linen mb-2 text-gray-100">Digital Innovation Consulting</h3>
                    <p class="text-[#F0E9E0]/80 text-md mb-3">
                        We help businesses and organizations build practical, user‑focused digital solutions for overlooked
                        markets and communities across Africa.
                    </p>
                    <a href="{{ url('/services#consulting') }}"
                        class="inline-flex items-center text-gray-200 text-sm font-medium group-hover:gap-2 transition-all">
                        <span>Learn more</span>
                        <i class="fas fa-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                {{-- Service 2: African Storytelling & Content --}}
                <div
                    class="bg-gradient-to-br from-[#2C2A24] to-[#8B5A2B] border border-dust-mite rounded-xl p-6 hover:border-sunflare transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-terracotta/20 rounded-xl mb-4 flex items-center justify-center group-hover:bg-terracotta transition">
                        <i class="fas fa-pen-fancy text-2xl text-gray-200 group-hover:text-raw-linen transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-raw-linen mb-2 text-gray-100">African Storytelling & Content</h3>
                    <p class="text-[#F0E9E0]/80 text-md mb-3">
                        We produce authentic, SEO‑driven stories – from forgotten empires to modern culture – helping brands
                        connect with Africa's diverse audiences.
                    </p>
                    <a href="{{ url('/services#storytelling') }}"
                        class="inline-flex items-center text-gray-200 text-sm font-medium group-hover:gap-2 transition-all">
                        <span>Learn more</span>
                        <i class="fas fa-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                {{-- Service 3: Travel Curation & Affiliate Monetization --}}
                <div
                    class="bg-gradient-to-br from-[#2C2A24] to-[#8B5A2B] border border-dust-mite rounded-xl p-6 hover:border-sunflare transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-terracotta/20 rounded-xl mb-4 flex items-center justify-center group-hover:bg-terracotta transition">
                        <i class="fas fa-map-marked-alt text-2xl text-gray-200 group-hover:text-raw-linen transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-raw-linen mb-2 text-gray-100">Travel Curation & Affiliate Monetization</h3>
                    <p class="text-[#F0E9E0]/80 text-md mb-3">
                        We help travel businesses and content creators earn through smart affiliate integration, destination
                        guides, and local partnerships.
                    </p>
                    <a href="{{ url('/services#travel') }}"
                        class="inline-flex items-center text-gray-200 text-sm font-medium group-hover:gap-2 transition-all">
                        <span>Learn more</span>
                        <i class="fas fa-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                {{-- Service 4: Partner Program & Business Growth --}}
                <div
                    class="bg-gradient-to-br from-[#2C2A24] to-[#8B5A2B] border border-dust-mite rounded-xl p-6 hover:border-sunflare transition-all duration-300 group">
                    <div
                        class="w-14 h-14 bg-terracotta/20 rounded-xl mb-4 flex items-center justify-center group-hover:bg-terracotta transition">
                        <i class="fas fa-handshake text-2xl text-gray-200 group-hover:text-raw-linen transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-raw-linen mb-2 text-gray-100">Partner Program & Business Growth</h3>
                    <p class="text-[#F0E9E0]/80 text-md mb-3">
                        We connect local African businesses (tour operators, hotels, creators) with global audiences through
                        our ecosystem and marketing channels.
                    </p>
                    <a href="{{ url('/partners') }}"
                        class="inline-flex items-center text-gray-200 text-sm font-medium group-hover:gap-2 transition-all">
                        <span>Learn more</span>
                        <i class="fas fa-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Ecosystem Section -->
    <section id="ecosystem" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Our Structure</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">The Vumbi <span class="text-[#8B5A2B]">Ecosystem</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">An innovation hub developing and managing a growing
                    ecosystem of digital platforms.</p>
            </div>

            <!-- Ecosystem Diagram -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-[#F9F5F0] p-8 rounded-3xl">
                    <!-- Center -->
                    <div class="text-center mb-12">
                        <div class="inline-block bg-[#8B5A2B] text-white px-8 py-4 rounded-2xl shadow-lg">
                            <h3 class="text-2xl font-bold">VUMBI VENTURES</h3>
                            <p class="text-sm opacity-90">Innovation Foundry</p>
                        </div>
                    </div>

                    <!-- Ecosystem Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-4 rounded-xl text-center hover:shadow-md transition group">
                            <i class="fas fa-dna text-2xl text-[#8B5A2B] mb-2"></i>
                            <div class="font-semibold text-[#8B5A2B]">SkillDNA</div>
                            <div class="text-xs text-[#6B6B6B]">Flagship</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl text-center hover:shadow-md transition group">
                            <i class="fas fa-mountain text-2xl text-[#8B5A2B] mb-2"></i>
                            <div class="font-semibold text-[#8B5A2B]">Discover Africa</div>
                            <div class="text-xs text-[#6B6B6B]">Destination Discovery</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl text-center hover:shadow-md transition group">
                            <i class="fas fa-pen-fancy text-2xl text-[#8B5A2B] mb-2"></i>
                            <div class="font-semibold text-[#8B5A2B]">Field Notes</div>
                            <div class="text-xs text-[#6B6B6B]">Blog</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl text-center hover:shadow-md transition group">
                            <i class="fas fa-user-tie text-2xl text-[#8B5A2B] mb-2"></i>
                            <div class="font-semibold text-[#8B5A2B]">Talent Pipeline</div>
                            <div class="text-xs text-[#6B6B6B]">Expert Discovery</div>
                        </div>
                    </div>

                    <!-- Future Products -->
                    <div class="mt-8 text-center">
                        <div class="inline-block bg-[#E5E0D9] px-6 py-3 rounded-full">
                            <span class="text-[#8B5A2B] font-medium"><i class="fas fa-plus-circle mr-2"></i>Future
                                Products In Development</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Highlight: SkillDNA -->
            <div class="mt-16 grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="text-[#8B5A2B] font-semibold"><i class="fas fa-crown mr-2"></i>Flagship
                        Product</span>
                    <h3 class="text-3xl font-bold mt-2 mb-4">SkillDNA</h3>
                    <p class="text-[#6B6B6B] mb-6">A skill-focused platform helping individuals discover, develop, and
                        apply their real abilities through intelligent assessments and guided growth systems.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>AI/ML personalized content recommendations</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Financial literacy & social impact components</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Parent, child, and tutor roles with full accountability</span>
                        </li>
                    </ul>
                    <a href="#" class="inline-block mt-6 text-[#8B5A2B] font-semibold hover:underline">Learn
                        more about SkillDNA <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="bg-[#F9F5F0] p-8 rounded-3xl">
                    <div class="aspect-square bg-white rounded-2xl p-6 shadow-inner">
                        <!-- SkillDNA visualization -->
                        <div class="grid grid-cols-2 gap-4 h-full">
                            <div class="bg-[#8B5A2B]/10 rounded-xl p-4 flex flex-col items-center justify-center">
                                <i class="fas fa-brain text-3xl text-[#8B5A2B] mb-2"></i>
                                <span class="text-[#8B5A2B] font-bold">AI</span>
                            </div>
                            <div class="bg-[#2C5F2D]/10 rounded-xl p-4 flex flex-col items-center justify-center">
                                <i class="fas fa-robot text-3xl text-[#2C5F2D] mb-2"></i>
                                <span class="text-[#2C5F2D] font-bold">ML</span>
                            </div>
                            <div class="bg-[#D98C5F]/10 rounded-xl p-4 flex flex-col items-center justify-center">
                                <i class="fas fa-chart-line text-3xl text-[#D98C5F] mb-2"></i>
                                <span class="text-[#D98C5F] font-bold">Skills</span>
                            </div>
                            <div class="bg-[#C7B5A6]/10 rounded-xl p-4 flex flex-col items-center justify-center">
                                <i class="fas fa-seedling text-3xl text-[#6B6B6B] mb-2"></i>
                                <span class="text-[#6B6B6B] font-bold">Growth</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section (Field Notes) -->
    <section id="blog" class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Stories</span>
                    <h2 class="text-4xl font-bold mt-2">Field <span class="text-[#8B5A2B]">Notes</span></h2>
                    <p class="text-[#6B6B6B]">Documenting the overlooked people, cultures, and destinations that
                        inspire our work.</p>
                </div>
                <a href="#" class="text-[#8B5A2B] font-semibold hover:underline hidden md:flex items-center gap-2">
                    View all posts <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @php
                $blogs = App\Models\Blog::latest()->take(3)->get();
            @endphp
            <div class="grid md:grid-cols-3 gap-6">
                @foreach ($blogs as $blog)
                    <!-- Blog Post 1 -->
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                        <div class="h-48 bg-gradient-to-br from-[#8B5A2B] to-[#D98C5F] relative overflow-hidden"
                            style="background-image: url('{{ asset($blog->media_path) }}')">
                            <div class="absolute inset-0 bg-black/20"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <span class="text-sm bg-[#8B5A2B] px-3 py-1 rounded-full"><i
                                        class="fas fa-user mr-1"></i>{{ ucfirst($blog->category) }}</span>
                            </div>
                            <i class="fas fa-pencil-alt absolute top-4 right-4 text-white/30 text-4xl"></i>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2 group-hover:text-[#8B5A2B] transition">{{ $blog->title }}</h3>
                            <p class="text-[#6B6B6B] mb-4">{{ Str::limit(strip_tags($blog->description), 120) }}
                            </p>
                            <div class="flex items-center text-sm text-[#6B6B6B]">
                                <i class="far fa-calendar mr-1"></i>
                                <span>Mar 15, 2025</span>
                                <span class="mx-2">·</span>
                                <i class="far fa-clock mr-1"></i>
                                <span>{{ $blog->reading_time }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Mobile View All Link -->
            <div class="text-center mt-8 md:hidden">
                <a href="#" class="text-[#8B5A2B] font-semibold hover:underline inline-flex items-center gap-2">
                    View all posts <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Get In Touch</span>
                    <h2 class="text-4xl font-bold mt-2 mb-4">Let's <span class="text-[#8B5A2B]">Connect</span></h2>
                    <p class="text-[#6B6B6B] text-lg mb-8">Whether you're a potential partner, investor, or just
                        curious about what we're building, we'd love to hear from you.</p>

                    <div class="space-y-6">
                        <!-- Contact Info -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-[#8B5A2B]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Email</h4>
                                <p class="text-[#6B6B6B]">hello@vumbiventures.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-[#8B5A2B]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Location</h4>
                                <p class="text-[#6B6B6B]">Nairobi, Kenya<br>Accra, Ghana</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-[#8B5A2B]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Response Time</h4>
                                <p class="text-[#6B6B6B]">Within 24-48 hours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="mt-8 flex gap-4">
                        <a href="#"
                            class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                            <i class="fab fa-twitter text-[#8B5A2B] group-hover:text-white"></i>
                        </a>
                        <a href="#"
                            class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                            <i class="fab fa-linkedin-in text-[#8B5A2B] group-hover:text-white"></i>
                        </a>
                        <a href="#"
                            class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                            <i class="fab fa-instagram text-[#8B5A2B] group-hover:text-white"></i>
                        </a>
                        <a href="#"
                            class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                            <i class="fab fa-github text-[#8B5A2B] group-hover:text-white"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-[#F9F5F0] p-8 rounded-3xl">
                    <form wire:submit.prevent="submitContact" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Name</label>
                            <input type="text" wire:model="name" required
                                class="w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <input type="email" wire:model="email" required
                                class="w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white">
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Message</label>
                            <textarea rows="4" wire:model="message" required
                                class="w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white"></textarea>
                            @error('message')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-[#8B5A2B] text-white px-6 py-3 rounded-xl hover:bg-[#6B421F] transition font-medium inline-flex items-center justify-center gap-2"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Send Message <i class="fas fa-paper-plane"></i></span>
                            <span wire:loading>Sending...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <!-- Alpine.js for mobile menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Additional JavaScript -->
    <script>
        // Update active nav link on scroll
        document.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('nav a[href^="#"]');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.scrollY >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-[#8B5A2B]');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('text-[#8B5A2B]');
                }
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg');
            } else {
                nav.classList.remove('shadow-lg');
            }
        });
    </script>
@endpush
<!-- Livewire Scripts -->