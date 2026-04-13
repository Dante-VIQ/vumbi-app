<header x-data="{ mobileOpen: false, scrolled: false }" 
    @scroll.window="scrolled = (window.scrollY > 20)"
    @keyup.escape.window="mobileOpen = false; profileOpen = false"
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-2.5' : 'bg-white/80 backdrop-blur-sm py-3.5'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 px-4 md:px-6">

    <div class="max-w-7xl mx-auto flex items-center justify-between">

        {{-- Brand Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none">
            <div class="relative">
                <div class="absolute inset-0 bg-[#8B5A2B]/20 rounded-xl blur-md group-hover:bg-[#8B5A2B]/30 transition-all duration-300"></div>
                <div class="relative w-10 h-10 bg-[#8B5A2B] rounded-full flex items-center justify-center transition group-hover:scale-105 shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="Vumbi Ventures Logo" class="h-8 w-auto object-contain"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <span class="hidden text-white font-bold text-lg">V</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="font-black text-xl md:text-2xl tracking-tight text-zinc-900 leading-tight">
                    Vumbi<span class="text-[#8B5A2B]"> Ventures</span>
                </span>
                <span class="text-[10px] text-zinc-500 tracking-wider -mt-0.5 font-semibold uppercase">
                    safaris • expeditions • rentals
                </span>
            </div>
        </a>

        {{-- Desktop Navigation Links --}}
        <nav class="hidden lg:flex items-center gap-1">
            @php
                $navItems = [
                    ['name' => 'Safaris & Tours', 'route' => 'tours.index', 'url' => '/tours', 'icon' => 'fas fa-compass'],
                    ['name' => 'Destinations', 'route' => 'destinations.index', 'url' => '/destinations', 'icon' => 'fas fa-map-marked-alt'],
                    // ['name' => 'Culture & Stories', 'route' => 'cultures.index', 'url' => '/cultures', 'icon' => 'fas fa-feather-alt'],
                    ['name' => 'Travel Stories', 'route' => 'blog', 'url' => '/blog', 'icon' => 'fas fa-book-open'],
                    ['name' => 'Services', 'route' => 'pages.services', 'url' => '/services', 'icon' => 'fas fa-concierge-bell'],
                    ['name' => 'About Us', 'route' => 'about', 'url' => '/about', 'icon' => 'fas fa-info-circle'],
                    ['name' => 'Contact', 'route' => 'contact', 'url' => '/contact', 'icon' => 'fas fa-envelope'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route'] . '*') || request()->is(ltrim($item['url'], '/'));
                @endphp
                <a href="{{ Route::has($item['route']) ? route($item['route']) : url($item['url']) }}"
                    class="relative px-3 py-2 text-sm font-medium transition-all duration-200 group {{ $isActive ? 'text-[#8B5A2B] font-semibold' : 'text-zinc-700 hover:text-[#8B5A2B]' }}">
                    <span class="relative z-10 flex items-center gap-1.5">
                        <i class="{{ $item['icon'] }} text-xs {{ $isActive ? 'text-[#8B5A2B]' : 'text-zinc-400 group-hover:text-[#8B5A2B]' }}"></i>
                        {{ $item['name'] }}
                    </span>
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 bg-[#8B5A2B] transition-all duration-300 {{ $isActive ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
            @endforeach

            {{-- Vumbi Studio Switcher --}}
            <a href="{{ url('/studio') }}"
                class="ml-2 px-3 py-1.5 text-xs font-semibold text-zinc-700 bg-zinc-100 hover:bg-zinc-900 hover:text-amber-400 border border-zinc-200 rounded-full transition-all duration-200 flex items-center gap-1.5 shadow-sm">
                <i class="fas fa-code text-amber-600"></i>
                Studio (Tech)
            </a>

            @role('master|engineer')
                <a href="{{ url('/admin/africa') }}"
                    class="ml-1 px-2.5 py-1.5 text-xs font-semibold text-zinc-600 hover:text-[#8B5A2B] transition flex items-center gap-1">
                    <i class="fas fa-lock text-zinc-400"></i>
                    Admin
                </a>
            @endrole
        </nav>

        {{-- Desktop Action Buttons --}}
        <div class="flex items-center gap-3">
            @auth
                <div class="hidden lg:block relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" :aria-expanded="profileOpen"
                        class="flex items-center gap-2 pl-3.5 pr-3 py-2 rounded-xl bg-[#F5EFE6]/80 border border-[#8B5A2B]/20 text-[#8B5A2B] font-semibold hover:bg-[#F5EFE6] transition-all duration-200 text-sm focus:outline-none">
                        <i class="fas fa-user-circle text-[#8B5A2B] text-base"></i>
                        <span class="truncate max-w-[110px]">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-[#8B5A2B] transition-transform duration-200"
                            :class="profileOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak
                        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-zinc-100 overflow-hidden z-50">
                        <div class="p-3.5 bg-[#F5EFE6]/50 border-b border-zinc-100">
                            <p class="font-semibold text-sm text-zinc-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-zinc-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="p-1.5 text-sm">
                            <a href="{{ url('/profile') }}"
                                class="flex items-center gap-2.5 px-3 py-2 text-zinc-700 hover:bg-[#F5EFE6] rounded-xl transition">
                                <i class="fas fa-user-cog text-[#8B5A2B] text-xs w-4"></i>
                                Profile Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-red-600 hover:bg-red-50 rounded-xl transition text-left text-sm font-medium">
                                    <i class="fas fa-sign-out-alt text-xs w-4"></i>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}"
                    class="hidden lg:inline-block px-4 py-2 text-sm font-semibold text-zinc-700 hover:text-[#8B5A2B] hover:bg-[#F5EFE6] rounded-xl transition">
                    Sign In
                </a>
            @endguest

            {{-- Primary Safari Booking CTA --}}
            <a href="{{ url('/tours') }}"
                class="hidden lg:flex items-center gap-2 px-4.5 py-2.5 bg-gradient-to-r from-[#8B5A2B] to-[#5C3A1E] text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-[#8B5A2B]/20 transition-all duration-200">
                <i class="fas fa-compass text-xs"></i>
                Book Safari
            </a>

            {{-- Mobile Toggle Button --}}
            <button @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-label="Toggle Navigation Menu"
                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-[#F5EFE6] border border-[#8B5A2B]/20 text-[#8B5A2B] hover:bg-[#E8DFD5] transition focus:outline-none">
                <i class="fas" :class="mobileOpen ? 'fa-times text-lg' : 'fa-bars text-base'"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Dropdown Menu --}}
    <div x-show="mobileOpen" x-cloak
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden fixed top-[68px] left-4 right-4 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden z-50"
        @click.away="mobileOpen = false">

        <div class="max-h-[calc(100vh-100px)] overflow-y-auto p-4 space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-zinc-100">
                <span class="text-xs font-bold uppercase tracking-wider text-[#8B5A2B]">Explore Vumbi</span>
                <a href="{{ url('/studio') }}"
                    class="text-xs font-semibold text-zinc-900 bg-amber-400 hover:bg-amber-500 px-2.5 py-1 rounded-full transition">
                    Studio (Tech) →
                </a>
            </div>

            <nav class="space-y-1">
                @foreach ($navItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route'] . '*') || request()->is(ltrim($item['url'], '/'));
                    @endphp
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : url($item['url']) }}"
                        @click="mobileOpen = false"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ $isActive ? 'bg-[#F5EFE6] text-[#8B5A2B] font-semibold' : 'text-zinc-700 hover:bg-zinc-50' }}">
                        <i class="{{ $item['icon'] }} w-5 text-center {{ $isActive ? 'text-[#8B5A2B]' : 'text-zinc-400' }}"></i>
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </nav>

            <div class="pt-2 border-t border-zinc-100 space-y-2">
                <a href="{{ url('/tours') }}" @click="mobileOpen = false"
                    class="flex items-center justify-center gap-2 w-full py-3 bg-gradient-to-r from-[#8B5A2B] to-[#5C3A1E] text-white rounded-xl font-bold text-sm shadow-md">
                    <i class="fas fa-compass"></i>
                    Book Safari Expedition
                </a>

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-red-50 text-red-600 font-semibold text-xs transition">
                            Log Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" @click="mobileOpen = false"
                        class="block text-center w-full py-2.5 rounded-xl border border-zinc-200 text-zinc-700 font-semibold text-xs">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>