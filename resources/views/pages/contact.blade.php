@extends('layouts.app')


@section('title', 'Contact Vumbi Ventures | Start Your African Journey Today')
@section('description', 'Have questions about traveling in Africa? Ready to book your safari? Reach out to our team of local experts for a free, no-obligation consultation.')
@section('keywords', 'contact Vumbi Ventures, African travel inquiry, book a safari, travel consultation Africa, reach out, get in touch')

{{-- Optional: Override robots if this is a landing page, but usually "index, follow" is fine --}}
@section('robots', 'index, follow')



@section('content')

@push('schema')

@php
    $pageSchemas = [];

    $pageSchemas[] = [
        "@context" => "https://schema.org",
        "@type" => "ContactPage",
        "name" => "Contact Vumbi Ventures",
        "description" => "Get in touch with our travel experts. Ask about safaris, tours, or general inquiries.",
        "url" => url()->current(),
        "mainEntity" => [
            "@type" => "ContactPoint",
            "contactType" => "Customer Service",
            "telephone" => "+254-734-591543",
            "email" => "info@vumbiventures.com",
            "availableLanguage" => ["English", "Swahili"]
        ]
    ];

    // You can also add an FAQPage if your contact page has an FAQ section
    // (only if the FAQ is visible on the page)
    // Example: if ($hasFaqs) { ... }
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
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
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
        transition: border-color 0.2s;
    }
    .input-field:focus {
        outline: none;
        border-color: var(--earth);
        box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);
    }

    .contact-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s;
    }
    .contact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -12px rgba(139, 90, 43, 0.1);
    }

    .section-title {
        font-size: 2.25rem;
        font-weight: 600;
        letter-spacing: -0.02em;
    }
    @media (max-width: 768px) {
        .section-title { font-size: 1.9rem; }
    }
</style>
@endpush

{{-- ===================== HERO ===================== --}}
<section class="relative overflow-hidden pt-24 pb-12 md:pt-32 md:pb-16">
    <div class="absolute inset-0 grain"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(139,90,43,0.04),transparent_50%)]"></div>

    <div class="relative container mx-auto px-6 text-center max-w-4xl">
        <span class="inline-flex items-center gap-2 text-sm bg-white/70 backdrop-blur-sm border border-white/40 px-4 py-2 rounded-full shadow-sm">
            <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>
            Let's Connect
        </span>

        <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold mt-6 leading-tight tracking-tight">
            Ready to <span class="text-[#8B5A2B] relative inline-block">
                start?
                <span class="absolute -bottom-1 left-0 w-full h-1 bg-[#D98C5F]/30 rounded-full"></span>
            </span>
        </h1>

        <p class="mt-4 text-[#5C5C5C] text-lg max-w-2xl mx-auto">
            Whether you're planning an African adventure or need a website that drives results — we're here to help.
        </p>
    </div>
</section>

{{-- ===================== CONTACT FORM + INFO ===================== --}}
<section class="container mx-auto px-6 py-8">
    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Contact Form (spans 2 cols on large) --}}
        <div class="lg:col-span-2">
            <div class="glass-panel rounded-3xl p-6 md:p-8 shadow-xl">
                <h2 class="text-2xl font-semibold mb-6">Send us a message</h2>

                {{-- Success Message (Livewire or session flash) --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-[#5C5C5C] mb-1">Full Name *</label>
                            <input type="text" name="name" id="name" required
                                   class="input-field" placeholder="Your name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#5C5C5C] mb-1">Email Address *</label>
                            <input type="email" name="email" id="email" required
                                   class="input-field" placeholder="you@example.com">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-[#5C5C5C] mb-1">Phone (optional)</label>
                            <input type="tel" name="phone" id="phone"
                                   class="input-field" placeholder="+254 XXX XXX XXX">
                        </div>
                        <div>
                            <label for="inquiry_type" class="block text-sm font-medium text-[#5C5C5C] mb-1">I'm interested in *</label>
                            <select name="inquiry_type" id="inquiry_type" required class="input-field">
                                <option value="">-- Please select --</option>
                                <option value="travel">Travel Booking / Discovery</option>
                                <option value="web_dev">Web Development Services</option>
                                <option value="partnership">Partnership Opportunity</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-[#5C5C5C] mb-1">Subject</label>
                        <input type="text" name="subject" id="subject"
                               class="input-field" placeholder="What's this about?">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-[#5C5C5C] mb-1">Message *</label>
                        <textarea name="message" id="message" rows="5" required
                                  class="input-field" placeholder="Tell us more about your inquiry..."></textarea>
                    </div>
                        <div>
                            <label for="website" class="block text-sm font-medium text-[#5C5C5C] mb-1">Website (optional)</label>
                            <input type="url" name="website" id="website"
                                   class="input-field" placeholder="https://yourwebsite.com">
                        </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="consent" id="consent" required class="w-4 h-4 text-[#8B5A2B] rounded border-gray-300">
                        <label for="consent" class="text-sm text-[#5C5C5C]">
                            I agree to be contacted about this inquiry. (We'll never spam you.)
                        </label>
                    </div>

                    <button type="submit" class="btn-primary w-full py-3 rounded-xl text-base">
                        Send Message
                    </button>

                    <p class="text-xs text-[#5C5C5C] text-center mt-4">
                        We typically respond within 24 hours.
                    </p>
                </form>
            </div>
        </div>

        {{-- Contact Info Cards --}}
        <div class="space-y-6">
            {{-- Email Card --}}
            <div class="contact-card p-6">
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8B5A2B" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <h3 class="font-semibold text-lg">Email Us</h3>
                <p class="text-[#5C5C5C] text-sm mt-1">For general inquiries:</p>
                <a href="mailto:africa@vumbiventures.com" class="text-[#8B5A2B] font-medium hover:underline block mt-2">africa@vumbiventures.com</a>
                <p class="text-[#5C5C5C] text-sm mt-3">For travel support:</p>
                <a href="mailto:info@vumbiventures.com" class="text-[#8B5A2B] font-medium hover:underline block mt-1">info@vumbiventures.com</a>
            </div>

            {{-- WhatsApp Card --}}
            <div class="contact-card p-6">
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#8B5A2B"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.162.574 4.189 1.575 5.938L.033 23.967l6.273-1.497A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.965 0-3.807-.472-5.425-1.303l-3.865.922.957-3.735A9.953 9.953 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/><path d="M17.5 14.5c-.3.85-1.5 1.5-2.5 1.5-1.5 0-3.5-1-5-2.5s-2.5-3.5-2.5-5c0-1 .65-2.2 1.5-2.5.2-.1.4-.1.6 0 .2.1.4.3.5.5.2.5.5 1.2.6 1.5.1.3.1.5 0 .7-.1.2-.3.4-.5.6-.2.2-.3.4-.3.6s.1.4.3.6c.5.8 1.5 1.8 2.5 2.3.2.1.4.2.6.1.2-.1.4-.3.6-.5.2-.2.4-.3.7-.2.3.1 1 .5 1.5.6.2.1.4.3.5.5.1.2.1.5 0 .7z"/></svg>
                </div>
                <h3 class="font-semibold text-lg">WhatsApp</h3>
                <p class="text-[#5C5C5C] text-sm mt-1">Quick questions? Chat with us.</p>
                <a href="https://wa.me/254734591543" target="_blank" rel="noopener" class="inline-block mt-3 text-[#8B5A2B] font-medium hover:underline">
                    +254 734 591 543 →
                </a>
            </div>

            {{-- Location Card --}}
            <div class="contact-card p-6">
                <div class="w-12 h-12 bg-[#F5EFE6] rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8B5A2B" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <h3 class="font-semibold text-lg">Our Base</h3>
                <p class="text-[#5C5C5C] text-sm mt-1">Nairobi, Kenya</p>
                <p class="text-[#5C5C5C] text-sm">Working across Africa</p>
                {{-- Optional mini map or flag --}}
            </div>

            {{-- Social Links --}}
            <div class="contact-card p-6">
                <h3 class="font-semibold text-lg mb-3">Follow Along</h3>
                <div class="flex gap-4">
                    <a href="https://twitter.com/vumbiventures" target="_blank" class="text-[#5C5C5C] hover:text-[#8B5A2B] transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://linkedin.com/company/vumbi-ventures" target="_blank" class="text-[#5C5C5C] hover:text-[#8B5A2B] transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="https://instagram.com/vumbiventures" target="_blank" class="text-[#5C5C5C] hover:text-[#8B5A2B] transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== MAP / LOCATION VISUAL ===================== --}}
{{-- ===================== MAP / LOCATION VISUAL ===================== --}}
<section class="container mx-auto px-6 py-12">
    <div class="rounded-3xl overflow-hidden border border-black/5 shadow-lg h-72 md:h-96 bg-[#E8DFD5]">
        {{-- OpenStreetMap Embed – free, no API key required --}}
        <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                src="https://www.openstreetmap.org/export/embed.html?bbox=36.05%2C-0.35%2C36.10%2C-0.30&amp;layer=mapnik&amp;marker=-0.325%2C36.075"
                style="border: 0;">
        </iframe>
    </div>
    <div class="flex flex-col sm:flex-row items-center justify-between mt-4">
        <p class="text-sm text-[#5C5C5C]">
            📍 P448+FW, Nakuru, Kenya — serving clients across Africa and beyond.
        </p>
        <div class="flex gap-3">
            <a href="https://www.openstreetmap.org/directions?engine=graphhopper_car&route=%3B-0.325%2C36.075"
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-1 text-sm font-medium text-[#8B5A2B] hover:underline mt-2 sm:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0 0-10 10c0 5 10 13 10 13s10-8 10-13a10 10 0 0 0-10-10z"/><circle cx="12" cy="12" r="3"/></svg>
                Directions (OSM) →
            </a>
            <a href="https://maps.google.com/?q=P448%2BFW+Nakuru%2C+Kenya"
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-1 text-sm font-medium text-[#8B5A2B] hover:underline mt-2 sm:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15 15 0 0 0 0 20 15 15 0 0 0 0-20z"/><path d="M2 12h20"/></svg>
                Google Maps →
            </a>
        </div>
    </div>
    <p class="text-xs text-[#8B5A2B]/60 text-center mt-2">Map data © OpenStreetMap contributors</p>
</section>

{{-- ===================== FAQ QUICK LINKS ===================== --}}
<section class="container mx-auto px-6 py-8 text-center">
    <p class="text-[#5C5C5C]">
        Looking for something specific?
        <a href="{{ url('/services') }}" class="text-[#8B5A2B] font-medium hover:underline mx-2">Our Services</a> •
        <a href="{{ url('/discover') }}" class="text-[#8B5A2B] font-medium hover:underline mx-2">Travel Discovery</a> •
        <a href="{{ url('/field-notes') }}" class="text-[#8B5A2B] font-medium hover:underline mx-2">Field Notes</a>
    </p>
</section>

@endsection