<header x-data="{ mobileOpen: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-2' : 'bg-white/80 backdrop-blur-sm py-3'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 px-4 md:px-6">

    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 group">
            <div class="relative">
                <div class="absolute inset-0 bg-[#8B5A2B]/20 rounded-xl blur-md group-hover:bg-[#8B5A2B]/30 transition-all duration-300"></div>
                <div class="relative w-10 h-10 bg-[#8B5A2B] rounded-full flex items-center justify-center transition group-hover:scale-110">
                    <span class="text-white font-bold text-lg">V</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="font-black text-xl md:text-2xl tracking-tight text-gray-800 leading-tight">
                    Vumbi<span class="text-[#8B5A2B]"> Ventures</span>
                </span>
                <span class="text-xs text-gray-500 tracking-wider -mt-1">discover • build • grow</span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-1">
            @php
                $navItems = [
                    '/discover' => ['Discover', 'fas fa-compass'],
                    '/field-notes' => ['Field Notes', 'fas fa-pen-fade'],
                    '/services' => ['Services', 'fas fa-toolbox'],
                    '/about' => ['About', 'fas fa-info-circle'],
                    '/contact' => ['Contact', 'fas fa-envelope'],
                ];
            @endphp

            @foreach ($navItems as $url => $item)
                <a href="{{ $url }}"
                   class="relative px-4 py-2.5 text-gray-600 hover:text-[#8B5A2B] font-medium transition-all duration-200 group {{ request()->is(ltrim($url, '/') ?: '/') ? 'text-[#8B5A2B]' : '' }}">
                    <span class="relative z-10 flex items-center gap-2">
                        <i class="{{ $item[1] }} text-sm {{ request()->is(ltrim($url, '/') ?: '/') ? 'text-[#8B5A2B]' : 'text-gray-400 group-hover:text-[#8B5A2B]' }}"></i>
                        {{ $item[0] }}
                    </span>
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-[#8B5A2B] group-hover:w-full transition-all duration-300 {{ request()->is(ltrim($url, '/') ?: '/') ? 'w-full bg-[#8B5A2B]' : '' }}"></span>
                </a>
            @endforeach

            @role('master|engineer')
                <a href="/admin"
                   class="relative px-4 py-2.5 text-gray-600 hover:text-[#8B5A2B] font-medium transition-all duration-200 group">
                    <span class="relative z-10 flex items-center gap-2">
                        <i class="fas fa-lock text-sm text-gray-400 group-hover:text-[#8B5A2B]"></i>
                        Admin
                    </span>
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-[#8B5A2B] group-hover:w-full transition-all duration-300"></span>
                </a>
            @endrole
        </nav>

        <!-- Right Side Actions -->
        <div class="flex items-center gap-3">
            @auth
                <div class="hidden lg:block">
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2 pl-4 pr-3 py-2.5 rounded-xl bg-gradient-to-r from-[#F5EFE6] to-[#F5EFE6]/50 border border-[#8B5A2B]/20 text-[#8B5A2B] font-semibold hover:from-[#E8DFD5] hover:to-[#E8DFD5]/50 transition-all duration-200 shadow-sm">
                            <i class="fas fa-user-circle text-[#8B5A2B] text-lg"></i>
                            <span class="truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-[#8B5A2B] transition-transform duration-200"
                               :class="profileOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="profileOpen" @click.away="profileOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-[#8B5A2B]/10 overflow-hidden z-50">
                            <div class="p-4 bg-gradient-to-r from-[#F5EFE6] to-white border-b border-[#8B5A2B]/10">
                                <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-sm text-gray-500 truncate mt-1">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="p-2">
                                <a href="/profile"
                                   class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-[#F5EFE6] rounded-lg transition">
                                    <i class="fas fa-user-cog text-[#8B5A2B] w-5"></i>
                                    Profile Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-lg transition text-left">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth

            @guest
                <div class="hidden lg:flex items-center gap-2">
                    <a href="{{ route('login') }}"
                       class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:text-[#8B5A2B] hover:bg-[#F5EFE6] rounded-xl transition-all duration-200">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-5 py-2.5 bg-gradient-to-r from-[#8B5A2B] to-[#5C3A1E] text-white rounded-xl text-sm font-semibold hover:from-[#5C3A1E] hover:to-[#8B5A2B] transition-all duration-200 shadow-md shadow-[#8B5A2B]/20 flex items-center gap-2">
                        <i class="fas fa-user-plus text-xs"></i>
                        Get Started
                    </a>
                </div>
            @endguest

            <!-- Primary CTA Button -->
            <a href="/discover"
               class="hidden lg:flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#8B5A2B] to-[#5C3A1E] text-white rounded-xl text-sm font-semibold hover:from-[#5C3A1E] hover:to-[#8B5A2B] transition-all duration-200 shadow-md shadow-[#8B5A2B]/20 ml-2">
                <i class="fas fa-compass"></i>
                Start Exploring
            </a>

            <!-- Mobile Menu Button -->
            <button @click="mobileOpen = !mobileOpen"
                    class="lg:hidden flex items-center justify-center w-12 h-12 rounded-xl bg-[#F5EFE6] border border-[#8B5A2B]/20 text-[#8B5A2B] hover:bg-[#E8DFD5] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/30">
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" class="w-5 h-5" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="lg:hidden fixed top-[72px] left-4 right-4 bg-white rounded-2xl shadow-2xl border border-[#8B5A2B]/10 overflow-hidden z-50"
         @click.away="mobileOpen = false"
         x-cloak>

        <div class="max-h-[calc(100vh-100px)] overflow-y-auto">
            <div class="p-4 bg-gradient-to-r from-[#F5EFE6] to-white border-b border-[#8B5A2B]/10">
                <span class="text-sm font-semibold text-[#8B5A2B]">MENU</span>
            </div>

            <nav class="p-3 space-y-1">
                @foreach ($navItems as $url => $item)
                    <a href="{{ $url }}" @click="mobileOpen = false"
                       class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-[#F5EFE6] hover:text-[#8B5A2B] transition-all duration-200 {{ request()->is(ltrim($url, '/') ?: '/') ? 'bg-[#F5EFE6] text-[#8B5A2B] font-medium' : '' }}">
                        <i class="{{ $item[1] }} w-5 text-center {{ request()->is(ltrim($url, '/') ?: '/') ? 'text-[#8B5A2B]' : 'text-gray-400' }}"></i>
                        {{ $item[0] }}
                    </a>
                @endforeach

                @role('master|engineer')
                    <a href="/admin" @click="mobileOpen = false"
                       class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-[#F5EFE6] hover:text-[#8B5A2B] transition-all duration-200">
                        <i class="fas fa-lock w-5 text-center text-gray-400"></i>
                        Admin
                    </a>
                @endrole

                <!-- Mobile CTA -->
                <div class="pt-4 mt-4 border-t border-[#8B5A2B]/10">
                    <a href="/discover" @click="mobileOpen = false"
                       class="flex items-center justify-center gap-2 px-5 py-4 bg-gradient-to-r from-[#8B5A2B] to-[#5C3A1E] text-white rounded-xl font-semibold shadow-md hover:from-[#5C3A1E] hover:to-[#8B5A2B] transition-all duration-200">
                        <i class="fas fa-compass"></i>
                        Start Exploring
                    </a>
                </div>

                <!-- Mobile Auth Section -->
                @auth
                    <div class="pt-4 mt-2 border-t border-[#8B5A2B]/10">
                        <div class="px-4 py-3 bg-[#F5EFE6]/80 rounded-xl border border-[#8B5A2B]/10 mb-2">
                            <div class="font-medium text-gray-800 flex items-center gap-2">
                                <i class="fas fa-user-circle text-[#8B5A2B]"></i>
                                {{ Auth::user()->name }}
                            </div>
                            <div class="text-sm text-gray-500 truncate mt-1">{{ Auth::user()->email }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" @click="mobileOpen = false"
                                    class="w-full px-5 py-4 rounded-xl bg-red-50 text-red-600 font-semibold hover:bg-red-100 transition text-center flex items-center justify-center gap-2">
                                <i class="fas fa-sign-out-alt"></i>Log Out
                            </button>
                        </form>
                    </div>
                @endauth

                @guest
                    <div class="pt-4 mt-2 border-t border-[#8B5A2B]/10 space-y-2">
                        <a href="{{ route('login') }}" @click="mobileOpen = false"
                           class="flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl text-center font-semibold text-gray-700 hover:bg-[#F5EFE6] hover:text-[#8B5A2B] transition border border-gray-200">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </a>
                        <a href="{{ route('register') }}" @click="mobileOpen = false"
                           class="flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-gradient-to-r from-[#8B5A2B] to-[#5C3A1E] text-white font-semibold text-center hover:from-[#5C3A1E] hover:to-[#8B5A2B] transition shadow-md">
                            <i class="fas fa-user-plus"></i> Create Account
                        </a>
                    </div>
                @endguest
            </nav>
        </div>
    </div>
</header>