<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Vumbi Ventures Tech Studio' }}</title>
    <meta name="description" content="{{ $description ?? 'Engineering scalable digital solutions and proprietary platforms for Africa.' }}">

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Vite Asset Loading -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="bg-[#07090E] text-slate-100 font-sans selection:bg-indigo-500 selection:text-white antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- Ambient 3D Glow Backdrops (Global) -->
    <div class="fixed top-[-10%] left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-gradient-to-tr from-amber-600/10 via-indigo-600/15 to-blue-500/10 blur-[160px] pointer-events-none rounded-full z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/10 blur-[180px] pointer-events-none rounded-full z-0"></div>

    <!-- Floating Navigation Bar -->
    <nav class="sticky top-4 z-50 max-w-6xl mx-auto px-4 w-full">
        <div class="bg-slate-900/60 border border-white/10 rounded-2xl px-5 py-3 backdrop-blur-xl shadow-[0_10px_30px_rgba(0,0,0,0.5)] flex items-center justify-between">
            
            <!-- Tech Studio Brand Logo -->
            <a href="/studio" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-amber-500 flex items-center justify-center font-black text-white text-lg shadow-md group-hover:scale-105 transition-transform">
                    V
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-extrabold tracking-tight text-white group-hover:text-amber-400 transition-colors">Vumbi Tech Studio</span>
                    <span class="text-[10px] font-mono text-slate-400 tracking-wider">A VUMBI VENTURES CO.</span>
                </div>
            </a>

            <!-- Navigation Links & Parent Site Return CTA -->
            <div class="flex items-center gap-3 sm:gap-6">
                <a href="/studio" class="text-xs font-semibold text-slate-300 hover:text-white transition-colors hidden sm:block">
                    Portfolio
                </a>
                <a href="/demo" class="text-xs font-semibold text-slate-300 hover:text-white transition-colors hidden sm:block">
                    Nafasi Demo
                </a>

                <!-- Back to Main Vumbi Ventures Link -->
                <a href="https://vumbiventures.com" target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-amber-300 hover:text-amber-200 backdrop-blur-md transition-all group">
                    <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Vumbi Ventures Main</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Dynamic Content Slot -->
    <main class="relative z-10 flex-grow">
        @yield('content')
    </main>

    {{-- Floating WhatsApp Action Button --}}
<div class="fixed bottom-6 right-6 z-50 group flex items-center gap-3">
    
    {{-- Hover Tooltip Badge --}}
    <div class="hidden sm:flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900/90 border border-white/10 backdrop-blur-xl shadow-2xl text-xs font-semibold text-slate-200 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 pointer-events-none">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span>Chat with Studio Team</span>
    </div>

    {{-- Glowing Button Link --}}
    <a href="https://wa.me/254734591543?text=Hello%20Vumbi%20Ventures%20Team%2C%20I%27m%20interested%20in%20learning%20more%20about%20your%20services." 
       target="_blank" 
       rel="noopener noreferrer" 
       aria-label="Contact us on WhatsApp"
       class="relative flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-[0_10px_25px_rgba(16,185,129,0.4)] hover:shadow-[0_15px_35px_rgba(16,185,129,0.6)] transform hover:-translate-y-1 active:translate-y-0 transition-all duration-300">
        
        {{-- Pulsing Background Effect --}}
        <span class="absolute -inset-1 rounded-2xl bg-emerald-500/40 animate-ping opacity-75 pointer-events-none"></span>

        {{-- WhatsApp Icon --}}
        <svg class="w-7 h-7 fill-current relative z-10" viewBox="0 0 24 24">
            <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.762.459 3.48 1.332 5.001l-1.416 5.169 5.291-1.387c1.467.8 3.122 1.222 4.781 1.223h.004c5.505 0 9.988-4.478 9.989-9.985 0-2.667-1.037-5.175-2.923-7.062a9.925 9.925 0 0-0-7.068-2.943zm0 18.281h-.003c-1.492 0-2.955-.4-4.233-1.16l-.303-.18-3.142.823.838-3.062-.198-.315a8.282 8.282 0 0 1-1.272-4.398c0-4.567 3.716-8.283 8.287-8.283 2.213 0 4.292.862 5.856 2.428a8.23 8.23 0 0 1 2.423 5.858c0 4.568-3.716 8.284-8.285 8.284z"/>
        </svg>
    </a>

</div>
    <!-- Global Studio Footer -->
    <footer class="border-t border-white/10 bg-slate-950/80 py-12 relative z-10 text-center">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-left">
                <p class="text-sm font-bold text-white">Vumbi Ventures Tech Studio</p>
                <p class="text-xs text-slate-400">Engineering digital infrastructure, health platforms, and custom software.</p>
            </div>

            <div class="flex items-center gap-6 text-xs text-slate-400">
                <a href="https://vumbiventures.com" class="hover:text-amber-400 transition-colors">Vumbi Ventures Main Site</a>
                <span>•</span>
                <a href="mailto:studio@vumbiventures.com" class="hover:text-amber-400 transition-colors">studio@vumbiventures.com</a>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-4 mt-8 pt-6 border-t border-white/5 text-xs text-slate-600 flex flex-col sm:flex-row justify-between items-center gap-2">
            <p>© {{ date('Y') }} Vumbi Ventures. Operating from Kenya.</p>
            <p class="font-mono text-[11px]">BUILDING FOR AFRICA</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>