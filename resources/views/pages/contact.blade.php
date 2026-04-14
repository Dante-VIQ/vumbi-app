@extends('layouts.app')

@section('title', 'Contact Vumbi Ventures - Let\'s Build Something Remarkable')
@section('description', 'Get in touch with Vumbi Ventures. Whether you\'re a potential partner, client, investor, or just curious about what we\'re building, we\'d love to hear from you.')
@section('keywords', 'contact Vumbi Ventures, partner with us, web development inquiry, African innovation, get in touch, Nairobi, Accra')

@push('styles')
<style>
    .contact-card {
        transition: all 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(139, 90, 43, 0.1), 0 10px 10px -5px rgba(139, 90, 43, 0.04);
    }
    
    .map-overlay {
        position: relative;
        overflow: hidden;
    }
    
    .map-overlay::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(139, 90, 43, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);
    }
    
    .office-flag {
        position: relative;
        display: inline-block;
    }
    
    .office-flag::before {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #8B5A2B, #D98C5F);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .office-flag:hover::before {
        transform: scaleX(1);
    }
</style>
@endpush

@push('schema')
    @php
        $contactSchema = [
            "@context" => "https://schema.org",
            "@type" => "ContactPage",
            "@id" => url('/contact') . "#contact",
            "name" => "Contact Vumbi Ventures",
            "description" => "Get in touch with Vumbi Ventures for partnerships, inquiries, or just to say hello.",
            "url" => url('/contact'),
            "mainEntity" => [
                "@type" => "Organization",
                "name" => "Vumbi Ventures",
                "address" => [
                    "@type" => "PostalAddress",
                    "addressLocality" => "Nairobi",
                    "addressCountry" => "KE"
                ],
                "email" => "hello@vumbiventures.com",
                "telephone" => "+254 700 000 000",
                "contactPoint" => [
                    [
                        "@type" => "ContactPoint",
                        "contactType" => "customer service",
                        "email" => "hello@vumbiventures.com",
                        "availableLanguage" => ["English", "Swahili"]
                    ],
                    [
                        "@type" => "ContactPoint",
                        "contactType" => "partner program",
                        "email" => "partners@vumbiventures.com",
                        "availableLanguage" => ["English"]
                    ],
                    [
                        "@type" => "ContactPoint",
                        "contactType" => "talent pipeline",
                        "email" => "talent@vumbiventures.com",
                        "availableLanguage" => ["English", "Swahili", "French"]
                    ]
                ]
            ]
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($contactSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-white overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 right-0 w-96 h-96 bg-[#8B5A2B]/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-[#D98C5F]/5 rounded-full blur-3xl"></div>
            
            <!-- Floating dust particles -->
            <div class="absolute top-40 left-20 w-2 h-2 bg-[#8B5A2B]/20 rounded-full animate-pulse-slow"></div>
            <div class="absolute bottom-40 right-40 w-3 h-3 bg-[#D98C5F]/20 rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-60 right-60 w-1.5 h-1.5 bg-[#C7B5A6]/30 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block mb-6">
                    <span class="bg-[#E5E0D9] text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-paper-plane mr-2"></i>Get In Touch
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Let's <span class="text-[#8B5A2B] relative">
                        Connect
                        <span class="absolute bottom-2 left-0 w-full h-3 bg-[#D98C5F]/20 -z-10"></span>
                    </span>
                </h1>
                <p class="text-xl text-[#6B6B6B] leading-relaxed max-w-3xl mx-auto">
                    Whether you're a potential partner, client, investor, or just curious about what we're building, 
                    we'd love to hear from you. From overlooked places, we build remarkable solutions together.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Cards -->
    <section class="py-12 dust-bg">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- General Inquiries -->
                <div class="contact-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-[#8B5A2B]/10 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-envelope text-2xl text-[#8B5A2B]"></i>
                    </div>
                    <h3 class="font-bold mb-2">General Inquiries</h3>
                    <p class="text-sm text-[#6B6B6B] mb-3">For general questions and information</p>
                    <a href="mailto:hello@vumbiventures.com" class="text-[#8B5A2B] font-semibold hover:underline inline-flex items-center gap-1">
                        hello@vumbiventures.com
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </a>
                    <div class="mt-3 text-xs text-[#6B6B6B]">
                        <i class="fas fa-clock mr-1"></i> Response within 24h
                    </div>
                </div>

                <!-- Partner Program -->
                <div class="contact-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-[#2C5F2D]/10 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-handshake text-2xl text-[#2C5F2D]"></i>
                    </div>
                    <h3 class="font-bold mb-2">Partner Program</h3>
                    <p class="text-sm text-[#6B6B6B] mb-3">Join our network of local businesses</p>
                    <a href="mailto:partners@vumbiventures.com" class="text-[#2C5F2D] font-semibold hover:underline inline-flex items-center gap-1">
                        partners@vumbiventures.com
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </a>
                    <div class="mt-3 text-xs text-[#6B6B6B]">
                        <i class="fas fa-clock mr-1"></i> Response within 48h
                    </div>
                </div>

                <!-- Talent Pipeline -->
                <div class="contact-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-[#D98C5F]/10 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-user-tie text-2xl text-[#D98C5F]"></i>
                    </div>
                    <h3 class="font-bold mb-2">Talent Pipeline</h3>
                    <p class="text-sm text-[#6B6B6B] mb-3">Join our network of experts</p>
                    <a href="mailto:talent@vumbiventures.com" class="text-[#D98C5F] font-semibold hover:underline inline-flex items-center gap-1">
                        talent@vumbiventures.com
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </a>
                    <div class="mt-3 text-xs text-[#6B6B6B]">
                        <i class="fas fa-clock mr-1"></i> Response within 3-5 days
                    </div>
                </div>

                <!-- Press & Media -->
                <div class="contact-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-[#C7B5A6]/30 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-newspaper text-2xl text-[#6B6B6B]"></i>
                    </div>
                    <h3 class="font-bold mb-2">Press & Media</h3>
                    <p class="text-sm text-[#6B6B6B] mb-3">Media inquiries and interviews</p>
                    <a href="mailto:press@vumbiventures.com" class="text-[#6B6B6B] font-semibold hover:underline inline-flex items-center gap-1">
                        press@vumbiventures.com
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </a>
                    <div class="mt-3 text-xs text-[#6B6B6B]">
                        <i class="fas fa-clock mr-1"></i> Response within 24h
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Contact Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold mb-3">Send Us a Message</h2>
                        <p class="text-[#6B6B6B]">Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>

                    <form wire:submit.prevent="submitContact" class="space-y-6">
                        <!-- Name Field -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">First Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="first_name" required
                                    class="form-input w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white transition">
                                @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Last Name</label>
                                <input type="text" wire:model="last_name"
                                    class="form-input w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white transition">
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" required
                                class="form-input w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white transition">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Phone (Optional)</label>
                            <input type="tel" wire:model="phone"
                                class="form-input w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white transition">
                        </div>

                        <!-- Subject / Interest -->
                        <div>
                            <label class="block text-sm font-medium mb-2">I'm interested in <span class="text-red-500">*</span></label>
                            <select wire:model="interest" required
                                class="w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white">
                                <option value="">Select an option</option>
                                <option value="general">General Inquiry</option>
                                <option value="partner">Partner Program</option>
                                <option value="talent">Talent Pipeline</option>
                                <option value="client">Web Development Services</option>
                                <option value="investor">Investment Opportunity</option>
                                <option value="press">Press & Media</option>
                                <option value="other">Other</option>
                            </select>
                            @error('interest') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Message Field -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Message <span class="text-red-500">*</span></label>
                            <textarea rows="5" wire:model="message" required
                                class="form-input w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white transition resize-none"
                                placeholder="Tell us about your project, idea, or inquiry..."></textarea>
                            @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- How did you hear about us? -->
                        <div>
                            <label class="block text-sm font-medium mb-2">How did you hear about us?</label>
                            <select wire:model="referral"
                                class="w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white">
                                <option value="">Select an option</option>
                                <option value="social">Social Media</option>
                                <option value="search">Search Engine</option>
                                <option value="referral">Referral</option>
                                <option value="field-notes">Field Notes Blog</option>
                                <option value="event">Event</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Newsletter Checkbox -->
                        <div class="flex items-start gap-3">
                            <input type="checkbox" wire:model="newsletter" id="newsletter" class="mt-1">
                            <label for="newsletter" class="text-sm text-[#6B6B6B]">
                                Subscribe to Field Notes - monthly stories from overlooked places
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" 
                                class="w-full bg-[#8B5A2B] text-white px-6 py-4 rounded-xl hover:bg-[#6B421F] transition font-medium inline-flex items-center justify-center gap-2 text-lg"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    Send Message <i class="fas fa-paper-plane"></i>
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin mr-2"></i> Sending...
                                </span>
                            </button>
                        </div>

                        <!-- Privacy Note -->
                        <p class="text-xs text-[#6B6B6B] text-center">
                            By submitting this form, you agree to our <a href="#" class="text-[#8B5A2B] hover:underline">Privacy Policy</a> and consent to being contacted.
                        </p>
                    </form>
                </div>

                <!-- Contact Information & Map -->
                <div>
                    <!-- Office Locations -->
                    <div class="bg-[#F9F5F0] p-8 rounded-3xl mb-8">
                        <h3 class="text-2xl font-bold mb-6">Our Offices</h3>
                        
                        <div class="space-y-6">
                            <!-- Nairobi Office -->
                            <div class="flex gap-4">
                                <div class="w-12 h-12 bg-[#8B5A2B] rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-pin text-white"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold">Nairobi, Kenya</h4>
                                        <span class="text-xs bg-[#8B5A2B]/10 text-[#8B5A2B] px-2 py-1 rounded-full">Headquarters</span>
                                    </div>
                                    <p class="text-[#6B6B6B] text-sm mb-2">
                                        <i class="fas fa-building mr-2"></i> Innovation Hub, Westlands<br>
                                        <i class="fas fa-phone mr-2"></i> +254 700 000 000<br>
                                        <i class="fas fa-clock mr-2"></i> Mon-Fri, 8:00 - 18:00 EAT
                                    </p>
                                    <a href="#" class="office-flag text-sm text-[#8B5A2B] font-medium">View on map →</a>
                                </div>
                            </div>

                            <!-- Accra Office -->
                            <div class="flex gap-4">
                                <div class="w-12 h-12 bg-[#D98C5F] rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-pin text-white"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold">Accra, Ghana</h4>
                                        <span class="text-xs bg-[#D98C5F]/10 text-[#D98C5F] px-2 py-1 rounded-full">West Africa Hub</span>
                                    </div>
                                    <p class="text-[#6B6B6B] text-sm mb-2">
                                        <i class="fas fa-building mr-2"></i> Osu, Accra<br>
                                        <i class="fas fa-phone mr-2"></i> +233 30 000 000<br>
                                        <i class="fas fa-clock mr-2"></i> Mon-Fri, 8:00 - 17:00 GMT
                                    </p>
                                    <a href="#" class="office-flag text-sm text-[#D98C5F] font-medium">View on map →</a>
                                </div>
                            </div>

                            <!-- Kigali Office (Future) -->
                            <div class="flex gap-4 opacity-75">
                                <div class="w-12 h-12 bg-[#C7B5A6] rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-pin text-white"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold">Kigali, Rwanda</h4>
                                        <span class="text-xs bg-[#C7B5A6]/20 text-[#6B6B6B] px-2 py-1 rounded-full">Coming Soon</span>
                                    </div>
                                    <p class="text-[#6B6B6B] text-sm">
                                        <i class="fas fa-clock mr-2"></i> Opening 2025
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Connect -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm mb-8">
                        <h3 class="text-xl font-bold mb-4">Quick Connect</h3>
                        <div class="space-y-4">
                            <a href="mailto:hello@vumbiventures.com" class="flex items-center gap-3 text-[#6B6B6B] hover:text-[#8B5A2B] transition">
                                <div class="w-10 h-10 bg-[#F9F5F0] rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-[#8B5A2B]"></i>
                                </div>
                                <span>hello@vumbiventures.com</span>
                            </a>
                            <a href="tel:+254700000000" class="flex items-center gap-3 text-[#6B6B6B] hover:text-[#8B5A2B] transition">
                                <div class="w-10 h-10 bg-[#F9F5F0] rounded-full flex items-center justify-center">
                                    <i class="fas fa-phone-alt text-[#8B5A2B]"></i>
                                </div>
                                <span>+254 700 000 000</span>
                            </a>
                            <div class="flex items-center gap-3 text-[#6B6B6B]">
                                <div class="w-10 h-10 bg-[#F9F5F0] rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-[#8B5A2B]"></i>
                                </div>
                                <span>Response within 24-48 hours</span>
                            </div>
                        </div>
                    </div>

                    <!-- Social Connect -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm">
                        <h3 class="text-xl font-bold mb-4">Follow Our Journey</h3>
                        <p class="text-sm text-[#6B6B6B] mb-6">Connect with us on social media for updates, stories, and behind-the-scenes content.</p>
                        
                        <div class="flex flex-wrap gap-3">
                            <a href="#" target="_blank" rel="noopener noreferrer" 
                                class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                                <i class="fab fa-twitter text-[#8B5A2B] group-hover:text-white"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer"
                                class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                                <i class="fab fa-linkedin-in text-[#8B5A2B] group-hover:text-white"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer"
                                class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                                <i class="fab fa-instagram text-[#8B5A2B] group-hover:text-white"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer"
                                class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                                <i class="fab fa-github text-[#8B5A2B] group-hover:text-white"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer"
                                class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                                <i class="fab fa-youtube text-[#8B5A2B] group-hover:text-white"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer"
                                class="w-12 h-12 bg-[#F9F5F0] rounded-full flex items-center justify-center hover:bg-[#8B5A2B] group transition">
                                <i class="fab fa-tiktok text-[#8B5A2B] group-hover:text-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-12 dust-bg">
        <div class="container mx-auto px-6">
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm">
                <div class="grid lg:grid-cols-3">
                    <!-- Map -->
                    <div class="lg:col-span-2 h-96 bg-[#F9F5F0] relative map-overlay">
                        <!-- Placeholder map - replace with actual Google Maps iframe -->
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#8B5A2B]/10 to-[#D98C5F]/10">
                            <div class="text-center">
                                <i class="fas fa-map-marked-alt text-6xl text-[#8B5A2B]/30 mb-4"></i>
                                <p class="text-[#6B6B6B]">Interactive Map Loading...</p>
                                <p class="text-xs text-[#6B6B6B] mt-2">Nairobi · Accra · Kigali (Coming Soon)</p>
                            </div>
                        </div>
                        <!-- Replace with actual Google Maps embed:
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.123456789!2d36.821946!3d-1.292066!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMcKwMTcnMzEuNSJTIDM2wrA0OScyMC4wIkU!5e0!3m2!1sen!2ske!4v1234567890" 
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        -->
                    </div>

                    <!-- Map Info -->
                    <div class="p-8 bg-white">
                        <h3 class="text-xl font-bold mb-4">Visit Us</h3>
                        <p class="text-sm text-[#6B6B6B] mb-6">
                            We'd love to meet you in person. Schedule a visit to our innovation hubs in Nairobi or Accra.
                        </p>

                        <div class="space-y-4 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#F9F5F0] rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-[#8B5A2B] text-xs"></i>
                                </div>
                                <span class="text-sm">Tours available by appointment</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#F9F5F0] rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-[#8B5A2B] text-xs"></i>
                                </div>
                                <span class="text-sm">Virtual meetings available worldwide</span>
                            </div>
                        </div>

                        <a href="#" class="inline-flex items-center gap-2 text-[#8B5A2B] font-semibold hover:underline">
                            Schedule a visit <i class="fas fa-calendar-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-3">Frequently Asked <span class="text-[#8B5A2B]">Questions</span></h2>
                <p class="text-[#6B6B6B]">Quick answers to common questions about connecting with us.</p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-[#F9F5F0] rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#E5E0D9] transition">
                        <span class="font-semibold">How quickly do you respond to inquiries?</span>
                        <i class="fas fa-chevron-down transition" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        We aim to respond to all inquiries within 24-48 hours. For partner and talent inquiries, it may take 3-5 business days as we carefully review each submission.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-[#F9F5F0] rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#E5E0D9] transition">
                        <span class="font-semibold">Can I visit your office without an appointment?</span>
                        <i class="fas fa-chevron-down transition" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        We recommend scheduling an appointment to ensure someone is available to meet with you. However, you're always welcome to stop by and leave a message at our reception.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-[#F9F5F0] rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#E5E0D9] transition">
                        <span class="font-semibold">Do you work with international clients?</span>
                        <i class="fas fa-chevron-down transition" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        Absolutely! While our roots are in Africa, we work with clients and partners from around the world. We're experienced in remote collaboration and can accommodate any timezone.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-[#F9F5F0] rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#E5E0D9] transition">
                        <span class="font-semibold">How can I become a Vumbi Partner?</span>
                        <i class="fas fa-chevron-down transition" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        Great to hear you're interested! Please use the contact form and select "Partner Program" as your interest, or email us directly at partners@vumbiventures.com with information about your business.
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-[#F9F5F0] rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-[#E5E0D9] transition">
                        <span class="font-semibold">Do you offer internships or volunteer opportunities?</span>
                        <i class="fas fa-chevron-down transition" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4 text-[#6B6B6B]">
                        Yes! We occasionally offer internships and volunteer positions. Please check our Careers page or send your CV to talent@vumbiventures.com with "Internship" in the subject line.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-[#8B5A2B] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <i class="fas fa-dust-storm absolute top-10 left-10 text-white text-8xl"></i>
            <i class="fas fa-paper-plane absolute bottom-10 right-10 text-white text-8xl rotate-12"></i>
        </div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to Start a Conversation?</h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Whether you have a project in mind, want to partner with us, or just want to say hello — we're all ears.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="#" class="bg-white text-[#8B5A2B] px-8 py-3 rounded-full hover:bg-[#F9F5F0] transition font-medium inline-flex items-center gap-2">
                    <i class="fas fa-calendar-alt"></i> Schedule a Call
                </a>
                <a href="mailto:hello@vumbiventures.com" class="border-2 border-white text-white px-8 py-3 rounded-full hover:bg-white hover:text-[#8B5A2B] transition font-medium">
                    hello@vumbiventures.com
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