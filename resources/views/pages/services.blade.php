@extends('layouts.app')

@section('title', 'Services - Vumbi Ventures | Digital Innovation & Web Development')
@section('description', 'Explore our services: SkillDNA, Discover Africa, Field Notes, and custom website & system development for businesses. We build remarkable solutions from overlooked places.')
@section('keywords', 'web development, custom systems, digital innovation, SkillDNA, Discover Africa, business websites, Laravel development, e-commerce, African tech services')

@push('styles')
<style>
    .service-card-gradient {
        background: linear-gradient(135deg, #F9F5F0 0%, #FFFFFF 100%);
    }
    
    .pricing-card {
        transition: transform 0.3s ease;
    }
    
    .pricing-card:hover {
        transform: translateY(-5px);
    }
    
    .tech-badge {
        background: linear-gradient(135deg, #8B5A2B10 0%, #D98C5F10 100%);
        border: 1px solid #8B5A2B20;
    }
    
    .process-line {
        position: relative;
    }
    
    .process-line::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -50%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #8B5A2B, #D98C5F);
        display: none;
    }
    
    @media (min-width: 768px) {
        .process-line:not(:last-child)::after {
            display: block;
        }
    }
</style>
@endpush

@push('schema')
    @php
        $serviceSchema = [
            "@context" => "https://schema.org",
            "@type" => "Service",
            "provider" => [
                "@type" => "Organization",
                "name" => "Vumbi Ventures",
                "url" => url('/')
            ],
            "serviceType" => "Digital Innovation & Web Development",
            "serviceOutput" => "Custom websites, web applications, and digital platforms",
            "description" => "Full-service digital innovation company offering custom website development, web application development, e-commerce solutions, and our own platforms: SkillDNA, Discover Africa, and Field Notes.",
            "areaServed" => [
                "@type" => "Country",
                "name" => "Kenya"
            ],
            "hasOfferCatalog" => [
                "@type" => "OfferCatalog",
                "name" => "Digital Services",
                "itemListElement" => [
                    [
                        "@type" => "Offer",
                        "itemOffered" => [
                            "@type" => "Service",
                            "name" => "Custom Website Development"
                        ]
                    ],
                    [
                        "@type" => "Offer",
                        "itemOffered" => [
                            "@type" => "Service",
                            "name" => "Web Application Development"
                        ]
                    ],
                    [
                        "@type" => "Offer",
                        "itemOffered" => [
                            "@type" => "Service",
                            "name" => "E-commerce Solutions"
                        ]
                    ]
                ]
            ]
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($serviceSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-white overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-5">
            <i class="fas fa-code text-[#8B5A2B] text-[200px] absolute top-20 right-20 rotate-12"></i>
            <i class="fas fa-laptop-code text-[#D98C5F] text-[150px] absolute bottom-20 right-40 -rotate-12"></i>
        </div>
        
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block mb-6">
                    <span class="bg-[#E5E0D9] text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-cog mr-2 fa-spin-slow"></i>What We Offer
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Our <span class="text-[#8B5A2B] relative">
                        Services
                        <span class="absolute bottom-2 left-0 w-full h-3 bg-[#D98C5F]/20 -z-10"></span>
                    </span>
                </h1>
                <p class="text-xl text-[#6B6B6B] leading-relaxed max-w-3xl mx-auto">
                    From our own innovative platforms to custom solutions for your business — we build technology that matters.
                </p>
            </div>
        </div>
    </section>

    <!-- Vumbi Ventures Own Platforms -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Our Ecosystem</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Vumbi <span class="text-[#8B5A2B]">Platforms</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Innovative solutions we've built from the ground up, serving communities across Africa.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- SkillDNA -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <div class="h-32 bg-gradient-to-br from-[#8B5A2B] to-[#D98C5F] relative">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <i class="fas fa-dna text-3xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 group-hover:text-[#8B5A2B] transition">SkillDNA</h3>
                        <p class="text-[#6B6B6B] text-sm mb-4">AI-powered skill development platform connecting parents, children, and tutors.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">AI/ML</span>
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Education</span>
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Laravel</span>
                        </div>
                        <a href="#" class="text-[#8B5A2B] text-sm font-semibold hover:underline inline-flex items-center gap-1">
                            Learn more <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Discover Africa -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <div class="h-32 bg-gradient-to-br from-[#2C5F2D] to-[#8B5A2B] relative">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <i class="fas fa-mountain text-3xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 group-hover:text-[#8B5A2B] transition">Discover Africa</h3>
                        <p class="text-[#6B6B6B] text-sm mb-4">Destination discovery platform for exploring African places with rich context.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Travel</span>
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Maps API</span>
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Affiliates</span>
                        </div>
                        <a href="#" class="text-[#8B5A2B] text-sm font-semibold hover:underline inline-flex items-center gap-1">
                            Learn more <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Field Notes -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <div class="h-32 bg-gradient-to-br from-[#D98C5F] to-[#C7B5A6] relative">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <i class="fas fa-pen-fancy text-3xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 group-hover:text-[#8B5A2B] transition">Field Notes</h3>
                        <p class="text-[#6B6B6B] text-sm mb-4">Digital publication documenting overlooked people, cultures, and destinations.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Blog</span>
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Storytelling</span>
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Culture</span>
                        </div>
                        <a href="/" class="text-[#8B5A2B] text-sm font-semibold hover:underline inline-flex items-center gap-1">
                            Read stories <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Partner Program -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <div class="h-32 bg-gradient-to-br from-[#8B5A2B] to-[#2C5F2D] relative">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <i class="fas fa-handshake text-3xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 group-hover:text-[#8B5A2B] transition">Partner Program</h3>
                        <p class="text-[#6B6B6B] text-sm mb-4">Connect local African businesses with travelers and learners.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Network</span>
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Local Business</span>
                            <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Growth</span>
                        </div>
                        <a href="/" class="text-[#8B5A2B] text-sm font-semibold hover:underline inline-flex items-center gap-1">
                            Become partner <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client Services - Web Development -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">For Your Business</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Custom <span class="text-[#8B5A2B]">Web Development</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">We build websites and systems that help businesses grow, automate, and succeed online.</p>
            </div>

            <!-- Service Categories -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Website Development -->
                <div class="service-card-gradient p-8 rounded-3xl shadow-sm hover:shadow-xl transition group">
                    <div class="w-20 h-20 bg-[#8B5A2B] rounded-2xl mb-6 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-globe text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Website Development</h3>
                    <p class="text-[#6B6B6B] mb-4">Professional, responsive websites that make your business stand out.</p>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Business websites & corporate sites</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Landing pages & portfolios</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Blogs & content platforms</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>SEO optimization</span>
                        </li>
                    </ul>
                    <div class="border-t border-[#E5E0D9] pt-4">
                        <span class="text-sm font-semibold">Starting from</span>
                        <div class="text-2xl font-bold text-[#8B5A2B]">$500</div>
                    </div>
                </div>

                <!-- Web Applications -->
                <div class="service-card-gradient p-8 rounded-3xl shadow-sm hover:shadow-xl transition group">
                    <div class="w-20 h-20 bg-[#D98C5F] rounded-2xl mb-6 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-cubes text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Web Applications</h3>
                    <p class="text-[#6B6B6B] mb-4">Custom systems that automate and streamline your business processes.</p>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Custom CRM systems</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Inventory management</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Booking & reservation systems</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Customer portals</span>
                        </li>
                    </ul>
                    <div class="border-t border-[#E5E0D9] pt-4">
                        <span class="text-sm font-semibold">Starting from</span>
                        <div class="text-2xl font-bold text-[#8B5A2B]">$2,500</div>
                    </div>
                </div>

                <!-- E-Commerce -->
                <div class="service-card-gradient p-8 rounded-3xl shadow-sm hover:shadow-xl transition group">
                    <div class="w-20 h-20 bg-[#2C5F2D] rounded-2xl mb-6 flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-shopping-cart text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">E-Commerce Solutions</h3>
                    <p class="text-[#6B6B6B] mb-4">Online stores that help you sell products and grow your business.</p>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Online stores & marketplaces</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Payment integration</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Inventory & order management</span>
                        </li>
                        <li class="flex items-center gap-2 text-sm">
                            <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                            <span>Multi-vendor platforms</span>
                        </li>
                    </ul>
                    <div class="border-t border-[#E5E0D9] pt-4">
                        <span class="text-sm font-semibold">Starting from</span>
                        <div class="text-2xl font-bold text-[#8B5A2B]">$1,500</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mt-2 mb-4">More <span class="text-[#8B5A2B]">Services</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Everything your business needs to succeed online.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Service 1 -->
                <div class="bg-white p-6 rounded-xl text-center hover:shadow-lg transition group">
                    <div class="w-16 h-16 mx-auto bg-[#8B5A2B]/10 rounded-xl mb-4 flex items-center justify-center group-hover:bg-[#8B5A2B] transition">
                        <i class="fas fa-mobile-alt text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Responsive Design</h3>
                    <p class="text-sm text-[#6B6B6B]">Perfect on all devices</p>
                </div>

                <!-- Service 2 -->
                <div class="bg-white p-6 rounded-xl text-center hover:shadow-lg transition group">
                    <div class="w-16 h-16 mx-auto bg-[#8B5A2B]/10 rounded-xl mb-4 flex items-center justify-center group-hover:bg-[#8B5A2B] transition">
                        <i class="fas fa-search text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                    </div>
                    <h3 class="font-semibold mb-2">SEO Optimization</h3>
                    <p class="text-sm text-[#6B6B6B]">Rank higher on Google</p>
                </div>

                <!-- Service 3 -->
                <div class="bg-white p-6 rounded-xl text-center hover:shadow-lg transition group">
                    <div class="w-16 h-16 mx-auto bg-[#8B5A2B]/10 rounded-xl mb-4 flex items-center justify-center group-hover:bg-[#8B5A2B] transition">
                        <i class="fas fa-tachometer-alt text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Performance Optimization</h3>
                    <p class="text-sm text-[#6B6B6B]">Lightning fast loading</p>
                </div>

                <!-- Service 4 -->
                <div class="bg-white p-6 rounded-xl text-center hover:shadow-lg transition group">
                    <div class="w-16 h-16 mx-auto bg-[#8B5A2B]/10 rounded-xl mb-4 flex items-center justify-center group-hover:bg-[#8B5A2B] transition">
                        <i class="fas fa-shield-alt text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Security & Maintenance</h3>
                    <p class="text-sm text-[#6B6B6B]">Keep your site secure</p>
                </div>

                <!-- Service 5 -->
                <div class="bg-white p-6 rounded-xl text-center hover:shadow-lg transition group">
                    <div class="w-16 h-16 mx-auto bg-[#8B5A2B]/10 rounded-xl mb-4 flex items-center justify-center group-hover:bg-[#8B5A2B] transition">
                        <i class="fas fa-database text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Database Design</h3>
                    <p class="text-sm text-[#6B6B6B]">Efficient data management</p>
                </div>

                <!-- Service 6 -->
                <div class="bg-white p-6 rounded-xl text-center hover:shadow-lg transition group">
                    <div class="w-16 h-16 mx-auto bg-[#8B5A2B]/10 rounded-xl mb-4 flex items-center justify-center group-hover:bg-[#8B5A2B] transition">
                        <i class="fas fa-cloud text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Cloud Hosting</h3>
                    <p class="text-sm text-[#6B6B6B]">Reliable & scalable</p>
                </div>

                <!-- Service 7 -->
                <div class="bg-white p-6 rounded-xl text-center hover:shadow-lg transition group">
                    <div class="w-16 h-16 mx-auto bg-[#8B5A2B]/10 rounded-xl mb-4 flex items-center justify-center group-hover:bg-[#8B5A2B] transition">
                        <i class="fas fa-chart-bar text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Analytics Integration</h3>
                    <p class="text-sm text-[#6B6B6B]">Track your success</p>
                </div>

                <!-- Service 8 -->
                <div class="bg-white p-6 rounded-xl text-center hover:shadow-lg transition group">
                    <div class="w-16 h-16 mx-auto bg-[#8B5A2B]/10 rounded-xl mb-4 flex items-center justify-center group-hover:bg-[#8B5A2B] transition">
                        <i class="fas fa-headset text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Ongoing Support</h3>
                    <p class="text-sm text-[#6B6B6B]">We're here to help</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Technology Stack -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Our Toolkit</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Technologies We <span class="text-[#8B5A2B]">Master</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Modern, robust technologies that deliver exceptional results.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <!-- Tech 1 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fab fa-laravel text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">Laravel</span>
                </div>

                <!-- Tech 2 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fab fa-php text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">PHP</span>
                </div>

                <!-- Tech 3 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fab fa-js text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">JavaScript</span>
                </div>

                <!-- Tech 4 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fab fa-vuejs text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">Vue.js</span>
                </div>

                <!-- Tech 5 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fab fa-react text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">React</span>
                </div>

                <!-- Tech 6 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fab fa-tailwindcss text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">Tailwind</span>
                </div>

                <!-- Tech 7 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fas fa-database text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">MySQL</span>
                </div>

                <!-- Tech 8 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fas fa-database text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">PostgreSQL</span>
                </div>

                <!-- Tech 9 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fab fa-docker text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">Docker</span>
                </div>

                <!-- Tech 10 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fab fa-git-alt text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">Git</span>
                </div>

                <!-- Tech 11 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fas fa-cloud text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">AWS</span>
                </div>

                <!-- Tech 12 -->
                <div class="tech-badge p-4 rounded-xl text-center hover:shadow-md transition">
                    <i class="fas fa-code text-3xl text-[#8B5A2B] mb-2"></i>
                    <span class="block text-sm font-semibold">Livewire</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Development Process -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">How We Work</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Our <span class="text-[#8B5A2B]">Process</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">A proven approach that delivers results, on time and on budget.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="process-line text-center relative">
                    <div class="w-20 h-20 mx-auto bg-[#8B5A2B] rounded-full flex items-center justify-center mb-4">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <h3 class="font-semibold mb-2">Discovery</h3>
                    <p class="text-sm text-[#6B6B6B]">We listen to your needs, understand your goals, and define requirements.</p>
                </div>

                <!-- Step 2 -->
                <div class="process-line text-center relative">
                    <div class="w-20 h-20 mx-auto bg-[#D98C5F] rounded-full flex items-center justify-center mb-4">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <h3 class="font-semibold mb-2">Planning</h3>
                    <p class="text-sm text-[#6B6B6B]">We create wireframes, architecture, and project roadmap.</p>
                </div>

                <!-- Step 3 -->
                <div class="process-line text-center relative">
                    <div class="w-20 h-20 mx-auto bg-[#2C5F2D] rounded-full flex items-center justify-center mb-4">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <h3 class="font-semibold mb-2">Development</h3>
                    <p class="text-sm text-[#6B6B6B]">We build your solution with regular updates and iterations.</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-[#C7B5A6] rounded-full flex items-center justify-center mb-4">
                        <span class="text-2xl font-bold text-white">4</span>
                    </div>
                    <h3 class="font-semibold mb-2">Launch & Support</h3>
                    <p class="text-sm text-[#6B6B6B]">We deploy, test, and provide ongoing maintenance and support.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio/Recent Work -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Recent Work</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Projects We've <span class="text-[#8B5A2B]">Built</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Real solutions for real businesses.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Project 1 -->
                <div class="bg-[#F9F5F0] rounded-2xl overflow-hidden hover:shadow-xl transition group">
                    <div class="h-48 bg-gradient-to-br from-[#8B5A2B] to-[#D98C5F] relative">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="bg-white text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-semibold">View Project</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold mb-1">E-Commerce Platform</h3>
                        <p class="text-sm text-[#6B6B6B]">Online store for local artisans</p>
                    </div>
                </div>

                <!-- Project 2 -->
                <div class="bg-[#F9F5F0] rounded-2xl overflow-hidden hover:shadow-xl transition group">
                    <div class="h-48 bg-gradient-to-br from-[#2C5F2D] to-[#8B5A2B] relative">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="bg-white text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-semibold">View Project</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold mb-1">Hotel Booking System</h3>
                        <p class="text-sm text-[#6B6B6B]">For a boutique hotel in Nairobi</p>
                    </div>
                </div>

                <!-- Project 3 -->
                <div class="bg-[#F9F5F0] rounded-2xl overflow-hidden hover:shadow-xl transition group">
                    <div class="h-48 bg-gradient-to-br from-[#D98C5F] to-[#C7B5A6] relative">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="bg-white text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-semibold">View Project</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold mb-1">CRM System</h3>
                        <p class="text-sm text-[#6B6B6B]">Custom solution for a logistics company</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="/contact" class="bg-[#8B5A2B] text-white px-8 py-3 rounded-full hover:bg-[#6B421F] transition font-medium inline-flex items-center gap-2">
                    Start Your Project <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mt-2 mb-4">Frequently Asked <span class="text-[#8B5A2B]">Questions</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Everything you need to know about working with us.</p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#F9F5F0] transition">
                        <span class="font-semibold">How long does it take to build a website?</span>
                        <i class="fas fa-chevron-down" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        A simple business website typically takes 2-4 weeks. More complex applications can take 2-3 months. We'll provide a detailed timeline during our discovery call.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#F9F5F0] transition">
                        <span class="font-semibold">What technologies do you use?</span>
                        <i class="fas fa-chevron-down" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        We specialize in Laravel, PHP, JavaScript, Vue.js, React, Tailwind CSS, and MySQL. We choose the best technology for your specific needs.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#F9F5F0] transition">
                        <span class="font-semibold">Do you provide maintenance after launch?</span>
                        <i class="fas fa-chevron-down" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        Yes! We offer ongoing maintenance and support packages to keep your site secure, updated, and running smoothly.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-white rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#F9F5F0] transition">
                        <span class="font-semibold">How much does a website cost?</span>
                        <i class="fas fa-chevron-down" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        Prices vary based on complexity. Simple business websites start at $500, while custom applications start at $2,500. Contact us for a personalized quote.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-[#8B5A2B] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <i class="fas fa-code absolute top-10 left-10 text-white text-8xl"></i>
            <i class="fas fa-laptop-code absolute bottom-10 right-10 text-white text-8xl"></i>
        </div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Build Something?</h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">Whether you need a website, custom system, or want to partner with us, let's talk.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/contact" class="bg-white text-[#8B5A2B] px-8 py-3 rounded-full hover:bg-[#F9F5F0] transition font-medium inline-flex items-center gap-2">
                    Get a Free Quote <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#ecosystem" class="border-2 border-white text-white px-8 py-3 rounded-full hover:bg-white hover:text-[#8B5A2B] transition font-medium">
                    View Our Platforms
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Add any page-specific JavaScript here
    document.addEventListener('alpine:init', () => {
        Alpine.data('faq', () => ({
            open: false
        }))
    })
</script>
@endpush