<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vumbi Ventures — From overlooked places, we build remarkable solutions.</title>
    <!-- Tailwind via CDN + our custom classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom styles to extend Tailwind -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap');

        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #2C2A24; /* Deep Earth */
            color: #F0E9E0; /* Raw Linen */
        }

        /* Custom color utilities (since we're using CDN, we extend with custom classes) */
        .bg-deep-earth { background-color: #2C2A24; }
        .bg-terracotta { background-color: #AF7A4D; }
        .bg-sunflare { background-color: #FFB347; }
        .bg-indigo-night { background-color: #2A3B4C; }
        .bg-dust-mite { background-color: #C4B9A6; }

        .text-raw-linen { color: #F0E9E0; }
        .text-sunflare { color: #FFB347; }
        .text-terracotta { color: #AF7A4D; }
        .text-indigo-night { color: #2A3B4C; }

        .border-dust-mite { border-color: #C4B9A6; }

        /* Dust particle animation */
        .dust-particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background-color: #C4B9A6;
            border-radius: 50%;
            opacity: 0.3;
            pointer-events: none;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0) translateX(0); opacity: 0.1; }
            25% { opacity: 0.4; }
            50% { transform: translateY(-20vh) translateX(10px); opacity: 0.1; }
            75% { opacity: 0.3; }
            100% { transform: translateY(-40vh) translateX(-10px); opacity: 0; }
        }

        /* Manifesto interactive styles */
        .manifesto-item {
            transition: all 0.3s ease;
            border-left: 2px solid transparent;
            padding-left: 1.5rem;
            cursor: pointer;
        }

        .manifesto-item:hover {
            border-left-color: #FFB347;
            background: linear-gradient(90deg, rgba(255,180,71,0.05) 0%, rgba(255,180,71,0) 100%);
        }

        .manifesto-item.active {
            border-left-color: #FFB347;
            background: linear-gradient(90deg, rgba(255,180,71,0.1) 0%, rgba(255,180,71,0) 100%);
        }

        .manifesto-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease;
        }

        .manifesto-content.show {
            max-height: 300px; /* plenty of space */
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
    @livewireStyles
    
</head>
<body class="antialiased">

    <!-- Dust particles container (will be populated by JS) -->
    <div id="dust-container" class="fixed inset-0 pointer-events-none z-0 overflow-hidden"></div>

    <!-- Navigation -->
    <nav class="relative z-10 border-b border-[#C4B9A6] border-opacity-20">
        <div class="container mx-auto px-6 py-5 flex justify-between items-center">
            <a href="/" class="text-2xl font-light tracking-wider text-raw-linen">VUMBI<span class="text-sunflare">·</span></a>
            <div class="space-x-8 text-sm uppercase tracking-widest">
                <a href="/" class="text-raw-linen hover:text-sunflare transition">Home</a>
                <a href="#" class="text-raw-linen hover:text-sunflare transition">Ecosystem</a>
                <a href="#" class="text-raw-linen hover:text-sunflare transition">Field Notes</a>
                <a href="#" class="text-raw-linen hover:text-sunflare transition">Manifesto</a>
                <a href="#" class="bg-terracotta px-4 py-2 text-raw-linen hover:bg-sunflare hover:text-deep-earth transition">Contact</a>
            </div>
        </div>
    </nav>

    <main class="relative z-10">

        <!-- 1. HERO SECTION -->
        <section class="container mx-auto px-6 py-24 md:py-32 border-b border-dust-mite border-opacity-20">
            <div class="max-w-4xl">
                <span class="text-sunflare text-sm uppercase tracking-[0.3em]">Vumbi Ventures</span>
                <h1 class="text-5xl md:text-7xl font-light mt-6 leading-tight">
                    From overlooked places,<br>
                    <span class="text-sunflare">we build remarkable solutions.</span>
                </h1>
                <p class="text-xl md:text-2xl text-[#C4B9A6] mt-8 max-w-2xl">
                    A digital innovation foundry. We build practical technology for the problems the world ignores.
                </p>
                <div class="mt-12 flex gap-4">
                    <a href="#" class="btn bg-terracotta px-8 py-4 text-raw-linen hover:bg-sunflare hover:text-deep-earth transition uppercase tracking-widest text-sm">Explore Ecosystem</a>
                    <a href="#" class="btn border border-dust-mite px-8 py-4 text-raw-linen hover:bg-sunflare hover:border-sunflare hover:text-deep-earth transition uppercase tracking-widest text-sm">Read Manifesto</a>
                </div>
            </div>
        </section>

        <!-- 2. THE DUST MANIFESTO (Interactive) -->
        <section class="container mx-auto px-6 py-24 border-b border-dust-mite border-opacity-20">
            <div class="grid md:grid-cols-2 gap-16">
                <!-- Left column: the words "dust" -->
                <div>
                    <span class="text-sunflare text-sm uppercase tracking-[0.3em]">Our Name. Our Meaning.</span>
                    <h2 class="text-4xl md:text-5xl font-light mt-4">Dust is <span class="text-sunflare">ignored.</span><br>We see it differently.</h2>
                    <div class="mt-12 space-y-6">
                        <!-- Interactive items -->
                        <div class="manifesto-item" data-target="dust1">
                            <h3 class="text-xl text-sunflare">Dust is reality</h3>
                            <div id="dust1" class="manifesto-content text-[#C4B9A6] mt-2">
                                <p>Raw, unfiltered, and honest. It symbolizes the places people forget, the communities often overlooked, and the problems most companies never attempt to solve.</p>
                            </div>
                        </div>
                        <div class="manifesto-item" data-target="dust2">
                            <h3 class="text-xl text-sunflare">We go where others don't look</h3>
                            <div id="dust2" class="manifesto-content text-[#C4B9A6] mt-2">
                                <p>We listen where others don't hear. We build where others don't try. Because within what the world ignores lies extraordinary potential.</p>
                            </div>
                        </div>
                        <div class="manifesto-item" data-target="dust3">
                            <h3 class="text-xl text-sunflare">Dust is everywhere</h3>
                            <div id="dust3" class="manifesto-content text-[#C4B9A6] mt-2">
                                <p>It settles in overlooked places, travels unseen, and touches every corner of life. Just like the problems we choose to solve — and the solutions we build.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right column: visual quote -->
                <div class="bg-[#2A3B4C] bg-opacity-30 p-12 flex items-center border-l-4 border-sunflare">
                    <p class="text-2xl italic text-raw-linen">
                        "We go where others don't look. We listen where others don't hear. We build where others don't try."
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. BUILT FROM THE SPIRIT OF AFRICA -->
        <section class="container mx-auto px-6 py-24 border-b border-dust-mite border-opacity-20">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-sunflare text-sm uppercase tracking-[0.3em]">Our Foundation</span>
                    <h2 class="text-4xl md:text-5xl font-light mt-4">Built From the <span class="text-sunflare">Spirit of Africa</span></h2>
                    <p class="text-xl text-[#C4B9A6] mt-6">
                        Africa is not just our environment; it is our foundation. The continent's diversity of stories, traditions, challenges, and innovations fuels our imagination and guides our purpose.
                    </p>
                    <p class="text-lg mt-4">
                        We believe Africa is one of the greatest sources of ideas in the world. Its everyday realities inspire solutions that are practical, resourceful, and deeply human.
                    </p>
                </div>
                <div class="bg-[#AF7A4D] bg-opacity-20 p-16 text-center border border-dust-mite">
                    <span class="text-8xl block mb-4">✦</span>
                    <p class="text-2xl font-light italic">"We don't just represent Africa. We reflect its spirit of creation, endurance, and vision."</p>
                </div>
            </div>
        </section>

        <!-- 4. THE INNOVATION ECOSYSTEM (featuring SkillDNA) -->
        <section class="container mx-auto px-6 py-24 border-b border-dust-mite border-opacity-20">
            <div class="text-center mb-16">
                <span class="text-sunflare text-sm uppercase tracking-[0.3em]">Our Ecosystem</span>
                <h2 class="text-4xl md:text-5xl font-light mt-4">We don't just build one thing.<br>We build <span class="text-sunflare">the future.</span></h2>
            </div>

            <!-- Featured: SkillDNA -->
            <div class="bg-[#2A3B4C] bg-opacity-40 p-12 md:p-16 border border-dust-mite mb-16">
                <div class="max-w-3xl mx-auto text-center">
                    <span class="text-sunflare text-sm uppercase tracking-[0.2em]">Flagship Product</span>
                    <h3 class="text-5xl md:text-6xl font-light mt-4 mb-6">SkillDNA</h3>
                    <p class="text-xl text-[#C4B9A6] mb-8">
                        A platform that helps individuals discover their real abilities and chart a path to growth.
                        Unlocking human potential, one skill at a time.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <span class="bg-terracotta px-8 py-4 text-raw-linen uppercase tracking-widest text-sm">Coming Soon — 2 Months</span>
                        <a href="#" class="border border-dust-mite px-8 py-4 text-raw-linen hover:bg-sunflare hover:border-sunflare hover:text-deep-earth transition uppercase tracking-widest text-sm">Get Notified</a>
                    </div>
                </div>
            </div>

            <!-- Placeholder for future products -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="border border-dust-mite p-8 opacity-40">
                    <h4 class="text-sunflare text-xl mb-2">EdTech</h4>
                    <p class="text-sm">Coming next</p>
                </div>
                <div class="border border-dust-mite p-8 opacity-40">
                    <h4 class="text-sunflare text-xl mb-2">Automation</h4>
                    <p class="text-sm">In development</p>
                </div>
                <div class="border border-dust-mite p-8 opacity-40">
                    <h4 class="text-sunflare text-xl mb-2">Infrastructure</h4>
                    <p class="text-sm">On the horizon</p>
                </div>
            </div>
        </section>

        <!-- 5. OUR PHILOSOPHY (Three pillars) -->
        <section class="container mx-auto px-6 py-24 border-b border-dust-mite border-opacity-20">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-light">Our Philosophy</h2>
                <p class="text-xl text-[#C4B9A6] mt-4 max-w-2xl mx-auto">
                    Innovation is measured by usefulness, accessibility, and positive impact.
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="text-5xl mb-4 text-sunflare">🛠️</div>
                    <h3 class="text-2xl text-sunflare mb-3">Usefulness</h3>
                    <p class="text-[#C4B9A6]">If it doesn't solve a real problem, it's not finished yet.</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-4 text-sunflare">🌍</div>
                    <h3 class="text-2xl text-sunflare mb-3">Accessibility</h3>
                    <p class="text-[#C4B9A6]">Technology must serve everyone, not just the few.</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl mb-4 text-sunflare">⚡</div>
                    <h3 class="text-2xl text-sunflare mb-3">Impact</h3>
                    <p class="text-[#C4B9A6]">Real progress creates opportunity and transforms lives.</p>
                </div>
            </div>
        </section>

        <!-- 6. FIELD NOTES (Blog preview) -->
        <section class="container mx-auto px-6 py-24 border-b border-dust-mite border-opacity-20">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="text-sunflare text-sm uppercase tracking-[0.3em]">Field Notes</span>
                    <h2 class="text-4xl md:text-5xl font-light mt-2">Dispatches from<br>overlooked places</h2>
                </div>
                <a href="#" class="text-sunflare hover:underline hidden md:block">View all →</a>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Post 1 -->
                <article class="border border-dust-mite hover:border-sunflare transition p-6">
                    <div class="bg-[#AF7A4D] bg-opacity-20 h-48 mb-4 flex items-center justify-center text-4xl">
                        📍
                    </div>
                    <span class="text-xs text-sunflare uppercase tracking-wider">Destination</span>
                    <h3 class="text-xl mt-2 mb-3">The Market That Moves</h3>
                    <p class="text-sm text-[#C4B9A6]">Inside the logistics of a Nigerian open-air market.</p>
                    <a href="#" class="block mt-4 text-sunflare text-sm">Read →</a>
                </article>
                <!-- Post 2 -->
                <article class="border border-dust-mite hover:border-sunflare transition p-6">
                    <div class="bg-[#AF7A4D] bg-opacity-20 h-48 mb-4 flex items-center justify-center text-4xl">
                        👤
                    </div>
                    <span class="text-xs text-sunflare uppercase tracking-wider">People</span>
                    <h3 class="text-xl mt-2 mb-3">The Tailor Who Teaches</h3>
                    <p class="text-sm text-[#C4B9A6]">How a Kumasi seamstress built a secret school.</p>
                    <a href="#" class="block mt-4 text-sunflare text-sm">Read →</a>
                </article>
                <!-- Post 3 -->
                <article class="border border-dust-mite hover:border-sunflare transition p-6">
                    <div class="bg-[#AF7A4D] bg-opacity-20 h-48 mb-4 flex items-center justify-center text-4xl">
                        🎵
                    </div>
                    <span class="text-xs text-sunflare uppercase tracking-wider">Culture</span>
                    <h3 class="text-xl mt-2 mb-3">The Last Cassette Shop</h3>
                    <p class="text-sm text-[#C4B9A6]">Preserving sound in Lagos, one tape at a time.</p>
                    <a href="#" class="block mt-4 text-sunflare text-sm">Read →</a>
                </article>
            </div>

            <div class="text-center mt-10 md:hidden">
                <a href="#" class="text-sunflare underline">View all Field Notes →</a>
            </div>
        </section>

        <!-- 7. OUR PROMISE / FOOTER -->
        <footer class="container mx-auto px-6 py-16">
            <div class="grid md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <h3 class="text-3xl text-sunflare mb-4">Vumbi Ventures</h3>
                    <p class="text-[#C4B9A6] max-w-md">
                        From overlooked places, we build remarkable solutions. A digital innovation foundry rooted in the spirit of Africa.
                    </p>
                </div>
                <div>
                    <h4 class="text-sunflare mb-4 uppercase text-sm tracking-wider">Explore</h4>
                    <ul class="space-y-2 text-[#C4B9A6]">
                        <li><a href="#" class="hover:text-sunflare">Ecosystem</a></li>
                        <li><a href="#" class="hover:text-sunflare">SkillDNA</a></li>
                        <li><a href="#" class="hover:text-sunflare">Field Notes</a></li>
                        <li><a href="#" class="hover:text-sunflare">Manifesto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sunflare mb-4 uppercase text-sm tracking-wider">Connect</h4>
                    <ul class="space-y-2 text-[#C4B9A6]">
                        <li><a href="#" class="hover:text-sunflare">Contact</a></li>
                        <li><a href="#" class="hover:text-sunflare">Press</a></li>
                        <li><a href="#" class="hover:text-sunflare">Partners</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-dust-mite border-opacity-20 mt-12 pt-8 text-sm text-[#C4B9A6] flex justify-between">
                <span>© 2025 Vumbi Ventures</span>
                <span>Dust is everywhere. We build in it.</span>
            </div>
        </footer>
    </main>

    <!-- JavaScript for dust particles + interactive manifesto -->
    <script>
        // Dust particles
        function createDustParticles() {
            const container = document.getElementById('dust-container');
            if (!container) return;

            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.classList.add('dust-particle');

                // Random starting positions
                const left = Math.random() * 100;
                const top = Math.random() * 100;
                const delay = Math.random() * 15;
                const size = Math.random() * 3 + 1;

                particle.style.left = left + '%';
                particle.style.top = top + '%';
                particle.style.animationDelay = delay + 's';
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.opacity = Math.random() * 0.3;

                container.appendChild(particle);
            }
        }

        // Interactive manifesto: click to expand/collapse
        function initManifesto() {
            const items = document.querySelectorAll('.manifesto-item');

            items.forEach(item => {
                const targetId = item.getAttribute('data-target');
                const content = document.getElementById(targetId);

                if (!content) return;

                // Initially, first item open
                if (targetId === 'dust1') {
                    content.classList.add('show');
                    item.classList.add('active');
                }

                item.addEventListener('click', function(e) {
                    // Close all others
                    document.querySelectorAll('.manifesto-content').forEach(c => {
                        c.classList.remove('show');
                    });
                    document.querySelectorAll('.manifesto-item').forEach(i => {
                        i.classList.remove('active');
                    });

                    // Open this one
                    content.classList.add('show');
                    this.classList.add('active');
                });
            });
        }

        // Run when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            createDustParticles();
            initManifesto();
        });
    </script>
</body>
</html>
