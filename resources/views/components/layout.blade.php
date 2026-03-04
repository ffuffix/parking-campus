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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                            </div>
                            <span class="font-bold text-white tracking-tight">CampusPark</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="flex items-center space-x-6">
                        <a href="/" class="text-sm text-zinc-400 hover:text-white transition-colors">Home</a>
                        <a href="{{ route('map') }}"
                            class="text-sm text-zinc-400 hover:text-white transition-colors">Map</a>
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="text-sm bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-sm text-zinc-400 hover:text-white transition-colors">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm text-zinc-400 hover:text-white transition-colors">Login</a>
                            <a href="{{ route('register') }}"
                                class="text-sm bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">Sign
                                Up</a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        @if(session('success') || session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                @if(session('success'))
                    <div id="flash-success"
                        class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 text-sm">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-green-400/60 hover:text-green-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div id="flash-error"
                        class="flex items-center gap-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg px-4 py-3 text-sm">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto text-red-400/60 hover:text-red-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
            <script>
                setTimeout(() => {
                    document.querySelectorAll('#flash-success, #flash-error').forEach(el => {
                        el.style.transition = 'opacity 0.5s';
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 500);
                    });
                }, 5000);
            </script>
        @endif

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