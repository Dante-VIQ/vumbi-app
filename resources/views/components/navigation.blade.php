<nav x-data="{ mobileOpen:false, profileOpen:false }"
     class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-neutral-200">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex h-16 items-center justify-between">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-[#8B5A2B] rounded-full flex items-center justify-center transition group-hover:scale-110">
                    <span class="text-white font-bold text-lg">V</span>
                </div>

                <span class="text-lg sm:text-xl font-semibold tracking-tight">
                    Vumbi <span class="text-[#8B5A2B]">Ventures</span>
                </span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8 text-[15px] font-medium"
                 aria-label="Primary navigation">

                <a href="/discover" class="nav-link font-semibold text-[#8B5A2B]">
                    Discover
                </a>

                <a href="/blog" class="nav-link">
                    Guides
                </a>

                <a href="/ecosystem" class="nav-link">
                    Ecosystem
                </a>

                <a href="/services" class="nav-link">
                    Work With Us
                </a>

                <a href="/about" class="nav-link">
                    About
                </a>

                <a href="/contact" class="nav-link">
                    Contact
                </a>

                @role('master|engineer')
                    <a href="/admin/africa" class="nav-link">
                        Admin
                    </a>
                @endrole
            </nav>

            <!-- Right Side Actions -->
            <div class="hidden lg:flex items-center gap-5">

                <!-- PRIMARY CTA -->
                <a href="/discover"
                   class="bg-[#8B5A2B] text-white px-6 py-2.5 rounded-full
                          hover:bg-[#6B421F] transition shadow-sm hover:shadow-md font-medium">
                    Start Exploring
                </a>

                @auth
                    <div class="relative" @click.away="profileOpen=false">

                        <button @click="profileOpen=!profileOpen"
                                class="flex items-center gap-2 text-sm">

                            <div class="text-right leading-tight hidden xl:block">
                                <div class="font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            </div>

                            <div class="w-9 h-9 bg-neutral-200 rounded-full"></div>
                        </button>

                        <div x-show="profileOpen" x-transition
                             class="absolute right-0 mt-3 w-52 bg-white rounded-xl shadow-xl border p-2">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded-lg">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}"
                       class="bg-slate-900 text-white px-4 py-2 rounded-full text-sm">
                        Join
                    </a>
                @endauth

            </div>

            <!-- Mobile button -->
            <button @click="mobileOpen=true" class="lg:hidden p-2">
                <i class="fas fa-bars text-2xl"></i>
            </button>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="mobileOpen" x-transition
         class="fixed inset-0 bg-black/40 backdrop-blur-sm lg:hidden">

        <div @click.away="mobileOpen=false"
             class="absolute right-0 top-0 h-full w-80 bg-white shadow-2xl p-6 space-y-6">

            <div class="flex justify-between items-center">
                <span class="font-semibold text-lg">Menu</span>
                <button @click="mobileOpen=false">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="flex flex-col space-y-4 text-lg font-medium">

                <a href="/discover" @click="mobileOpen=false">Discover</a>
                <a href="/blog" @click="mobileOpen=false">Guides</a>
                <a href="/ecosystem" @click="mobileOpen=false">Ecosystem</a>
                <a href="/services" @click="mobileOpen=false">Work With Us</a>
                <a href="/about" @click="mobileOpen=false">About</a>
                <a href="/contact" @click="mobileOpen=false">Contact</a>

                @role('master|engineer')
                    <a href="/admin/africa" @click="mobileOpen=false">Admin</a>
                @endrole
            </div>

            <div class="pt-6 border-t space-y-4">

                <a href="/discover"
                   class="block text-center bg-[#8B5A2B] text-white py-3 rounded-full font-medium">
                    Start Exploring
                </a>

                @guest
                    <a href="{{ route('login') }}" class="block text-center py-2">Login</a>
                    <a href="{{ route('register') }}"
                       class="block text-center bg-slate-900 text-white py-3 rounded-full">
                        Join
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>