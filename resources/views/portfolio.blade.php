{{-- resources/views/portfolio.blade.php --}}
@extends('layouts.studio')

@section('title', 'Daniel Maina — Self-Taught AI Engineer & Founder')

@section('content')

{{-- ============ HERO ============ --}}
<section class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-16 pb-24">
    <div class="max-w-4xl">
        {{-- Status badge --}}
        <div class="flex flex-wrap items-center gap-3 mb-8">
            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 border border-emerald-500/30 px-3 py-1 text-xs font-medium text-emerald-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Available for work
            </span>
            <span class="text-xs font-mono text-slate-500 tracking-wider">📍 NAKURU, KENYA · REMOTE</span>
        </div>

        {{-- Name --}}
        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black tracking-tight text-white leading-[1.05]">
            Daniel<br>
            <span class="bg-gradient-to-r from-amber-400 via-indigo-400 to-emerald-400 bg-clip-text text-transparent">
                Mwangi Maina
            </span>
        </h1>

        {{-- Tagline --}}
        <p class="mt-8 text-xl sm:text-2xl leading-relaxed text-slate-300 max-w-3xl">
            Self-taught software engineer. I build production systems that solve real problems —
            from <span class="text-amber-400 font-semibold">AI agents</span> to
            <span class="text-indigo-400 font-semibold">healthcare infrastructure</span> to
            <span class="text-emerald-400 font-semibold">enterprise platforms</span>.
        </p>

        <p class="mt-4 text-lg text-slate-400 font-mono">
            6+ live systems · Real users · No degree
        </p>

        {{-- CTA buttons --}}
        <div class="mt-10 flex flex-wrap items-center gap-3">
            <a href="#projects"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-slate-950 font-semibold text-sm shadow-lg hover:bg-amber-300 transition-all group">
                View Projects
                <svg class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </a>
            <a href="https://github.com/Dante-VIQ" target="_blank"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/5 border border-white/10 text-slate-200 font-semibold text-sm hover:bg-white/10 hover:text-white transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                </svg>
                GitHub
            </a>
        </div>
    </div>
</section>

{{-- ============ FEATURED PROJECT: VUMBI AI ============ --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 pb-24">
    <div class="flex items-end justify-between mb-10">
        <div>
            <span class="text-[11px] font-mono tracking-[0.2em] text-amber-400/80 uppercase">Featured Work</span>
            <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-white">Flagship Systems</h2>
        </div>
        <a href="/studio" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-slate-400 hover:text-amber-400 transition-colors">
            Full Studio →
        </a>
    </div>

    {{-- Vumbi AI — Flagship --}}
    <div class="relative rounded-3xl border border-amber-500/25 bg-gradient-to-br from-slate-900/80 via-slate-900/40 to-indigo-950/30 backdrop-blur-xl p-8 sm:p-10 overflow-hidden group hover:border-amber-400/50 transition-all">
        <div class="absolute -right-32 -top-32 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative">
            {{-- Header --}}
            <div class="flex items-start justify-between flex-wrap gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500/30 to-indigo-500/30 border border-amber-500/40 flex items-center justify-center text-3xl shadow-lg">
                        🧠
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Vumbi AI</h3>
                        <p class="text-sm font-mono text-slate-400 tracking-wide">AUTONOMOUS MARKETING AGENT</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 px-3 py-1 text-xs font-semibold text-amber-300">
                    ⭐ FLAGSHIP
                </span>
            </div>

            {{-- Description --}}
            <p class="text-slate-300 leading-relaxed max-w-3xl">
                A hierarchical multi-agent system built with the Strands Agents SDK and Google Gemini.
                Runs every 15 minutes, discovers marketing opportunities, reasons about them,
                applies a deterministic safety policy, executes low-risk actions autonomously,
                and queues high-risk ones for human approval. Includes day-scoped idempotency,
                experience memory, and a closed-loop learning system.
            </p>

            {{-- Tech tags --}}
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach(['Python', 'Strands SDK', 'Google Gemini', 'Laravel 13', 'MySQL', 'REST APIs'] as $tag)
                    <span class="px-3 py-1 text-xs font-mono rounded-lg bg-white/5 border border-white/10 text-slate-300">{{ $tag }}</span>
                @endforeach
            </div>

            {{-- CTA row --}}
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="https://github.com/Dante-VIQ/Autonomous-Agent" target="_blank"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-amber-400 hover:text-amber-300 transition-colors">
                    View Code
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="https://saddlebrown-butterfly-192418.hostingersite.com" target="_blank"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white transition-colors">
                    Live Demo
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============ PROJECT GRID ============ --}}
<section id="projects" class="max-w-6xl mx-auto px-4 sm:px-6 pb-24">
    <div class="mb-10">
        <span class="text-[11px] font-mono tracking-[0.2em] text-indigo-400/80 uppercase">Portfolio</span>
        <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-white">Live Systems</h2>
        <p class="mt-3 text-slate-400 max-w-xl">
            Production platforms serving real users across Kenya and the United States.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Nafasi --}}
        <div class="group rounded-2xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-6 hover:border-sky-500/50 hover:bg-slate-900/70 transition-all">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-xl">🏥</div>
                <div class="flex-1">
                    <h3 class="font-bold text-white">Nafasi</h3>
                    <p class="text-[11px] font-mono text-slate-500 tracking-wider">HEALTHCARE COORDINATION</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">
                Multi-tenant platform connecting citizens to facilities, pharmacies, and emergency responders
                in real time. Built for county and enterprise licensing with full data sovereignty.
            </p>
            <div class="mt-4 flex flex-wrap gap-1.5">
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Laravel</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Multi-tenant</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Real-time</span>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-xs text-emerald-400 font-semibold">In testing</span>
                <a href="https://vumbidna.com" target="_blank" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors">Visit →</a>
            </div>
        </div>

        {{-- Seattle Mobility --}}
        <div class="group rounded-2xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-6 hover:border-purple-500/50 hover:bg-slate-900/70 transition-all">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-xl">🚐</div>
                <div class="flex-1">
                    <h3 class="font-bold text-white">Seattle Mobility</h3>
                    <p class="text-[11px] font-mono text-slate-500 tracking-wider">NEMT DISPATCH ENGINE</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">
                HIPAA-compliant non-emergency medical transportation platform. Wheelchair/gurney routing,
                automated dispatching, and facility-to-facility patient scheduling.
            </p>
            <div class="mt-4 flex flex-wrap gap-1.5">
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Laravel</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Maps API</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">HIPAA</span>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-xs text-emerald-400 font-semibold">10+ trips dispatched</span>
                <a href="https://mobilityseattle.com" target="_blank" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors">Visit →</a>
            </div>
        </div>

        {{-- Ride Aide --}}
        <div class="group rounded-2xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-6 hover:border-amber-500/50 hover:bg-slate-900/70 transition-all">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-xl">🚗</div>
                <div class="flex-1">
                    <h3 class="font-bold text-white">Ride Aide LLC</h3>
                    <p class="text-[11px] font-mono text-slate-500 tracking-wider">MEDICAL TRANSPORT BOOKING</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">
                Booking platform for medical appointments and senior transport. Live GPS tracking,
                distance-based fare estimation, and automated SMS notifications.
            </p>
            <div class="mt-4 flex flex-wrap gap-1.5">
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Laravel</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">GPS</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">SMS</span>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-xs text-emerald-400 font-semibold">40+ successful bookings</span>
                <a href="https://rideaidellc.com" target="_blank" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors">Visit →</a>
            </div>
        </div>

        {{-- Amazing Palace --}}
        <div class="group rounded-2xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-6 hover:border-pink-500/50 hover:bg-slate-900/70 transition-all">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-pink-500/10 border border-pink-500/30 flex items-center justify-center text-xl">🏛️</div>
                <div class="flex-1">
                    <h3 class="font-bold text-white">Amazing Palace</h3>
                    <p class="text-[11px] font-mono text-slate-500 tracking-wider">LUXURY RETAIL E-COMMERCE</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">
                Full-stack e-commerce portal with product catalog, online reservations,
                inventory control, and secure payment checkout.
            </p>
            <div class="mt-4 flex flex-wrap gap-1.5">
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Laravel</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Payments</span>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-xs text-emerald-400 font-semibold">10+ direct bookings</span>
                <a href="https://amazingpalace.com" target="_blank" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors">Visit →</a>
            </div>
        </div>

        {{-- GB Handyman --}}
        <div class="group rounded-2xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-6 hover:border-orange-500/50 hover:bg-slate-900/70 transition-all">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-center text-xl">🔧</div>
                <div class="flex-1">
                    <h3 class="font-bold text-white">GB Handyman LLC</h3>
                    <p class="text-[11px] font-mono text-slate-500 tracking-wider">HOME SERVICES PLATFORM</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">
                Home services booking platform with quote engine, job CRM, and digital invoicing.
                Homeowners request estimates, schedule visits, and track job progress in real time.
            </p>
            <div class="mt-4 flex flex-wrap gap-1.5">
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Laravel</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">CRM</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Invoicing</span>
            </div>
            <div class="mt-4 flex items-center justify-end">
                <a href="https://gbhandymanllc.com" target="_blank" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors">Visit →</a>
            </div>
        </div>

        {{-- VumbiDNA --}}
        <div class="group rounded-2xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-6 hover:border-emerald-500/50 hover:bg-slate-900/70 transition-all">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-xl">🧬</div>
                <div class="flex-1">
                    <h3 class="font-bold text-white">Vumbi DNA</h3>
                    <p class="text-[11px] font-mono text-slate-500 tracking-wider">FAMILY EDUCATION ECOSYSTEM</p>
                </div>
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">
                Household productivity and family education platform. Structured modules in
                financial literacy, practical skills, and wellness for all generations.
            </p>
            <div class="mt-4 flex flex-wrap gap-1.5">
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Laravel</span>
                <span class="px-2 py-0.5 text-[10px] font-mono rounded-md bg-white/5 border border-white/10 text-slate-400">Education</span>
            </div>
            <p class="mt-4 text-xs font-semibold text-amber-400">🔬 In internal testing</p>
        </div>

    </div>
</section>

{{-- ============ TECHNICAL STACK ============ --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 pb-24">
    <div class="mb-10">
        <span class="text-[11px] font-mono tracking-[0.2em] text-emerald-400/80 uppercase">Capabilities</span>
        <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-white">Technical Stack</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-white/10 bg-slate-900/40 backdrop-blur-xl p-6 hover:border-indigo-500/30 transition-all">
            <h3 class="text-xs font-mono font-semibold text-indigo-400 tracking-widest uppercase mb-4">Languages</h3>
            <ul class="space-y-2 text-sm text-slate-300">
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-indigo-400"></span>Python</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-indigo-400"></span>PHP</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-indigo-400"></span>TypeScript</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-indigo-400"></span>JavaScript</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-indigo-400"></span>SQL</li>
            </ul>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/40 backdrop-blur-xl p-6 hover:border-amber-500/30 transition-all">
            <h3 class="text-xs font-mono font-semibold text-amber-400 tracking-widest uppercase mb-4">AI & Agents</h3>
            <ul class="space-y-2 text-sm text-slate-300">
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-amber-400"></span>Strands Agents SDK</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-amber-400"></span>Google Gemini</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-amber-400"></span>Ollama</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-amber-400"></span>Multi-agent orchestration</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-amber-400"></span>Prompt engineering</li>
            </ul>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/40 backdrop-blur-xl p-6 hover:border-emerald-500/30 transition-all">
            <h3 class="text-xs font-mono font-semibold text-emerald-400 tracking-widest uppercase mb-4">Backend</h3>
            <ul class="space-y-2 text-sm text-slate-300">
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-emerald-400"></span>Laravel 13</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-emerald-400"></span>Livewire</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-emerald-400"></span>MySQL</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-emerald-400"></span>Redis</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-emerald-400"></span>REST APIs</li>
            </ul>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/40 backdrop-blur-xl p-6 hover:border-sky-500/30 transition-all">
            <h3 class="text-xs font-mono font-semibold text-sky-400 tracking-widest uppercase mb-4">DevOps</h3>
            <ul class="space-y-2 text-sm text-slate-300">
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-sky-400"></span>Docker</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-sky-400"></span>Git / GitHub Actions</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-sky-400"></span>Linux (Parrot OS)</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-sky-400"></span>Hostinger</li>
                <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-sky-400"></span>AWS Builder</li>
            </ul>
        </div>
    </div>
</section>

{{-- ============ CERTIFICATIONS ============ --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 pb-24">
    <div class="mb-10">
        <span class="text-[11px] font-mono tracking-[0.2em] text-sky-400/80 uppercase">Credentials</span>
        <h2 class="mt-2 text-3xl sm:text-4xl font-bold text-white">Certifications</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="rounded-2xl border border-emerald-500/30 bg-emerald-950/20 backdrop-blur-xl p-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px] font-mono font-bold text-emerald-400 tracking-widest uppercase">Completed</span>
                </div>
                <h3 class="font-semibold text-white leading-snug">Introduction to Cyber Security</h3>
                <p class="text-sm text-slate-400 mt-2">Cisco Networking Academy</p>
                <p class="text-xs font-mono text-slate-500 mt-4">2026</p>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-500/30 bg-amber-950/10 backdrop-blur-xl p-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px] font-mono font-bold text-amber-400 tracking-widest uppercase">In Progress</span>
                </div>
                <h3 class="font-semibold text-white leading-snug">Ethical Hacking</h3>
                <p class="text-sm text-slate-400 mt-2">Cisco Networking Academy</p>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-500/30 bg-amber-950/10 backdrop-blur-xl p-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px] font-mono font-bold text-amber-400 tracking-widest uppercase">In Progress</span>
                </div>
                <h3 class="font-semibold text-white leading-snug">Advanced Cyber Security</h3>
                <p class="text-sm text-slate-400 mt-2">EdX</p>
            </div>
        </div>

    </div>
</section>

{{-- ============ CONTACT ============ --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 pb-24">
    <div class="relative rounded-3xl border border-amber-500/20 bg-gradient-to-br from-amber-950/20 via-slate-900/60 to-indigo-950/30 backdrop-blur-xl p-10 sm:p-16 text-center overflow-hidden">
        <div class="absolute inset-0 -z-0">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-amber-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative">
            <span class="text-[11px] font-mono tracking-[0.2em] text-amber-400/80 uppercase">Get in touch</span>
            <h2 class="mt-3 text-3xl sm:text-5xl font-black text-white">
                Let's build<br>
                <span class="bg-gradient-to-r from-amber-400 to-indigo-400 bg-clip-text text-transparent">
                    something real.
                </span>
            </h2>
            <p class="mt-6 text-lg text-slate-300 max-w-2xl mx-auto">
                Open to roles in <strong class="text-white">AI agent engineering</strong>,
                <strong class="text-white">backend development</strong>, and
                <strong class="text-white">full-stack systems</strong>.
                Also available for freelance platform builds.
            </p>

            <div class="mt-10 flex flex-wrap justify-center gap-3">
                <a href="mailto:vumbiventures@gmail.com"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-slate-950 font-semibold text-sm shadow-lg hover:bg-amber-300 transition-all">
                    ✉️ Email me
                </a>
                <a href="https://www.linkedin.com/in/daniel-maina-040487263" target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/5 border border-white/10 text-slate-200 font-semibold text-sm hover:bg-white/10 hover:text-white transition-all">
                    💼 LinkedIn
                </a>
                <a href="https://github.com/Dante-VIQ" target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/5 border border-white/10 text-slate-200 font-semibold text-sm hover:bg-white/10 hover:text-white transition-all">
                    ⚙️ GitHub
                </a>
            </div>

            <p class="mt-10 text-xs font-mono text-slate-500 tracking-wider">
                📍 NAKURU, KENYA · REMOTE-FRIENDLY · AVAILABLE IMMEDIATELY
            </p>
        </div>
    </div>
</section>

@endsection