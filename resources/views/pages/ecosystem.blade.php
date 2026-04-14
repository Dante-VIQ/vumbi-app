@extends('layouts.app')

@section('title', 'The Vumbi Ecosystem - Innovation Foundry | SkillDNA, Discover Africa & More')
@section('description', 'Explore the Vumbi Ventures ecosystem: SkillDNA (flagship AI learning platform), Discover Africa (destination discovery), Field Notes (blog), Talent Pipeline, and Partner Program. An innovation hub building from Africa.')
@section('keywords', 'Vumbi ecosystem, SkillDNA, Discover Africa, Field Notes, Talent Pipeline, Partner Program, African innovation hub, tech ecosystem')

@push('styles')
<style>
    .ecosystem-node {
        transition: all 0.3s ease;
    }
    
    .ecosystem-node:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgba(139, 90, 43, 0.1), 0 10px 10px -5px rgba(139, 90, 43, 0.04);
    }
    
    .connection-line {
        position: relative;
    }
    
    .connection-line::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -50%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #8B5A2B, #D98C5F);
        transform: translateY(-50%);
        opacity: 0.3;
    }
    
    .platform-card {
        background: linear-gradient(135deg, #FFFFFF 0%, #F9F5F0 100%);
        border: 1px solid rgba(139, 90, 43, 0.1);
    }
    
    .hub-center {
        background: radial-gradient(circle at 30% 30%, #8B5A2B, #6B421F);
        box-shadow: 0 20px 40px -10px rgba(139, 90, 43, 0.3);
    }
    
    .pulse-ring {
        animation: pulse-ring 2s infinite;
    }
    
    @keyframes pulse-ring {
        0% {
            box-shadow: 0 0 0 0 rgba(139, 90, 43, 0.3);
        }
        70% {
            box-shadow: 0 0 0 20px rgba(139, 90, 43, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(139, 90, 43, 0);
        }
    }
    
    .floating-particle {
        position: absolute;
        background: rgba(139, 90, 43, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }
</style>
@endpush

@push('schema')
    @php
        $ecosystemSchema = [
            "@context" => "https://schema.org",
            "@type" => "ItemList",
            "name" => "Vumbi Ventures Ecosystem",
            "description" => "A collection of digital platforms built by Vumbi Ventures including SkillDNA, Discover Africa, Field Notes, Talent Pipeline, and Partner Program.",
            "numberOfItems" => 5,
            "itemListElement" => [
                [
                    "@type" => "ListItem",
                    "position" => 1,
                    "item" => [
                        "@type" => "Product",
                        "name" => "VumbiDNA",
                        "description" => "AI-powered skill development platform connecting parents, children, and tutors."
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 2,
                    "item" => [
                        "@type" => "Product",
                        "name" => "Discover Africa",
                        "description" => "Destination discovery platform for exploring African places with rich context."
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "item" => [
                        "@type" => "Blog",
                        "name" => "Field Notes",
                        "description" => "Digital publication documenting overlooked people, cultures, and destinations."
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 4,
                    "item" => [
                        "@type" => "Service",
                        "name" => "Talent Pipeline",
                        "description" => "Discovering and nurturing talent across Africa."
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 5,
                    "item" => [
                        "@type" => "Service",
                        "name" => "Partner Program",
                        "description" => "Connecting local African businesses with travelers and learners."
                    ]
                ]
            ]
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($ecosystemSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-white overflow-hidden">
        <!-- Floating particles background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="floating-particle w-64 h-64 -top-20 -right-20 bg-[#8B5A2B]/5 rounded-full animate-pulse-slow"></div>
            <div class="floating-particle w-96 h-96 -bottom-40 -left-40 bg-[#D98C5F]/5 rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block mb-6">
                    <span class="bg-[#E5E0D9] text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-project-diagram mr-2"></i>Our Ecosystem
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    The Vumbi <span class="text-[#8B5A2B] relative">
                        Ecosystem
                        <span class="absolute bottom-2 left-0 w-full h-3 bg-[#D98C5F]/20 -z-10"></span>
                    </span>
                </h1>
                <p class="text-xl text-[#6B6B6B] leading-relaxed max-w-3xl mx-auto">
                    An innovation hub developing and managing a growing ecosystem of digital platforms. 
                    Each product solves different challenges, united by a common purpose.
                </p>
            </div>
        </div>
    </section>

    <!-- Ecosystem Visualization -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <!-- Ecosystem Hub Diagram -->
            <div class="max-w-5xl mx-auto relative">
                <!-- Center Hub -->
                <div class="text-center mb-16 relative">
                    <div class="hub-center inline-block text-white px-12 py-8 rounded-3xl pulse-ring relative z-10">
                        <i class="fas fa-dust-storm text-4xl mb-3 opacity-80"></i>
                        <h2 class="text-3xl font-bold">VUMBI VENTURES</h2>
                        <p class="text-lg opacity-90">Innovation Foundry</p>
                    </div>
                    
                    <!-- Connection lines (decorative) -->
                    <div class="absolute left-1/2 top-1/2 w-[80%] h-[80%] border-2 border-[#8B5A2B]/20 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute left-1/2 top-1/2 w-[60%] h-[60%] border-2 border-[#D98C5F]/20 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                </div>

                <!-- Ecosystem Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative">
                    <!-- SkillDNA -->
                    <div class="platform-card rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition ecosystem-node">
                        <div class="h-2 bg-gradient-to-r from-[#8B5A2B] to-[#D98C5F]"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-[#8B5A2B] rounded-xl flex items-center justify-center">
                                    <i class="fas fa-dna text-2xl text-white"></i>
                                </div>
                                <span class="bg-[#8B5A2B]/10 text-[#8B5A2B] px-3 py-1 rounded-full text-xs font-semibold">Flagship</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">VumbiDNA</h3>
                            <p class="text-[#6B6B6B] mb-4">AI-powered skill development platform connecting parents, children, and tutors.</p>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-users text-[#8B5A2B] w-5"></i>
                                    <span>Parents, Children, Tutors</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-robot text-[#8B5A2B] w-5"></i>
                                    <span>AI/ML Content Recommendations</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-chart-line text-[#8B5A2B] w-5"></i>
                                    <span>Progress Tracking</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Laravel</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Livewire</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">AI/ML</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">MySQL</span>
                            </div>

                            <a href="#" class="inline-flex items-center text-[#8B5A2B] font-semibold hover:underline">
                                Explore SkillDNA <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Discover Africa -->
                    <div class="platform-card rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition ecosystem-node">
                        <div class="h-2 bg-gradient-to-r from-[#2C5F2D] to-[#8B5A2B]"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-[#2C5F2D] rounded-xl flex items-center justify-center">
                                    <i class="fas fa-mountain text-2xl text-white"></i>
                                </div>
                                <span class="bg-[#2C5F2D]/10 text-[#2C5F2D] px-3 py-1 rounded-full text-xs font-semibold">Travel</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">Discover Africa</h3>
                            <p class="text-[#6B6B6B] mb-4">Destination discovery platform for exploring African places with rich context and seamless booking.</p>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-map-marker-alt text-[#2C5F2D] w-5"></i>
                                    <span>Places, Hotels, Local Businesses</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-handshake text-[#2C5F2D] w-5"></i>
                                    <span>Vumbi Partner Network</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-dollar-sign text-[#2C5F2D] w-5"></i>
                                    <span>Affiliate Commissions</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Google Places API</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Travelpayouts</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Laravel</span>
                            </div>

                            <a href="#" class="inline-flex items-center text-[#2C5F2D] font-semibold hover:underline">
                                Explore Discover Africa <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Field Notes -->
                    <div class="platform-card rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition ecosystem-node">
                        <div class="h-2 bg-gradient-to-r from-[#D98C5F] to-[#C7B5A6]"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-[#D98C5F] rounded-xl flex items-center justify-center">
                                    <i class="fas fa-pen-fancy text-2xl text-white"></i>
                                </div>
                                <span class="bg-[#D98C5F]/10 text-[#D98C5F] px-3 py-1 rounded-full text-xs font-semibold">Blog</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">Field Notes</h3>
                            <p class="text-[#6B6B6B] mb-4">Digital publication documenting the overlooked people, cultures, and destinations that inspire our work.</p>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-user text-[#D98C5F] w-5"></i>
                                    <span>People Profiles</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-globe text-[#D98C5F] w-5"></i>
                                    <span>Culture & Traditions</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-map text-[#D98C5F] w-5"></i>
                                    <span>Hidden Destinations</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Storytelling</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Documentary</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Laravel</span>
                            </div>

                            <a href="/" class="inline-flex items-center text-[#D98C5F] font-semibold hover:underline">
                                Read Field Notes <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Talent Pipeline -->
                    <div class="platform-card rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition ecosystem-node">
                        <div class="h-2 bg-gradient-to-r from-[#8B5A2B] to-[#2C5F2D]"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-[#8B5A2B] to-[#2C5F2D] rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user-tie text-2xl text-white"></i>
                                </div>
                                <span class="bg-[#8B5A2B]/10 text-[#8B5A2B] px-3 py-1 rounded-full text-xs font-semibold">Network</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">Talent Pipeline</h3>
                            <p class="text-[#6B6B6B] mb-4">Actively discovering and nurturing talent across Africa. Experts are featured in our Talent Showcase and invited to become SkillDNA tutors.</p>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-search text-[#8B5A2B] w-5"></i>
                                    <span>Discovery via Field Notes</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-clipboard-check text-[#8B5A2B] w-5"></i>
                                    <span>Verification Process</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-chalkboard-teacher text-[#8B5A2B] w-5"></i>
                                    <span>SkillDNA Tutor Pathway</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Outreach</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Referrals</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Applications</span>
                            </div>

                            <a href="/talent" class="inline-flex items-center text-[#8B5A2B] font-semibold hover:underline">
                                Join Talent Network <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Partner Program -->
                    <div class="platform-card rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition ecosystem-node">
                        <div class="h-2 bg-gradient-to-r from-[#D98C5F] to-[#8B5A2B]"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-[#D98C5F] to-[#8B5A2B] rounded-xl flex items-center justify-center">
                                    <i class="fas fa-handshake text-2xl text-white"></i>
                                </div>
                                <span class="bg-[#D98C5F]/10 text-[#D98C5F] px-3 py-1 rounded-full text-xs font-semibold">Business</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">Partner Program</h3>
                            <p class="text-[#6B6B6B] mb-4">Connecting local African businesses with travelers and learners through our platforms.</p>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-hotel text-[#D98C5F] w-5"></i>
                                    <span>Hotels & Lodges</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-route text-[#D98C5F] w-5"></i>
                                    <span>Tour Operators</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-chalkboard-teacher text-[#D98C5F] w-5"></i>
                                    <span>Local Guides & Tutors</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Priority Placement</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Verified Badge</span>
                                <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Analytics</span>
                            </div>

                            <a href="/" class="inline-flex items-center text-[#D98C5F] font-semibold hover:underline">
                                Become a Partner <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Future Products (placeholder) -->
                    <div class="platform-card rounded-2xl overflow-hidden shadow-sm border-2 border-dashed border-[#C7B5A6] bg-transparent hover:shadow-xl transition ecosystem-node">
                        <div class="p-6 text-center">
                            <div class="w-20 h-20 mx-auto bg-[#F9F5F0] rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-plus-circle text-3xl text-[#C7B5A6]"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 text-[#C7B5A6]">Future Products</h3>
                            <p class="text-[#6B6B6B] mb-4">New platforms in development. Coming soon to the ecosystem.</p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="text-sm text-[#6B6B6B]">• Community Platform</div>
                                <div class="text-sm text-[#6B6B6B]">• Mobile Apps</div>
                                <div class="text-sm text-[#6B6B6B]">• More to announce</div>
                            </div>

                            <span class="inline-block bg-[#F9F5F0] px-4 py-2 rounded-full text-sm text-[#6B6B6B]">
                                In Development
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It All Connects -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Synergy</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">How It All <span class="text-[#8B5A2B]">Connects</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Our platforms work together, creating a powerful ecosystem that serves multiple needs.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Connection 1 -->
                <div class="bg-[#F9F5F0] p-8 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#8B5A2B]/5 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-[#8B5A2B] rounded-xl flex items-center justify-center">
                                <i class="fas fa-dna text-xl text-white"></i>
                            </div>
                            <i class="fas fa-arrow-right text-2xl text-[#8B5A2B]"></i>
                            <div class="w-12 h-12 bg-[#D98C5F] rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-tie text-xl text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-3">SkillDNA ⇄ Talent Pipeline</h3>
                        <p class="text-[#6B6B6B]">Talent discovered through Field Notes and applications are vetted and invited to become SkillDNA tutors, creating a steady stream of qualified educators.</p>
                    </div>
                </div>

                <!-- Connection 2 -->
                <div class="bg-[#F9F5F0] p-8 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#2C5F2D]/5 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-[#2C5F2D] rounded-xl flex items-center justify-center">
                                <i class="fas fa-mountain text-xl text-white"></i>
                            </div>
                            <i class="fas fa-arrow-right text-2xl text-[#2C5F2D]"></i>
                            <div class="w-12 h-12 bg-[#8B5A2B] rounded-xl flex items-center justify-center">
                                <i class="fas fa-handshake text-xl text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Discover Africa ⇄ Partner Program</h3>
                        <p class="text-[#6B6B6B]">Local businesses become Vumbi Partners and get priority placement on Discover Africa, while travelers get authentic local experiences.</p>
                    </div>
                </div>

                <!-- Connection 3 -->
                <div class="bg-[#F9F5F0] p-8 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#D98C5F]/5 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-[#D98C5F] rounded-xl flex items-center justify-center">
                                <i class="fas fa-pen-fancy text-xl text-white"></i>
                            </div>
                            <i class="fas fa-arrow-right text-2xl text-[#D98C5F]"></i>
                            <div class="w-12 h-12 bg-[#8B5A2B] rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-tie text-xl text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Field Notes ⇄ Talent Pipeline</h3>
                        <p class="text-[#6B6B6B]">People featured in Field Notes are automatically considered for the Talent Pipeline, creating a natural discovery channel.</p>
                    </div>
                </div>

                <!-- Connection 4 -->
                <div class="bg-[#F9F5F0] p-8 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#8B5A2B]/5 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-[#8B5A2B] rounded-xl flex items-center justify-center">
                                <i class="fas fa-dna text-xl text-white"></i>
                            </div>
                            <i class="fas fa-arrow-right text-2xl text-[#8B5A2B]"></i>
                            <div class="w-12 h-12 bg-[#2C5F2D] rounded-xl flex items-center justify-center">
                                <i class="fas fa-mountain text-xl text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-3">SkillDNA ⇄ Discover Africa</h3>
                        <p class="text-[#6B6B6B]">Educational content about African destinations in SkillDNA drives interest and traffic to Discover Africa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SkillDNA Deep Dive -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Flagship Product</span>
                    <h2 class="text-4xl font-bold mt-2 mb-4">SkillDNA <span class="text-[#8B5A2B]">Deep Dive</span></h2>
                    <p class="text-[#6B6B6B] text-lg mb-6">Our flagship platform is a skill-focused ecosystem connecting parents, children, and tutors through intelligent learning.</p>
                    
                    <div class="space-y-6">
                        <!-- Feature 1 -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-robot text-xl text-[#8B5A2B]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">AI/ML Content Recommendations</h4>
                                <p class="text-sm text-[#6B6B6B]">Personalized learning paths based on interests, watch history, and similar users.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users text-xl text-[#8B5A2B]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Multi-Role System</h4>
                                <p class="text-sm text-[#6B6B6B]">Parents manage children and assignments, children learn, tutors guide (invitation only).</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-coins text-xl text-[#8B5A2B]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Financial Literacy</h4>
                                <p class="text-sm text-[#6B6B6B]">Mandatory money education component for all learners.</p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-heart text-xl text-[#8B5A2B]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Social Impact</h4>
                                <p class="text-sm text-[#6B6B6B]">Learning activities generate contributions to community projects.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="#" class="bg-[#8B5A2B] text-white px-8 py-3 rounded-full hover:bg-[#6B421F] transition font-medium inline-flex items-center gap-2">
                            Learn More About SkillDNA <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <!-- SkillDNA Visualization -->
                    <div class="bg-white p-8 rounded-3xl shadow-xl">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center p-4 bg-[#F9F5F0] rounded-xl">
                                <i class="fas fa-user-friends text-3xl text-[#8B5A2B] mb-2"></i>
                                <span class="block font-semibold">Parents</span>
                                <span class="text-xs text-[#6B6B6B]">Manage, track, assign</span>
                            </div>
                            <div class="text-center p-4 bg-[#F9F5F0] rounded-xl">
                                <i class="fas fa-child text-3xl text-[#8B5A2B] mb-2"></i>
                                <span class="block font-semibold">Children</span>
                                <span class="text-xs text-[#6B6B6B]">Learn, build skills</span>
                            </div>
                            <div class="text-center p-4 bg-[#F9F5F0] rounded-xl">
                                <i class="fas fa-chalkboard-teacher text-3xl text-[#8B5A2B] mb-2"></i>
                                <span class="block font-semibold">Tutors</span>
                                <span class="text-xs text-[#6B6B6B]">Guide, create content</span>
                            </div>
                            <div class="text-center p-4 bg-[#F9F5F0] rounded-xl">
                                <i class="fas fa-crown text-3xl text-[#8B5A2B] mb-2"></i>
                                <span class="block font-semibold">Master Admin</span>
                                <span class="text-xs text-[#6B6B6B]">Full system oversight</span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-[#E5E0D9]">
                            <div>
                                <div class="text-2xl font-bold text-[#8B5A2B]">1,000+</div>
                                <div class="text-xs text-[#6B6B6B]">Active Learners</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-[#8B5A2B]">50+</div>
                                <div class="text-xs text-[#6B6B6B]">Verified Tutors</div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative elements -->
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-[#8B5A2B]/10 rounded-full"></div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-[#D98C5F]/10 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Discover Africa Preview -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1">
                    <div class="bg-[#F9F5F0] p-6 rounded-3xl">
                        <!-- Sample location card -->
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm">
                            <div class="h-32 bg-gradient-to-r from-[#2C5F2D] to-[#8B5A2B] relative">
                                <div class="absolute inset-0 bg-black/20"></div>
                                <div class="absolute bottom-4 left-4 text-white">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span class="font-semibold">Nakuru, Kenya</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 text-sm text-[#6B6B6B] mb-3">
                                    <i class="fas fa-hotel"></i>
                                    <span>12 hotels</span>
                                    <i class="fas fa-utensils ml-2"></i>
                                    <span>8 restaurants</span>
                                    <i class="fas fa-tree ml-2"></i>
                                    <span>5 attractions</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-[#2C5F2D]">Vumbi Partner</span>
                                    <span class="text-xs bg-[#F9F5F0] px-2 py-1 rounded-full">Verified</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data sources -->
                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <div class="text-center p-2 bg-white rounded-lg text-xs">
                                <i class="fab fa-google text-[#8B5A2B] mb-1"></i>
                                <span class="block">Google Places</span>
                            </div>
                            <div class="text-center p-2 bg-white rounded-lg text-xs">
                                <i class="fas fa-plane text-[#8B5A2B] mb-1"></i>
                                <span class="block">Travelpayouts</span>
                            </div>
                            <div class="text-center p-2 bg-white rounded-lg text-xs">
                                <i class="fas fa-handshake text-[#8B5A2B] mb-1"></i>
                                <span class="block">Awin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 md:order-2">
                    <span class="text-[#2C5F2D] font-semibold tracking-wider uppercase">Destination Discovery</span>
                    <h2 class="text-4xl font-bold mt-2 mb-4">Discover <span class="text-[#2C5F2D]">Africa</span></h2>
                    <p class="text-[#6B6B6B] text-lg mb-6">A guided discovery engine that helps users explore African places — from major cities to overlooked villages — with rich context and seamless booking.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#2C5F2D]/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-route text-[#2C5F2D]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Browse by Country & Region</h4>
                                <p class="text-sm text-[#6B6B6B]">Kenya, Tanzania, Uganda and more</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#2C5F2D]/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hotel text-[#2C5F2D]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Hotels, Tours & Local Businesses</h4>
                                <p class="text-sm text-[#6B6B6B]">Affiliate links + Vumbi Partners</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#2C5F2D]/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-percent text-[#2C5F2D]"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Commission Model</h4>
                                <p class="text-sm text-[#6B6B6B]">Earn from bookings and referrals</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="#" class="bg-[#2C5F2D] text-white px-8 py-3 rounded-full hover:bg-[#1E4A1E] transition font-medium inline-flex items-center gap-2">
                            Explore Discover Africa <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Technology Stack -->
    <section class="py-20 dust-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">Built With</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Ecosystem <span class="text-[#8B5A2B]">Technology</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Modern, robust technologies powering our entire ecosystem.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Vumbi Ventures -->
                <div class="bg-white p-6 rounded-2xl">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-[#8B5A2B] rounded-lg flex items-center justify-center">
                            <span class="text-white text-sm">VV</span>
                        </div>
                        Vumbi Ventures (Public Site)
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Frontend:</span>
                            <span class="font-medium">HTML, Tailwind CSS</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Templating:</span>
                            <span class="font-medium">Laravel Blade</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Hosting:</span>
                            <span class="font-medium">Laravel Forge</span>
                        </div>
                    </div>
                </div>

                <!-- Discover Africa -->
                <div class="bg-white p-6 rounded-2xl">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-[#2C5F2D] rounded-lg flex items-center justify-center">
                            <i class="fas fa-mountain text-white text-sm"></i>
                        </div>
                        Discover Africa
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Mapping:</span>
                            <span class="font-medium">Google Maps API</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Places Data:</span>
                            <span class="font-medium">Google Places API</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Affiliates:</span>
                            <span class="font-medium">Travelpayouts, Awin</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Backend:</span>
                            <span class="font-medium">Laravel</span>
                        </div>
                    </div>
                </div>

                <!-- SkillDNA -->
                <div class="bg-white p-6 rounded-2xl">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-[#8B5A2B] rounded-lg flex items-center justify-center">
                            <i class="fas fa-dna text-white text-sm"></i>
                        </div>
                        SkillDNA
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Framework:</span>
                            <span class="font-medium">Laravel 12</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Frontend:</span>
                            <span class="font-medium">Livewire Volt</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">Database:</span>
                            <span class="font-medium">MySQL</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B]">AI/ML:</span>
                            <span class="font-medium">Custom Service</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Roadmap -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#8B5A2B] font-semibold tracking-wider uppercase">What's Next</span>
                <h2 class="text-4xl font-bold mt-2 mb-4">Ecosystem <span class="text-[#8B5A2B]">Roadmap</span></h2>
                <p class="text-[#6B6B6B] text-lg max-w-2xl mx-auto">Our vision for growth and expansion across the continent and beyond.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <!-- Phase 1 -->
                <div class="relative">
                    <div class="bg-[#F9F5F0] p-6 rounded-2xl h-full">
                        <div class="w-12 h-12 bg-[#8B5A2B] rounded-xl flex items-center justify-center mb-4">
                            <span class="text-white font-bold">1</span>
                        </div>
                        <h3 class="font-bold mb-3">Phase 1: Foundation</h3>
                        <p class="text-sm text-[#6B6B6B] mb-4">(Current)</p>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                                <span>Brand identity defined</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                                <span>Site architecture planned</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                                <span>Homepage & ecosystem pages</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-[#2C5F2D]"></i>
                                <span>Field Notes template</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Phase 2 -->
                <div class="relative">
                    <div class="bg-[#F9F5F0] p-6 rounded-2xl h-full">
                        <div class="w-12 h-12 bg-[#D98C5F] rounded-xl flex items-center justify-center mb-4">
                            <span class="text-white font-bold">2</span>
                        </div>
                        <h3 class="font-bold mb-3">Phase 2: Discover Africa</h3>
                        <p class="text-sm text-[#6B6B6B] mb-4">(Next)</p>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#D98C5F]"></i>
                                <span>Complete East Africa structure</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#D98C5F]"></i>
                                <span>Google Places API integration</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#D98C5F]"></i>
                                <span>Travelpayouts integration</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#D98C5F]"></i>
                                <span>Partner onboarding system</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Phase 3 -->
                <div class="relative">
                    <div class="bg-[#F9F5F0] p-6 rounded-2xl h-full">
                        <div class="w-12 h-12 bg-[#2C5F2D] rounded-xl flex items-center justify-center mb-4">
                            <span class="text-white font-bold">3</span>
                        </div>
                        <h3 class="font-bold mb-3">Phase 3: SkillDNA</h3>
                        <p class="text-sm text-[#6B6B6B] mb-4">(Development)</p>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#2C5F2D]"></i>
                                <span>User authentication & roles</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#2C5F2D]"></i>
                                <span>Parent/child management</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#2C5F2D]"></i>
                                <span>AI recommendation engine</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#2C5F2D]"></i>
                                <span>Tutor verification workflow</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Phase 4 -->
                <div class="relative">
                    <div class="bg-[#F9F5F0] p-6 rounded-2xl h-full">
                        <div class="w-12 h-12 bg-[#C7B5A6] rounded-xl flex items-center justify-center mb-4">
                            <span class="text-white font-bold">4</span>
                        </div>
                        <h3 class="font-bold mb-3">Phase 4: Scaling</h3>
                        <p class="text-sm text-[#6B6B6B] mb-4">(Future)</p>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#C7B5A6]"></i>
                                <span>Unify partner database</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#C7B5A6]"></i>
                                <span>Expand continent-wide</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#C7B5A6]"></i>
                                <span>Mobile apps</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-circle-notch text-[#C7B5A6]"></i>
                                <span>Community features</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-[#8B5A2B] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <i class="fas fa-dust-storm absolute top-10 left-10 text-white text-8xl"></i>
            <i class="fas fa-dna absolute bottom-10 right-10 text-white text-8xl"></i>
        </div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Join Our Ecosystem</h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">Whether as a partner, tutor, or investor — be part of building remarkable solutions from Africa.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/contact" class="bg-white text-[#8B5A2B] px-8 py-3 rounded-full hover:bg-[#F9F5F0] transition font-medium inline-flex items-center gap-2">
                    Get Involved <i class="fas fa-arrow-right"></i>
                </a>
                <a href="/" class="border-2 border-white text-white px-8 py-3 rounded-full hover:bg-white hover:text-[#8B5A2B] transition font-medium">
                    Become a Partner
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