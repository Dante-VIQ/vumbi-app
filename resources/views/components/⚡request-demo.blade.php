<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>


<div class="min-h-screen bg-[#090D16] text-slate-100 relative overflow-hidden font-sans selection:bg-blue-500 selection:text-white">
    
    {{-- Ambient 3D Glow Backdrops --}}
    <div class="absolute top-[-10%] left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-gradient-to-tr from-blue-600/20 via-indigo-500/10 to-emerald-500/20 blur-[120px] pointer-events-none rounded-full"></div>
    <div class="absolute bottom-[10%] right-[-10%] w-[600px] h-[600px] bg-blue-600/10 blur-[150px] pointer-events-none rounded-full"></div>

    <div class="max-w-6xl mx-auto px-4 py-16 lg:py-24 relative z-10">

        {{-- Hero Header Section --}}
        <header class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-xl mb-6 shadow-2xl">
                <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-semibold tracking-wide uppercase text-slate-300">Live Pilot Phase Active</span>
            </div>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-200 to-slate-400 leading-tight mb-6">
                Experience Nafasi <br class="hidden sm:inline"/>In Real-Time
            </h1>
            
            <p class="text-lg text-slate-400 leading-relaxed max-w-2xl mx-auto">
                Test our intelligent emergency routing and booking engine in a live sandbox environment or watch a full walkthrough.
            </p>
        </header>

        {{-- Main Feature Split: 3D Video & Interactive Sandbox Card --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch mb-20">
            
            {{-- Interactive Video Card --}}
            <div class="lg:col-span-7 group relative bg-slate-900/40 border border-white/10 rounded-3xl p-4 sm:p-6 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] transition-all duration-500 hover:border-blue-500/30">
                <div class="flex items-center justify-between mb-4 px-2">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                        <span class="text-xs text-slate-500 font-mono ml-2">platform-walkthrough.mp4</span>
                    </div>
                    <span class="text-xs font-medium text-slate-400 bg-white/5 px-2.5 py-1 rounded-md border border-white/5">3 mins</span>
                </div>
                
                {{-- Video Container with 3D Depth Frame --}}
                <div class="relative aspect-video rounded-2xl overflow-hidden border border-white/10 shadow-inner bg-slate-950 group-hover:shadow-[0_0_30px_rgba(59,130,246,0.2)] transition-all duration-500">
                    <iframe class="w-full h-full object-cover" src="https://www.youtube.com/embed/YOUR_VIDEO_ID" title="Nafasi Healthcare Engine Demo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                
                <div class="mt-4 px-2">
                    <h3 class="text-base font-semibold text-white">Full Engine Tour</h3>
                    <p class="text-sm text-slate-400">See real-time dispatch, triage intelligence, and facility capacity routing live.</p>
                </div>
            </div>

            {{-- Sandbox Action Card with Floating Tactile Effect --}}
            <div class="lg:col-span-5 flex flex-col justify-between bg-gradient-to-b from-white/10 to-white/[0.02] border border-white/15 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.4)] relative overflow-hidden group">
                <div class="absolute -right-12 -top-12 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all duration-500"></div>

                <div>
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/30 mb-6">
                        ⚡
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-3">Live Interactive Sandbox</h2>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Jump into a pre-loaded testing ground with simulated facilities, instant bed allocation, and emergency routes. No sign-up required.
                    </p>
                </div>

                <div>
                    <a href="https://demo.vumbidna.com" target="_blank" rel="noopener noreferrer" 
                       class="relative group/btn w-full inline-flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 text-white font-bold text-base shadow-[0_10px_25px_rgba(37,99,235,0.4)] hover:shadow-[0_15px_35px_rgba(37,99,235,0.6)] transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                        <span>Launch Sandbox Environment</span>
                        <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <p class="text-xs text-slate-400 text-center mt-3">Separate test environment • Sample data</p>
                </div>
            </div>

        </div>

        {{-- WhatsApp Direct Conversion Card (Primary Call To Action) --}}
        <section class="relative bg-gradient-to-r from-emerald-950/40 via-slate-900/80 to-slate-900/40 border border-emerald-500/30 rounded-3xl p-8 sm:p-12 text-center backdrop-blur-2xl shadow-[0_20px_60px_rgba(16,185,129,0.15)] overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>

            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                Want to Become a Pilot Partner?
            </h2>
            <p class="text-slate-300 max-w-2xl mx-auto mb-8 text-base sm:text-lg">
                We are onboarding healthcare providers for early testing. Get custom setup, zero platform fees during pilot, and priority support.
            </p>

            <a href="https://wa.me/254745506182?text=Hello%20Nafasi%20Team%2C%20I%27d%20like%20to%20learn%20more%20about%20becoming%20a%20pilot%20tenant." 
               target="_blank" rel="noopener noreferrer" 
               class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-lg shadow-[0_10px_30px_rgba(16,185,129,0.4)] hover:shadow-[0_15px_40px_rgba(16,185,129,0.6)] transform hover:-translate-y-1 transition-all duration-200">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.762.459 3.48 1.332 5.001l-1.416 5.169 5.291-1.387c1.467.8 3.122 1.222 4.781 1.223h.004c5.505 0 9.988-4.478 9.989-9.985 0-2.667-1.037-5.175-2.923-7.062a9.925 9.925 0 0-0-7.068-2.943zm0 18.281h-.003c-1.492 0-2.955-.4-4.233-1.16l-.303-.18-3.142.823.838-3.062-.198-.315a8.282 8.282 0 0 1-1.272-4.398c0-4.567 3.716-8.283 8.287-8.283 2.213 0 4.292.862 5.856 2.428a8.23 8.23 0 0 1 2.423 5.858c0 4.568-3.716 8.284-8.285 8.284z"/></svg>
                <span>Connect via WhatsApp</span>
            </a>
        </section>

    </div>
</div>

{{-- Google Structured Data for Ranking Enhancement --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Nafasi Platform",
  "operatingSystem": "Web",
  "applicationCategory": "HealthApplication",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "KES"
  }
}
</script>