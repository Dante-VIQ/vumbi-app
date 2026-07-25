<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div
    class="min-h-screen bg-[#07090E] text-slate-100 font-sans selection:bg-indigo-500 selection:text-white relative overflow-hidden">

    {{-- Ambient 3D Glow Backdrops --}}
    <div
        class="absolute top-[-5%] left-1/2 -translate-x-1/2 w-[900px] h-[550px] bg-gradient-to-tr from-amber-600/15 via-indigo-600/20 to-blue-500/10 blur-[140px] pointer-events-none rounded-full">
    </div>
    <div
        class="absolute bottom-[15%] left-[-10%] w-[600px] h-[600px] bg-indigo-600/10 blur-[160px] pointer-events-none rounded-full">
    </div>

    {{-- Hero Section --}}
<header class="relative z-10 max-w-6xl mx-auto px-4 pt-16 pb-20 lg:pt-24 lg:pb-32">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
        
        {{-- Left Column: Copy & Conversions --}}
        <div class="lg:col-span-6 text-center lg:text-left">
            
            {{-- Status Badge --}}
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-xl mb-6 shadow-2xl">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                </span>
                <span class="text-xs font-semibold tracking-widest uppercase text-amber-300">Vumbi Ventures Tech Studio</span>
            </div>

            {{-- Headline --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.1] mb-6">
                Engineering <br class="hidden sm:inline"/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-indigo-300 to-blue-400">
                    Precision Platforms
                </span> <br/>
                For Africa & Beyond
            </h1>

            {{-- Sub-description --}}
            <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                We craft enterprise digital infrastructure—from custom multi-tenant software to high-impact health, logistics, and education systems designed for absolute scale.
            </p>

            {{-- Primary CTAs --}}
            <div class="flex flex-wrap justify-center lg:justify-start items-center gap-4 mb-10">
                <a href="#licensed-platforms" 
                   class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 via-indigo-600 to-blue-600 text-white font-bold text-sm shadow-[0_10px_30px_rgba(245,158,11,0.3)] hover:shadow-[0_15px_40px_rgba(79,70,229,0.5)] transform hover:-translate-y-0.5 transition-all duration-200">
                    Explore Our IP
                </a>
                <a href="#client-work" 
                   class="px-7 py-3.5 rounded-2xl bg-white/5 hover:bg-white/10 text-slate-200 border border-white/10 backdrop-blur-md font-semibold text-sm transition-all duration-200">
                    View Case Studies
                </a>
            </div>

            {{-- Quick Studio Social Proof Badges --}}
            <div class="pt-8 border-t border-white/10 grid grid-cols-3 gap-4 text-center lg:text-left">
                <div>
                    <p class="text-2xl font-black text-white">100%</p>
                    <p class="text-xs text-slate-400">IP Retention</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-white">6+</p>
                    <p class="text-xs text-slate-400">US & KE Builds</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-white">Multi-tenant</p>
                    <p class="text-xs text-slate-400">Architecture</p>
                </div>
            </div>
        </div>

        {{-- Right Column: Unique 3D Glass Hero Showcase Graphic --}}
        <div class="lg:col-span-6 relative">
            
            {{-- Secondary Ambient Glow specific to image frame --}}
            <div class="absolute -inset-4 bg-gradient-to-tr from-amber-500/20 via-indigo-500/30 to-blue-600/20 rounded-[40px] blur-2xl opacity-75 group-hover:opacity-100 transition duration-1000 pointer-events-none"></div>

            {{-- Main 3D Container Frame --}}
            <div class="relative rounded-3xl border border-white/15 bg-slate-900/60 p-3 sm:p-4 backdrop-blur-2xl shadow-[0_25px_60px_rgba(0,0,0,0.7)] overflow-hidden">
                
                {{-- Browser Header Control Bar --}}
                <div class="flex items-center justify-between pb-3 px-3 border-b border-white/10 mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                    </div>
                    <div class="px-3 py-1 rounded-full bg-white/5 border border-white/10 font-mono text-[11px] text-slate-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        studio.vumbiventures.com
                    </div>
                    <div class="text-slate-500 text-xs font-mono">
                        v2.4
                    </div>
                </div>

                {{-- Hero Visual Image with Dark Overlay Gradient --}}
                <div class="relative aspect-[4/3] rounded-2xl overflow-hidden border border-white/10 group">
                    
                    {{-- High-Quality Technical Visual (Abstract Network Nodes & Glass Architecture) --}}
                    <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80" 
                         alt="Vumbi Tech Studio Platform Architecture" 
                         class="w-full h-full object-cover transform scale-105 group-hover:scale-100 transition-transform duration-700 opacity-80 mix-blend-luminosity hover:mix-blend-normal">

                    {{-- Dark Gradient Vignette Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-[#07090E] via-slate-950/40 to-transparent"></div>

                    {{-- Floating Glassmorphic Metric Card 1 (Bottom Left) --}}
                    <div class="absolute bottom-4 left-4 right-4 sm:right-auto sm:max-w-xs bg-slate-900/80 border border-white/15 p-4 rounded-xl backdrop-blur-md shadow-2xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center font-bold text-lg shrink-0">
                            ⚡
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Live System Engine</p>
                            <p class="text-[11px] text-slate-300">Healthcare Routing & Multi-Tenant Dispatch Active</p>
                        </div>
                    </div>

                    {{-- Floating Pill Tag 2 (Top Right) --}}
                    <div class="absolute top-4 right-4 bg-slate-900/80 border border-white/15 px-3 py-1.5 rounded-xl backdrop-blur-md shadow-xl flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        <span class="text-xs font-mono font-medium text-slate-200">99.99% Uptime SLA</span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</header>

    {{-- Proprietary Licensed Platforms Section --}}
    <section id="licensed-platforms" class="max-w-6xl mx-auto px-4 py-16 relative z-10">
        <div class="text-center mb-16">
            <span class="text-xs font-bold text-indigo-400 tracking-widest uppercase mb-2 block">Proprietary
                Software</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Licensed Platforms</h2>
            <p class="text-slate-400 max-w-2xl mx-auto text-base">
                Engineered and owned 100% by Vumbi Ventures. Deployed as dedicated instances for enterprise,
                institutional, and government clients.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Platform 1: Nafasi --}}
            <div
                class="group relative bg-slate-900/50 border border-blue-500/30 rounded-3xl p-8 backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] hover:border-blue-400/60 transition-all duration-500 flex flex-col justify-between overflow-hidden">
                <div
                    class="absolute -top-16 -right-16 w-36 h-36 bg-blue-500/20 rounded-full blur-2xl group-hover:bg-blue-500/30 transition-all duration-500">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-3xl shadow-lg shadow-blue-500/30">
                            🏥
                        </div>
                        <span
                            class="px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 rounded-full text-xs font-semibold uppercase tracking-wider">
                            Testing Phase
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-blue-400 transition-colors">Nafasi
                    </h3>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Real-time health services and emergency coordination network. Connects citizens to facilities,
                        pharmacies, and responders instantly across multi-lingual channels.
                    </p>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <span
                            class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Licensing
                            Model</span>
                        <span
                            class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Multi-tenant
                            Architecture</span>
                        <span
                            class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">County
                            & Enterprise</span>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Monthly facility license basis</span>
                    <a href="/demo"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-lg shadow-blue-600/30 transition-all">
                        <span>Experience Demo</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Platform 2: VumbiDNA --}}
            <div
                class="group relative bg-slate-900/50 border border-emerald-500/30 rounded-3xl p-8 backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] hover:border-emerald-400/60 transition-all duration-500 flex flex-col justify-between overflow-hidden">
                <div
                    class="absolute -top-16 -right-16 w-36 h-36 bg-emerald-500/20 rounded-full blur-2xl group-hover:bg-emerald-500/30 transition-all duration-500">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/30">
                            🧬
                        </div>
                        <span
                            class="px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 rounded-full text-xs font-semibold uppercase tracking-wider">
                            In Active Development
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-emerald-400 transition-colors">
                        VumbiDNA</h3>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Household productivity and family education ecosystem. Provides structured modules in financial
                        literacy, practical skills, and wellness for all generations.
                    </p>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <span
                            class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Group
                            Subscriptions</span>
                        <span
                            class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Institutional
                            Licensing</span>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Targeted for Schools, Chamas & Groups</span>
                    <button disabled
                        class="px-5 py-2.5 rounded-xl bg-white/5 text-slate-500 font-bold text-xs cursor-not-allowed border border-white/5">
                        Launch Coming Soon
                    </button>
                </div>
            </div>

        </div>
    </section>

    {{-- Delivered Client Projects --}}
    <section id="client-work" class="bg-slate-900/30 border-y border-white/10 py-20 relative z-10">
        <div class="max-w-6xl mx-auto px-4">

            {{-- Section Header --}}
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-indigo-400 tracking-widest uppercase mb-2 block">Proven Delivery &
                    Case Studies</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Custom Client Builds</h2>
                <p class="text-slate-400 max-w-2xl mx-auto text-base">
                    Bespoke commercial platforms engineered from the ground up—complete with client ownership, secure
                    payment/dispatch integrations, and real-time operational workflows.
                </p>
            </div>

            {{-- Projects Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Project 1: Seattle Mobility Solutions --}}
                <div
                    class="group relative bg-gradient-to-b from-white/10 to-white/[0.02] border border-white/10 rounded-3xl p-6 sm:p-8 backdrop-blur-xl hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-1 shadow-[0_15px_35px_rgba(0,0,0,0.3)] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold text-lg">
                                01
                            </div>
                            <span
                                class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full text-[11px] font-bold uppercase tracking-wider">
                                Live Operational
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-indigo-300 transition-colors">
                            Seattle Mobility Solutions
                        </h3>

                        <p class="text-slate-300 text-sm leading-relaxed mb-6">
                            A specialized Non-Emergency Medical Transportation (NEMT) dispatch and fleet engine built
                            for US healthcare corridors. Integrates wheelchair/gurney accessibility routing,
                            facility-to-facility patient scheduling, automated dispatching algorithms, and
                            HIPAA-compliant trip log management.
                        </p>

                        {{-- Highlight Features --}}
                        <div class="flex flex-wrap gap-2 mb-8">
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Fleet
                                Dispatch</span>
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">NEMT
                                Logistics</span>
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Driver
                                Portal</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-mono">seattlemobilitysolutions.com</span>
                        <a href="https://mobilityseattle.com" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/30 transition-all group/link">
                            <span>Visit Live Site</span>
                            <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Project 2: Ride Aide LLC --}}
                <div
                    class="group relative bg-gradient-to-b from-white/10 to-white/[0.02] border border-white/10 rounded-3xl p-6 sm:p-8 backdrop-blur-xl hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-1 shadow-[0_15px_35px_rgba(0,0,0,0.3)] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold text-lg">
                                02
                            </div>
                            <span
                                class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full text-[11px] font-bold uppercase tracking-wider">
                                Live Operational
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-indigo-300 transition-colors">
                            Ride Aide LLC
                        </h3>

                        <p class="text-slate-300 text-sm leading-relaxed mb-6">
                            An end-to-end transportation booking platform designed for medical appointments and senior
                            transport. Features live GPS ride tracking, dynamic distance-based fare estimation,
                            automated SMS notifications for patients, and driver shift optimization systems.
                        </p>

                        {{-- Highlight Features --}}
                        <div class="flex flex-wrap gap-2 mb-8">
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Live
                                GPS Tracking</span>
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Ride
                                Booking</span>
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Automated
                                Dispatch</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-mono">rideaidellc.com</span>
                        <a href="https://rideaidellc.com" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/30 transition-all group/link">
                            <span>Visit Live Site</span>
                            <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Project 3: GB Handyman LLC --}}
                <div
                    class="group relative bg-gradient-to-b from-white/10 to-white/[0.02] border border-white/10 rounded-3xl p-6 sm:p-8 backdrop-blur-xl hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-1 shadow-[0_15px_35px_rgba(0,0,0,0.3)] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold text-lg">
                                03
                            </div>
                            <span
                                class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full text-[11px] font-bold uppercase tracking-wider">
                                Live Operational
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-indigo-300 transition-colors">
                            GB Handyman LLC
                        </h3>

                        <p class="text-slate-300 text-sm leading-relaxed mb-6">
                            An active home services booking and business management hub. Allows homeowners to request
                            estimates, schedule contractor site visits, receive digital invoices, and track home
                            improvement job progress in real time.
                        </p>

                        {{-- Highlight Features --}}
                        <div class="flex flex-wrap gap-2 mb-8">
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Quote
                                Engine</span>
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Job
                                CRM</span>
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Invoicing</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-mono">gbhandymanllc.com</span>
                        <a href="https://gbhandymanllc.com" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/30 transition-all group/link">
                            <span>Visit Live Site</span>
                            <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Project 4: Amazing Palace --}}
                <div
                    class="group relative bg-gradient-to-b from-white/10 to-white/[0.02] border border-white/10 rounded-3xl p-6 sm:p-8 backdrop-blur-xl hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-1 shadow-[0_15px_35px_rgba(0,0,0,0.3)] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold text-lg">
                                04
                            </div>
                            <span
                                class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full text-[11px] font-bold uppercase tracking-wider">
                                Live Operational
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-indigo-300 transition-colors">
                            Amazing Palace
                        </h3>

                        <p class="text-slate-300 text-sm leading-relaxed mb-6">
                            A full-stack luxury retail and e-commerce portal equipped with a product catalog showcase,
                            online reservation systems, automated inventory control, and secure payment checkout
                            integrations.
                        </p>

                        {{-- Highlight Features --}}
                        <div class="flex flex-wrap gap-2 mb-8">
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">E-Commerce</span>
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Payment
                                Gateway</span>
                            <span
                                class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-medium text-slate-300">Inventory
                                Management</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-mono">amazingpalace.com</span>
                        <a href="https://amazingpalace.com" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md shadow-indigo-600/30 transition-all group/link">
                            <span>Visit Live Site</span>
                            <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Operating Models Comparison --}}
    <section class="max-w-6xl mx-auto px-4 py-20 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-white mb-4">How We Partner</h2>
            <p class="text-slate-400 max-w-xl mx-auto text-base">
                Flexible engagement paths designed for business flexibility and long-term tech sustainability.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
                class="bg-gradient-to-b from-blue-950/30 to-slate-900/40 border border-blue-500/20 rounded-3xl p-8 backdrop-blur-xl">
                <div
                    class="w-12 h-12 rounded-2xl bg-blue-500/20 border border-blue-500/30 text-blue-400 flex items-center justify-center text-xl font-bold mb-6">
                    🛠️
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Custom Build Engagement</h3>
                <p class="text-slate-300 text-sm leading-relaxed mb-6">
                    Ideal for businesses needing specialized software tailored to precise operating standards.
                </p>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li class="flex items-center gap-3">
                        <span class="text-emerald-400">✓</span> Full source code and IP ownership upon handover
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-emerald-400">✓</span> Transparent milestones & fixed pricing schedule
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-emerald-400">✓</span> Optional ongoing hosting and maintenance plans
                    </li>
                </ul>
            </div>

            <div
                class="bg-gradient-to-b from-indigo-950/30 to-slate-900/40 border border-indigo-500/20 rounded-3xl p-8 backdrop-blur-xl">
                <div
                    class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center text-xl font-bold mb-6">
                    🔑
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Platform Licensing Model</h3>
                <p class="text-slate-300 text-sm leading-relaxed mb-6">
                    Built for institutions, counties, and networks leveraging our core IP with zero upfront build costs.
                </p>
                <ul class="space-y-3 text-slate-400 text-sm">
                    <li class="flex items-center gap-3">
                        <span class="text-emerald-400">✓</span> Predictable subscription rates (Monthly/Annual)
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-emerald-400">✓</span> 100% data sovereignty & isolated database setup
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-emerald-400">✓</span> Continuous feature rollouts & platform maintenance
                    </li>
                </ul>
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <footer class="border-t border-white/10 bg-slate-950/80 py-16 relative z-10 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-3xl font-extrabold text-white mb-4">Let's Engineer Your Vision</h2>
            <p class="text-slate-400 max-w-xl mx-auto mb-8 text-base">
                Whether you need a custom enterprise application or want to pilot a licensed platform like Nafasi in
                your region.
            </p>
            <a href="mailto:studio@vumbiventures.com"
                class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-white text-slate-950 font-black text-base hover:bg-slate-200 transition-all shadow-[0_10px_30px_rgba(255,255,255,0.2)]">
                ✉️ Contact Tech Studio
            </a>

            <p class="text-xs text-slate-500 mt-12">
                Vumbi Ventures Tech Studio • Building Digital Infrastructure for Africa • Operating from Kenya
            </p>
            <p class="text-xs text-slate-600 mt-2">© {{ date('Y') }} Vumbi Ventures. All rights reserved.</p>
        </div>
    </footer>

</div>

{{-- Google Structured Data for Studio Ranking --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareHouse",
  "name": "Vumbi Ventures Tech Studio",
  "url": "https://vumbiventures.com",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "KE"
  },
  "knowsAbout": ["Custom Software Development", "SaaS Licensing", "Healthcare Systems", "Logistics Platforms"]
}
</script>
