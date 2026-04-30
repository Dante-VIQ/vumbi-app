<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="@yield('robots', 'index, nofollow')">

    <title>@yield('title', 'Admin - Vumbi Ventures')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #2C2A24; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #AF7A4D; border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #FFB347; }

        .bg-deep-earth { background-color: #2C2A24; }
        .bg-terracotta { background-color: #AF7A4D; }
        .bg-sunflare { background-color: #FFB347; }
        .bg-indigo-night { background-color: #2A3B4C; }
        .text-raw-linen { color: #F0E9E0; }
        .text-sunflare { color: #FFB347; }
        .border-dust-mite { border-color: #C4B9A6; }
    </style>
    @stack('styles')
</head>
<body class="bg-deep-earth text-raw-linen antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar (visible on desktop, toggleable on mobile) --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-indigo-night bg-opacity-50 border-r border-dust-mite flex flex-col sidebar-scroll overflow-y-auto transform transition-transform duration-300 ease-in-out
                   lg:relative lg:translate-x-0
                   {{-- Mobile: hidden by default, shown when sidebarOpen is true --}}
                   "
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            {{-- Logo --}}
            <div class="p-6 border-b border-dust-mite">
                <a href="{{ route('dashboard') }}" class="text-2xl font-light tracking-wider">
                    VUMBI<span class="text-sunflare"> Ventures</span>
                    <span class="text-sm block text-[#C4B9A6] mt-1">Admin</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-3 p-3 rounded transition {{ request()->routeIs('admin.dashboard') ? 'bg-terracotta text-raw-linen' : 'hover:bg-terracotta hover:bg-opacity-30' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blogs.index') }}"
                           class="flex items-center gap-3 p-3 rounded transition {{ request()->routeIs('admin.blogs.*') ? 'bg-terracotta text-raw-linen' : 'hover:bg-terracotta hover:bg-opacity-30' }}">
                            <i class="fas fa-blog w-5"></i>
                            <span>Blog Posts</span>
                        </a>
                    </li>
                    <li>
                        <a href="/header"
                           class="flex items-center gap-3 p-3 rounded transition {{ request()->routeIs('admin.header') ? 'bg-terracotta text-raw-linen' : 'hover:bg-terracotta hover:bg-opacity-30' }}">
                            <i class="fas fa-image w-5"></i>
                            <span>Header Media</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/places"
                           class="flex items-center gap-3 p-3 rounded transition {{ request()->routeIs('admin.destinations.*') ? 'bg-terracotta text-raw-linen' : 'hover:bg-terracotta hover:bg-opacity-30' }}">
                            <i class="fas fa-map-marker-alt w-5"></i>
                            <span>Destinations</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cultures') }}"
                           class="flex items-center gap-3 p-3 rounded transition {{ request()->routeIs('admin.cultures.*') ? 'bg-terracotta text-raw-linen' : 'hover:bg-terracotta hover:bg-opacity-30' }}">
                            <i class="fas fa-users w-5"></i>
                            <span>Culture</span>
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- User Menu --}}
            <div class="p-4 border-t border-dust-mite">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-terracotta flex items-center justify-center">
                        <i class="fas fa-user text-raw-linen"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-[#C4B9A6] truncate">{{ Auth::user()->email ?? 'admin@vumbi.com' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 p-3 rounded hover:bg-terracotta hover:bg-opacity-30 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay when mobile sidebar is open --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

        {{-- Main Content Area --}}
        <main class="flex-1 overflow-y-auto w-full">
            {{-- Top Bar --}}
            <header class="bg-indigo-night bg-opacity-30 border-b border-dust-mite p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        {{-- Mobile hamburger button --}}
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded hover:bg-terracotta transition">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-light">@yield('header', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ url('/') }}" target="_blank"
                           class="px-4 py-2 border border-dust-mite text-raw-linen hover:bg-terracotta transition rounded text-sm hidden sm:inline-block">
                            <i class="fas fa-external-link-alt mr-2"></i>View Site
                        </a>
                        <a href="{{ url('/') }}" target="_blank"
                           class="p-2 border border-dust-mite text-raw-linen hover:bg-terracotta transition rounded sm:hidden">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 bg-terracotta bg-opacity-20 border border-terracotta p-4 rounded">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-500 bg-opacity-20 border border-red-500 p-4 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>