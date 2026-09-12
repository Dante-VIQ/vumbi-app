@extends('layouts.app')

@section('title', 'Contact Vumbi Ventures | Safari & Digital Growth Experts')
@section('description', 'Connect with Vumbi Ventures for bespoke African safaris, data-driven SEO consulting, and custom software development. Book your strategy call today.')
@section('keywords', 'contact Vumbi Ventures, African safari booking, SEO strategy call, custom web development Kenya, digital growth Africa')
@section('robots', 'index, follow')

@push('schema')
@php
    $pageSchemas = [];

    $pageSchemas[] = [
        "@context" => "https://schema.org",
        "@type" => "ContactPage",
        "name" => "Contact Vumbi Ventures",
        "description" => "Connect with Vumbi Ventures for bespoke African safaris, data-driven SEO consulting, and custom software development.",
        "url" => url()->current(),
        "mainEntity" => [
            "@type" => "ContactPoint",
            "contactType" => "Customer Service & Advisory",
            "telephone" => "+254-734-591543",
            "email" => "info@vumbiventures.com",
            "availableLanguage" => ["English", "Swahili"]
        ]
    ];
@endphp
@endpush

@push('styles')
<style>
    :root {
        --earth: #8B5A2B;
        --earth-deep: #5C3A1E;
        --ink: #1A1A1A;
        --muted: #5C5C5C;
        --dust: #F5EFE6;
    }

    body {
        background: #FCFAF7;
        font-family: 'Inter', sans-serif;
        color: var(--ink);
    }

    .grain {
        background-image: url("https://grainy-gradients.vercel.app/noise.svg");
        opacity: 0.03;
        pointer-events: none;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }

    .btn-primary {
        background: var(--earth);
        color: white;
        border: none;
        font-weight: 500;
        transition: all 0.3s;
        box-shadow: 0 6px 14px rgba(139, 90, 43, 0.12);
    }
    .btn-primary:hover {
        background: var(--earth-deep);
        transform: translateY(-2px);
        box-shadow: 0 14px 24px rgba(92, 58, 30, 0.18);
    }

    .input-field {
        width: 100%;
        padding: 0.75rem 1rem;
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.75rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-field:focus {
        outline: none;
        border-color: var(--earth);
        box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);
    }

    .service-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .service-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -12px rgba(139, 90, 43, 0.12);
        border-color: rgba(139, 90, 43, 0.3);
    }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="relative overflow-hidden pt-24 pb-12 md:pt-32 md:pb-16">
    <div class="absolute inset-0 grain"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(139,90,43,0.05),transparent_50%)]"></div>

    <div class="relative container mx-auto px-6 text-center max-w-4xl">
        <span class="inline-flex items-center gap-2 text-sm bg-white/80 backdrop-blur-sm border border-black/5 px-4 py-2 rounded-full shadow-sm">
            <span class="w-2 h-2 bg-[#8B5A2B] rounded-full animate-pulse"></span>
            Clear the Path Forward
        </span>

        <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold mt-6 leading-tight tracking-tight text-[#1A1A1A]">
            Chart Your Course to <br class="hidden sm:inline"/>
            <span class="text-[#8B5A2B] relative inline-block">
                Extraordinary
                <span class="absolute -bottom-1 left-0 w-full h-1 bg-[#D98C5F]/30 rounded-full"></span>
            </span> 
            Adventures & Growth
        </h1>

        <p class="mt-6 text-[#5C5C5C] text-lg max-w-2xl mx-auto leading-relaxed">
            At <strong>Vumbi Ventures</strong>, we operate at the intersection of wild exploration and digital innovation. Whether planning a luxury safari or scaling your search performance, start the conversation on your terms.
        </p>
    </div>
</section>

{{-- Consultation Paths --}}
<section class="container mx-auto px-6 py-8">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <h2 class="text-2xl md:text-3xl font-semibold text-[#1A1A1A]">Choose Your Consultation Path</h2>
        <p class="text-[#5C5C5C] mt-2">Select a focus area below to book a direct advisory call or submit an inquiry.</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Path 1: Safari --}}
        <div class="service-card p-8 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-compass text-[#8B5A2B] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">1. Safari & Expeditions</h3>
                <p class="text-xs font-semibold uppercase tracking-wider text-[#8B5A2B] mb-4">Adventures & Private Retreats</p>
                <p class="text-sm text-[#5C5C5C] mb-6">
                    Crafted for luxury travelers and groups seeking custom itineraries—from tracking the Great Migration to private coastal stays in Zanzibar.
                </p>
                <ul class="text-xs text-[#5C5C5C] space-y-2 mb-8 bg-[#FCFAF7] p-4 rounded-xl">
                    <li class="flex items-start gap-2">
                        <span class="text-[#8B5A2B] font-bold">✓</span>
                        <span><strong>20-min Briefing:</strong> Logistics, seasonal movements, & transparent pricing ($500–$5,000+/person).</span>
                    </li>
                </ul>
            </div>
            <a href="#contact-form" onclick="setInquiryType('travel', 'Safari & Travel Booking')" class="btn-primary text-center py-3 rounded-xl text-sm w-full block">
                Book Safari Consultation
            </a>
        </div>

        {{-- Path 2: SEO & Digital Growth --}}
        <div class="service-card p-8 flex flex-col justify-between border-2 border-[#8B5A2B]/20 relative">
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#8B5A2B] text-white text-[10px] font-bold uppercase px-3 py-1 rounded-full tracking-wider">Most Popular</span>
            <div>
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-[#8B5A2B] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">2. SEO & Growth Strategy</h3>
                <p class="text-xs font-semibold uppercase tracking-wider text-[#8B5A2B] mb-4">Tourism & Digital Enterprise</p>
                <p class="text-sm text-[#5C5C5C] mb-6">
                    Outpace competitors with rigorous keyword architecture, authoritative search performance optimizations, and high-intent booking strategies.
                </p>
                <ul class="text-xs text-[#5C5C5C] space-y-2 mb-8 bg-[#FCFAF7] p-4 rounded-xl">
                    <li class="flex items-start gap-2">
                        <span class="text-[#8B5A2B] font-bold">✓</span>
                        <span><strong>30-min Audit:</strong> Authority analysis, traffic bottleneck checks, & growth strategy.</span>
                    </li>
                </ul>
            </div>
            <a href="#contact-form" onclick="setInquiryType('seo', 'SEO & Digital Growth Strategy')" class="btn-primary text-center py-3 rounded-xl text-sm w-full block">
                Schedule SEO Discovery Call
            </a>
        </div>

        {{-- Path 3: Software & Tech --}}
        <div class="service-card p-8 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-code text-[#8B5A2B] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">3. Software & Tech Solutions</h3>
                <p class="text-xs font-semibold uppercase tracking-wider text-[#8B5A2B] mb-4">Enterprise & Web Platforms</p>
                <p class="text-sm text-[#5C5C5C] mb-6">
                    Build reliable, scalable web applications, booking engine integrations, and custom software systems designed for high engagement.
                </p>
                <ul class="text-xs text-[#5C5C5C] space-y-2 mb-8 bg-[#FCFAF7] p-4 rounded-xl">
                    <li class="flex items-start gap-2">
                        <span class="text-[#8B5A2B] font-bold">✓</span>
                        <span><strong>Technical Scoping:</strong> Assessing stack selection, parameters, and timeline expectations.</span>
                    </li>
                </ul>
            </div>
            <a href="#contact-form" onclick="setInquiryType('web_dev', 'Custom Software Development')" class="btn-primary text-center py-3 rounded-xl text-sm w-full block">
                Schedule Technical Scoping Call
            </a>
        </div>
    </div>
</section>

{{-- Form + Quick Contact Sidebar --}}
<section id="contact-form" class="container mx-auto px-6 py-12 scroll-mt-12">
    <div class="grid lg:grid-cols-3 gap-8">
        
        {{-- Contact Form --}}
        <div class="lg:col-span-2">
            <div class="glass-panel rounded-3xl p-6 md:p-10 shadow-xl">
                <h2 class="text-2xl font-semibold mb-2">Send Us a Message</h2>
                <p class="text-sm text-[#5C5C5C] mb-6">Fill out your details and we will respond within 24 business hours.</p>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-[#5C5C5C] mb-1">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name" class="input-field" placeholder="Jane Doe">
                            @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#5C5C5C] mb-1">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" class="input-field" placeholder="jane@example.com">
                            @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-[#5C5C5C] mb-1">Phone Number (optional)</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" autocomplete="tel" class="input-field" placeholder="+254 XXX XXX XXX">
                        </div>
                        <div>
                            <label for="inquiry_type" class="block text-sm font-medium text-[#5C5C5C] mb-1">I'm interested in *</label>
                            <select name="inquiry_type" id="inquiry_type" required class="input-field">
                                <option value="">-- Please select --</option>
                                <option value="travel" {{ old('inquiry_type') == 'travel' ? 'selected' : '' }}>Safari & Travel Expeditions</option>
                                <option value="seo" {{ old('inquiry_type') == 'seo' ? 'selected' : '' }}>SEO & Digital Growth Strategy</option>
                                <option value="web_dev" {{ old('inquiry_type') == 'web_dev' ? 'selected' : '' }}>Custom Software & Web Development</option>
                                <option value="partnership" {{ old('inquiry_type') == 'partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                                <option value="other" {{ old('inquiry_type') == 'other' ? 'selected' : '' }}>Other Inquiry</option>
                            </select>
                            @error('inquiry_type') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="subject" class="block text-sm font-medium text-[#5C5C5C] mb-1">Subject</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="input-field" placeholder="How can we help?">
                        </div>
                        <div>
                            <label for="website" class="block text-sm font-medium text-[#5C5C5C] mb-1">Website URL (if applicable)</label>
                            <input type="url" name="website" id="website" value="{{ old('website') }}" class="input-field" placeholder="https://yourbrand.com">
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-[#5C5C5C] mb-1">Message / Project Details *</label>
                        <textarea name="message" id="message" rows="4" required class="input-field" placeholder="Tell us more about your goals, timing, or inquiry...">{{ old('message') }}</textarea>
                        @error('message') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="consent" id="consent" required class="w-4 h-4 text-[#8B5A2B] rounded border-gray-300 focus:ring-[#8B5A2B]">
                        <label for="consent" class="text-xs text-[#5C5C5C]">
                            I agree to be contacted about this inquiry. (We protect your data and never spam.)
                        </label>
                    </div>

                    <button type="submit" class="btn-primary w-full py-3.5 rounded-xl text-base font-semibold">
                        Submit Inquiry
                    </button>
                </form>
            </div>
        </div>

        {{-- Direct Contact Sidebar --}}
        <div class="space-y-6">
            {{-- Direct WhatsApp --}}
            <div class="service-card p-6 border-l-4 border-l-green-600">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center text-green-600">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-base">Instant WhatsApp Chat</h3>
                        <p class="text-xs text-[#5C5C5C]">Response: ~15 mins (EAT hours)</p>
                    </div>
                </div>
                <p class="text-xs text-[#5C5C5C] mb-4">Skip the email queue for quick service questions or urgent updates.</p>
                <a href="https://wa.me/254734591543" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 text-sm font-medium bg-green-600 text-white w-full py-2.5 rounded-xl hover:bg-green-700 transition">
                    Start WhatsApp Chat →
                </a>
            </div>

            {{-- Direct Emails --}}
            <div class="service-card p-6">
                <h3 class="font-semibold text-base mb-3">Direct Emails</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs text-[#5C5C5C]">General & Tech Inquiries:</p>
                        <a href="mailto:africa@vumbiventures.com" class="text-[#8B5A2B] font-medium hover:underline">africa@vumbiventures.com</a>
                    </div>
                    <div>
                        <p class="text-xs text-[#5C5C5C]">Safari & Travel Support:</p>
                        <a href="mailto:info@vumbiventures.com" class="text-[#8B5A2B] font-medium hover:underline">info@vumbiventures.com</a>
                    </div>
                </div>
            </div>

            {{-- Location --}}
            <div class="service-card p-6">
                <h3 class="font-semibold text-base mb-1">Primary Operating Hubs</h3>
                <p class="text-xs text-[#5C5C5C] mb-3">Kenya & Tanzania — Serving Global Clients</p>
                <p class="text-xs text-[#5C5C5C] flex items-center gap-1.5">
                    <i class="fas fa-map-marker-alt text-[#8B5A2B]"></i>
                    Nakuru & Nairobi, Kenya
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Map Location Section --}}
<section class="container mx-auto px-6 pb-12">
    <div class="rounded-3xl overflow-hidden border border-black/5 shadow-lg h-72 md:h-96 bg-[#E8DFD5]">
        <iframe width="100%" height="100%" title="Vumbi Ventures Location Map" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                src="https://www.openstreetmap.org/export/embed.html?bbox=36.05%2C-0.35%2C36.10%2C-0.30&amp;layer=mapnik&amp;marker=-0.325%2C36.075"
                style="border: 0;">
        </iframe>
    </div>
    <div class="flex flex-col sm:flex-row items-center justify-between mt-4 gap-2">
        <p class="text-xs text-[#5C5C5C]">
            📍 P448+FW, Nakuru, Kenya — Serving clients across Africa and globally.
        </p>
        <div class="flex gap-4 text-xs font-medium text-[#8B5A2B]">
            <a href="https://www.openstreetmap.org/directions?engine=graphhopper_car&route=%3B-0.325%2C36.075" target="_blank" rel="noopener" class="hover:underline">Directions (OSM) →</a>
            <a href="https://maps.google.com/?q=P448%2BFW+Nakuru%2C+Kenya" target="_blank" rel="noopener" class="hover:underline">Google Maps →</a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function setInquiryType(value, subjectText) {
        const select = document.getElementById('inquiry_type');
        const subject = document.getElementById('subject');
        
        if (select) {
            select.value = value;
        }
        if (subject && subjectText) {
            subject.value = `Inquiry regarding ${subjectText}`;
        }
    }
</script>
@endpush

@endsection