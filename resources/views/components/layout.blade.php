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

<body class="font-sans antialiased bg-black text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Simple Navigation Header -->
        <header class="border-b border-zinc-800 bg-black/50 backdrop-blur-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="/" class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                            </div>
                            <span class="font-bold text-white tracking-tight">CampusPark</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="flex items-center space-x-6">
                        <a href="/" class="text-sm text-zinc-400 hover:text-white transition-colors">Home</a>
                        @auth
                        <a href="{{ route('dashboard') }}" class="text-sm bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-zinc-400 hover:text-white transition-colors">Logout</button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">Sign Up</a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        @if(isset($fullWidth))
        <main class="flex-grow">
            {{ $slot }}
        </main>
        @else
        <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
            {{ $slot }}
        </main>
        @endif

        <!-- Footer -->
        <footer class="border-t border-zinc-800 py-8 text-center text-sm text-zinc-600">
            &copy; {{ date('Y') }} Campus Parking. All rights reserved.
        </footer>
    </div>
</body>

</html>