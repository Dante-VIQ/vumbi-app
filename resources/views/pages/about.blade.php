@extends('layouts.app')

@php
    $seo_title = 'About Vumbi Ventures – A Digital Innovation Company Built from Africa';
    $seo_description = "Vumbi means dust — the overlooked places we build for. Meet the team turning African stories, culture and ingenuity into travel platforms and digital products.";
    $seo_og_title = 'Our Name. Our Meaning. Our Identity.';
    $seo_og_description = 'Vumbi Ventures builds remarkable digital solutions inspired by the spirit of Africa — for the places and people the world overlooks.';
@endphp
@push('styles')
<style>
    .dust-bg {
        background: radial-gradient(circle at 20% 50%, rgba(199, 181, 166, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(139, 90, 43, 0.1) 0%, transparent 50%);
    }
    
    .timeline-line {
        position: relative;
    }
    
    .timeline-line::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #8B5A2B, #D98C5F, #C7B5A6);
    }
</style>
@endpush

@push('schema')
    @php
        $aboutSchema = [
            "@context" => "https://schema.org",
            "@type" => "AboutPage",
            "@id" => url('/about') . "#about",
            "name" => "About Vumbi Ventures",
            "description" => "Learn about Vumbi Ventures - our name meaning dust, our inspiration from Africa, and our mission to build remarkable solutions from overlooked places.",
            "url" => url('/about'),
            "mainEntity" => [
                "@id" => url('/') . "#organization"
            ],
            "breadcrumb" => [
                "@type" => "BreadcrumbList",
                "itemListElement" => [
                    [
                        "@type" => "ListItem",
                        "position" => 1,
                        "name" => "Home",
                        "item" => url('/')
                    ],
                    [
                        "@type" => "ListItem",
                        "position" => 2,
                        "name" => "About",
                        "item" => url('/about')
                    ]
                ]
            ]
        ];

        $founderSchema = [
            "@context" => "https://schema.org",
            "@type" => "Person",
            "@id" => url('/') . "#founder",
            "name" => "Founder of Vumbi Ventures",
            "description" => "Founder of Vumbi Ventures with deep roots in African innovation and technology",
            "worksFor" => [
                "@id" => url('/') . "#organization"
            ],
            "jobTitle" => "Founder",
            "knowsAbout" => ["African Innovation", "Technology", "Digital Solutions", "Skill Development", "Tourism"]
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($aboutSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($founderSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-white overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full opacity-5">
            <i class="fas fa-dust-storm text-[#8B5A2B] text-[200px] absolute top-20 right-20 rotate-12"></i>
        </div>
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block mb-6">
                    <span class="bg-[#E5E0D9] text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-dust-storm mr-2"></i>Our Story
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Our Name. <span class="text-[#8B5A2B] relative">
                        Our Meaning.
                        <span class="absolute bottom-2 left-0 w-full h-3 bg-[#D98C5F]/20 -z-10"></span>
                    </span><br>Our Identity.
                </h1>
                <p class="text-xl text-[#6B6B6B] leading-relaxed max-w-3xl mx-auto">
                    Vumbi means dust. We find potential in what the world overlooks. From the spirit of Africa, we build remarkable solutions for overlooked places.
                </p>
            </div>
        </div>
    </section>

    <!-- Dust Philosophy Section -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-[#8B5A2B]/5 rounded-full"></div>
                    <div class="absolute -bottom-10 -right-10 w-60 h-60 bg-[#D98C5F]/5 rounded-full"></div>
                    
                    <div class="relative bg-white p-8 rounded-3xl shadow-xl">
                        <div class="text-7xl mb-6 text-[#8B5A2B] opacity-20">"</div>
                        <h2 class="text-3xl font-bold mb-6 relative z-10">Why <span class="text-[#8B5A2B]">Dust?</span></h2>
                        <div class="space-y-6 text-[#6B6B6B] text-lg leading-relaxed">
                            <p>Dust is often ignored. It settles in overlooked places, travels unseen, and touches every corner of life. Many see dust as something insignificant, messy, or unwanted. We see it differently.</p>
                            <p>To us, dust represents reality — raw, unfiltered, and honest. It symbolizes the places people forget, the communities often overlooked, and the problems most companies never attempt to solve.</p>
                            <p class="font-semibold text-[#1A1A1A] text-xl">Vumbi Ventures exists for those spaces.</p>
                        </div>
                        
                        <!-- Dust particles animation -->
                        <div class="absolute inset-0 pointer-events-none overflow-hidden">
                            <div class="absolute top-10 left-10 w-2 h-2 bg-[#8B5A2B]/20 rounded-full animate-pulse-slow"></div>
                            <div class="absolute bottom-20 right-20 w-3 h-3 bg-[#D98C5F]/20 rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
                            <div class="absolute top-40 right-40 w-1.5 h-1.5 bg-[#C7B5A6]/30 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-8">
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition group">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center group-hover:bg-[#8B5A2B] transition flex-shrink-0">
                                <i class="fas fa-eye text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">We go where others don't look</h3>
                                <p class="text-[#6B6B6B]">In the margins, in the unnoticed corners of society and geography, we find our inspiration and purpose.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition group">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center group-hover:bg-[#8B5A2B] transition flex-shrink-0">
                                <i class="fas fa-ear-listen text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">We listen where others don't hear</h3>
                                <p class="text-[#6B6B6B]">The quiet voices, the untold stories, the wisdom of overlooked communities guide our work.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition group">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 bg-[#8B5A2B]/10 rounded-2xl flex items-center justify-center group-hover:bg-[#8B5A2B] transition flex-shrink-0">
                                <i class="fas fa-hammer text-2xl text-[#8B5A2B] group-hover:text-white transition"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">We build where others don't try</h3>
                                <p class="text-[#6B6B6B]">The hardest problems, the most challenging environments—that's where we create our most meaningful solutions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Built From Africa Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="order-2 md:order-1">
                    <div class="relative">
                        <!-- Africa Map Visualization -->
                        <div class="bg-[#F9F5F0] p-8 rounded-3xl relative overflow-hidden">
                            <div class="absolute inset-0 opacity-10">
                                <svg viewBox="0 0 100 100" class="w-full h-full">
                                    <path d="M30,20 L45,15 L60,20 L70,30 L75,45 L70,60 L60,70 L45,75 L30,70 L20,60 L15,45 L20,30 Z" 
                                          fill="#8B5A2B" stroke="#8B5A2B" stroke-width="0.5"/>
                                </svg>
                            </div>
                            
                            <div class="relative grid grid-cols-2 gap-6">
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-[#8B5A2B] mb-2">54</div>
                                    <div class="text-sm text-[#6B6B6B]">Countries</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-[#8B5A2B] mb-2">2000+</div>
                                    <div class="text-sm text-[#6B6B6B]">Languages</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-[#8B5A2B] mb-2">1.4B</div>
                                    <div class="text-sm text-[#6B6B6B]">People</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-[#8B5A2B] mb-2">∞</div>
                                    <div class="text-sm text-[#6B6B6B]">Innovation</div>
                                </div>
                            </div>
                            
                            <!-- Decorative elements -->
                            <div class="absolute bottom-4 right-4 text-6xl opacity-10">
                                🌍
                            </div>
                        </div>
                        
                        <!-- Floating stats -->
                        <div class="absolute -top-6 -right-6 bg-white px-6 py-3 rounded-full shadow-lg">
                            <span class="text-[#8B5A2B] font-semibold"><i class="fas fa-star mr-2"></i>Since 2024</span>
                        </div>
                    </div>
                </div>
                
                <div class="order-1 md:order-2">
                    <div class="inline-block mb-4">
                        <span class="bg-[#E5E0D9] text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-globe-africa mr-2"></i>Our Foundation
                        </span>
                    </div>
                    <h2 class="text-4xl font-bold mb-6 leading-tight">
                        Built From the <span class="text-[#8B5A2B]">Spirit of Africa</span>
                    </h2>
                    <div class="space-y-6 text-[#6B6B6B] text-lg">
                        <p>Vumbi Ventures is proudly inspired by Africa — its people, cultures, creativity, resilience, and limitless possibility. Africa is not just our environment; it is our foundation.</p>
                        
                        <p>The continent's diversity of stories, traditions, challenges, and innovations fuels our imagination and guides our purpose. Every solution we build carries the essence of African ingenuity.</p>
                        
                        <div class="bg-[#F9F5F0] p-6 rounded-2xl italic">
                            <p class="font-semibold text-[#1A1A1A]">"We believe Africa is one of the greatest sources of ideas in the world. Its everyday realities inspire solutions that are practical, resourceful, and deeply human."</p>
                        </div>
                        
                        <p>We don't just represent Africa. We reflect its spirit of creation, endurance, and vision in everything we build.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Philosophy, Mission, Vision Section -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Our Guiding Principles</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">What Drives <span class="text-[#8B5A2B]">Us</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Every decision we make, every line of code we write, is guided by these core principles.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Philosophy Card -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#8B5A2B]/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition"></div>
                    
                    <div class="relative">
                        <div class="w-20 h-20 bg-[#8B5A2B] rounded-2xl mb-6 flex items-center justify-center transform group-hover:rotate-6 transition">
                            <i class="fas fa-lightbulb text-3xl text-white"></i>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-4">Our Philosophy</h3>
                        <p class="text-[#6B6B6B] mb-6">Innovation is not defined by complexity or hype. True innovation is measured by usefulness, accessibility, and positive impact on people's lives.</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#8B5A2B]/10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check text-[#8B5A2B]"></i>
                                </div>
                                <div>
                                    <span class="font-semibold block">Usefulness</span>
                                    <span class="text-sm text-[#6B6B6B]">If it doesn't solve a problem, it's not finished</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#8B5A2B]/10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-universal-access text-[#8B5A2B]"></i>
                                </div>
                                <div>
                                    <span class="font-semibold block">Accessibility</span>
                                    <span class="text-sm text-[#6B6B6B]">Technology must serve everyone</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#8B5A2B]/10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chart-line text-[#8B5A2B]"></i>
                                </div>
                                <div>
                                    <span class="font-semibold block">Impact</span>
                                    <span class="text-sm text-[#6B6B6B]">Real progress transforms lives</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mission Card -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#D98C5F]/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition"></div>
                    
                    <div class="relative">
                        <div class="w-20 h-20 bg-[#D98C5F] rounded-2xl mb-6 flex items-center justify-center transform group-hover:rotate-6 transition">
                            <i class="fas fa-bullseye text-3xl text-white"></i>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-4">Our Mission</h3>
                        <p class="text-[#6B6B6B] mb-6">To design and build meaningful digital solutions that solve real-world problems, empower people, and create opportunities for growth across communities and industries.</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#D98C5F] rounded-full"></div>
                                <span class="text-sm">Solve real problems</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#D98C5F] rounded-full"></div>
                                <span class="text-sm">Empower individuals</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#D98C5F] rounded-full"></div>
                                <span class="text-sm">Create opportunities</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Vision Card -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#2C5F2D]/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition"></div>
                    
                    <div class="relative">
                        <div class="w-20 h-20 bg-[#2C5F2D] rounded-2xl mb-6 flex items-center justify-center transform group-hover:rotate-6 transition">
                            <i class="fas fa-eye text-3xl text-white"></i>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-4">Our Vision</h3>
                        <p class="text-[#6B6B6B] mb-6">To become a leading innovation company recognized for transforming ideas into powerful, practical technologies that shape a smarter and more inclusive future — starting with Africa and expanding globally.</p>
                        
                        <div class="flex items-center justify-between mt-8 pt-4 border-t border-[#E5E0D9]">
                            <span class="text-sm text-[#6B6B6B]">Africa First</span>
                            <i class="fas fa-arrow-right text-[#2C5F2D]"></i>
                            <span class="text-sm text-[#6B6B6B]">Global Impact</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">What We Stand For</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Our Core <span class="text-[#8B5A2B]">Values</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">These values shape our culture, guide our decisions, and define how we work.</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-6">
                <!-- Value 1 -->
                <div class="text-center p-6">
                    <div class="w-20 h-20 mx-auto bg-[#F9F5F0] rounded-2xl flex items-center justify-center mb-4">
                        <i class="fas fa-heart text-3xl text-[#8B5A2B]"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Purpose First</h3>
                    <p class="text-sm text-[#6B6B6B]">We build what matters, not just what's trendy.</p>
                </div>
                
                <!-- Value 2 -->
                <div class="text-center p-6">
                    <div class="w-20 h-20 mx-auto bg-[#F9F5F0] rounded-2xl flex items-center justify-center mb-4">
                        <i class="fas fa-hand-holding-heart text-3xl text-[#8B5A2B]"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Radical Empathy</h3>
                    <p class="text-sm text-[#6B6B6B]">We design for real people, not personas.</p>
                </div>
                
                <!-- Value 3 -->
                <div class="text-center p-6">
                    <div class="w-20 h-20 mx-auto bg-[#F9F5F0] rounded-2xl flex items-center justify-center mb-4">
                        <i class="fas fa-leaf text-3xl text-[#8B5A2B]"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Rooted in Africa</h3>
                    <p class="text-sm text-[#6B6B6B]">Our foundation and inspiration.</p>
                </div>
                
                <!-- Value 4 -->
                <div class="text-center p-6">
                    <div class="w-20 h-20 mx-auto bg-[#F9F5F0] rounded-2xl flex items-center justify-center mb-4">
                        <i class="fas fa-infinity text-3xl text-[#8B5A2B]"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Endless Curiosity</h3>
                    <p class="text-sm text-[#6B6B6B]">We never stop learning and exploring.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section (Optional) -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">The People</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Behind <span class="text-[#8B5A2B]">Vumbi</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">A diverse team of builders, dreamers, and problem-solvers united by a shared purpose.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <!-- Team Member 1 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <div class="h-64 bg-gradient-to-br from-[#8B5A2B] to-[#D98C5F] relative">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <i class="fas fa-user-circle text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-1">Founder</h3>
                        <p class="text-[#8B5A2B] text-sm mb-3">Lead Visionary</p>
                        <p class="text-[#6B6B6B] text-sm">Building technology that serves overlooked communities across Africa.</p>
                    </div>
                </div>
                
                <!-- Team Member 2 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <div class="h-64 bg-gradient-to-br from-[#2C5F2D] to-[#8B5A2B] relative">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <i class="fas fa-user-circle text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-1">Tech Lead</h3>
                        <p class="text-[#8B5A2B] text-sm mb-3">Engineering</p>
                        <p class="text-[#6B6B6B] text-sm">Crafting robust, scalable solutions with modern technologies.</p>
                    </div>
                </div>
                
                <!-- Team Member 3 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <div class="h-64 bg-gradient-to-br from-[#D98C5F] to-[#C7B5A6] relative">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <i class="fas fa-user-circle text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-1">Community Lead</h3>
                        <p class="text-[#8B5A2B] text-sm mb-3">Partnerships</p>
                        <p class="text-[#6B6B6B] text-sm">Connecting with local partners and talent across Africa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-[#8B5A2B] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <i class="fas fa-dust-storm absolute top-10 left-10 text-white text-8xl"></i>
            <i class="fas fa-dust-storm absolute bottom-10 right-10 text-white text-8xl rotate-180"></i>
        </div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Join Us in Building</h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">From overlooked places, we're building remarkable solutions. Be part of the journey.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/contact" class="bg-white text-[#8B5A2B] px-8 py-3 rounded-full hover:bg-[#F9F5F0] transition font-medium inline-flex items-center gap-2">
                    Partner With Us <i class="fas fa-arrow-right"></i>
                </a>
                <a href="/ecosystem" class="border-2 border-white text-white px-8 py-3 rounded-full hover:bg-white hover:text-[#8B5A2B] transition font-medium">
                    Explore Ecosystem
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Add any page-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add scroll animations or other interactions
    });
</script>
@endpush