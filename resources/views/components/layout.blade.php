<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Campus Parking') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-black text-white selection:bg-white selection:text-black">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="border-b border-zinc-800 bg-black/50 backdrop-blur-xl fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="/" class="flex items-center gap-2">
                             <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                </svg>
                            </div>
                            <span class="font-bold text-white tracking-tight">CampusPark</span>
                        </a>
                        
                        @auth
                            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-zinc-400">
                                <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors {{ request()->routeIs('dashboard*') ? 'text-white' : '' }}">Dashboard</a>
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.parking-spots') }}" class="hover:text-white transition-colors {{ request()->routeIs('admin.parking-spots') ? 'text-white' : '' }}">Spots</a>
                                    <a href="{{ route('admin.reservations') }}" class="hover:text-white transition-colors {{ request()->routeIs('admin.reservations') ? 'text-white' : '' }}">Reservations</a>
                                @else
                                    <a href="{{ route('vehicles.index') }}" class="hover:text-white transition-colors {{ request()->routeIs('vehicles.*') ? 'text-white' : '' }}">Vehicles</a>
                                    <a href="{{ route('reservations.index') }}" class="hover:text-white transition-colors {{ request()->routeIs('reservations.*') ? 'text-white' : '' }}">Reservations</a>
                                @endif
                            </div>
                        @endauth
                    </div>
                    
                    <div class="flex items-center gap-4">
                        @auth
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-zinc-400">{{ Auth::user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-zinc-500 hover:text-white transition-colors">Sign Out</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">Log In</a>
                            <a href="{{ route('register') }}" class="text-sm bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">Sign Up</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
            {{ $slot }}
        </main>

        <footer class="border-t border-zinc-800 py-8 text-center text-sm text-zinc-600">
            &copy; {{ date('Y') }} Campus Parking. All rights reserved.
        </footer>
    </div>
</body>
</html>