<!-- Navigation -->
<nav class="fixed w-full bg-white/90 backdrop-blur-md z-50 border-b border-[#E5E0D9]" x-data="{ mobileOpen: false }">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="#home" class="flex items-center space-x-2 group" @click="mobileOpen = false">
                <div
                    class="w-10 h-10 bg-[#8B5A2B] rounded-full flex items-center justify-center transform group-hover:scale-110 transition">
                    <span class="text-white text-xl font-bold">V</span>
                </div>
                <span class="text-xl font-semibold text-[#1A1A1A]">Vumbi<span class="text-[#8B5A2B]">
                        Ventures</span></span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-[#1A1A1A] hover:text-[#8B5A2B] transition font-medium">Home</a>
                <a href="/about" class="text-[#1A1A1A] hover:text-[#8B5A2B] transition font-medium">About</a>
                <a href="/services" class="text-[#1A1A1A] hover:text-[#8B5A2B] transition font-medium">Services</a>
                <a href="/ecosystem" class="text-[#1A1A1A] hover:text-[#8B5A2B] transition font-medium">Ecosystem</a>
                <a href="/blog" class="text-[#1A1A1A] hover:text-[#8B5A2B] transition font-medium">Field Notes</a>
                <a href="/contact" class="text-[#1A1A1A] hover:text-[#8B5A2B] transition font-medium">Contact</a>
                {{-- @auth --}}
                    @role('master|engineer')
                    <a href="/admin/africa" class="text-[#1A1A1A] hover:text-[#8B5A2B] transition font-medium">Africa</a>
                    @endrole
                {{-- @endauth --}}

                <div class="hidden md:block">
                    <a href="/contact"
                        class="bg-[#8B5A2B] text-white px-6 py-2 rounded-full hover:bg-[#6B421F] transition font-medium inline-flex items-center gap-2 group">
                        Partner With Us
                        <i class="fas fa-arrow-right text-sm transform group-hover:translate-x-1 transition"></i>
                    </a>
                </div>
                @auth
                    <div x-data="{ show: false }" @click.away="show = false" class="relative justify-end">
                        <button @click="show = !show"
                            class="flex items-center space-x-2 text-gray-700 hover:text-gold focus:outline-none">
                            <div class="text-right">
                                <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                            <i class="fas fa-chevron-down text-sm transition-transform duration-200"
                                :class="{ 'rotate-180': show }"></i>
                        </button>

                        <div x-show="show" x-cloak x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50 border border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-gold">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-navy text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-navy-dark transition hover:shadow-lg">
                            Register
                        </a>
                    </div>
                @endguest
            </div>
            <!-- CTA Button -->


            <!-- Mobile Menu Button -->
            <button @click="mobileOpen = !mobileOpen" class="md:hidden text-[#1A1A1A] p-2">
                <i x-show="!mobileOpen" class="fas fa-bars text-2xl"></i>
                <i x-show="mobileOpen" class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" @click.away="mobileOpen = false" x-transition class="md:hidden mt-4 pb-4">
            <div class="flex flex-col space-y-3">
                <a href="/" @click="mobileOpen = false"
                    class="text-[#1A1A1A] hover:text-[#8B5A2B] transition py-2">Home</a>
                <a href="/about" @click="mobileOpen = false"
                    class="text-[#1A1A1A] hover:text-[#8B5A2B] transition py-2">About</a>
                <a href="/services" @click="mobileOpen = false"
                    class="text-[#1A1A1A] hover:text-[#8B5A2B] transition py-2">Services</a>
                <a href="/ecosystem" @click="mobileOpen = false"
                    class="text-[#1A1A1A] hover:text-[#8B5A2B] transition py-2">Ecosystem</a>
                <a href="/blog" @click="mobileOpen = false"
                    class="text-[#1A1A1A] hover:text-[#8B5A2B] transition py-2">Field Notes</a>
                <a href="/contact" @click="mobileOpen = false"
                    class="text-[#1A1A1A] hover:text-[#8B5A2B] transition py-2">Contact</a>
                {{-- @auth --}}

                    @role('master|engineer')
                    <a href="/admin/africa" @click="mobileOpen = false"
                        class="text-[#1A1A1A] hover:text-[#8B5A2B] transition py-2">Analysis</a>
                    @endrole

                {{-- @endauth --}}

                <a href="#contact" @click="mobileOpen = false"
                    class="bg-[#8B5A2B] text-white px-6 py-2 rounded-full hover:bg-[#6B421F] transition text-center">Partner
                    With Us</a>

                @auth
                    <div class="pt-6 mt-6 border-t border-gray-200">
                        <div class="px-4 mb-4">
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <button wire:click="logout" @click="mobileMenuOpen = false"
                            class="block w-full text-left py-3 px-4 text-base font-semibold text-gray-700 hover:text-red-600 hover:bg-gray-50 rounded-lg transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                        </button>
                    </div>
                @endauth

                @guest
                    <div class="pt-6 mt-6 border-t border-gray-200 space-y-3">
                        <a href="{{ route('login') }}" @click="mobileMenuOpen = false"
                            class="block py-3 px-4 text-base font-semibold text-gray-700 hover:text-gold hover:bg-gray-50 rounded-lg transition">Login</a>
                        <a href="{{ route('register') }}" @click="mobileMenuOpen = false"
                            class="block bg-slate-800 text-white px-4 py-3 rounded-full text-base font-semibold text-center hover:bg-navy-dark transition hover:shadow-lg">
                            Register
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>