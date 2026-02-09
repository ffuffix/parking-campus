<x-layout>
    <x-slot name="fullWidth">true</x-slot>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <!-- Animated gradient background -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-b from-black via-black to-zinc-950"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-gradient-to-b from-white/[0.03] to-transparent rounded-full blur-3xl"></div>
            <div class="absolute top-40 left-1/4 w-72 h-72 bg-white/[0.02] rounded-full blur-3xl animate-pulse" style="animation-duration: 4s;"></div>
            <div class="absolute top-60 right-1/4 w-96 h-96 bg-white/[0.015] rounded-full blur-3xl animate-pulse" style="animation-duration: 6s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32 text-center relative">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 bg-zinc-900/80 border border-zinc-800 rounded-full px-4 py-1.5 mb-8 backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-sm text-zinc-300">{{ \App\Models\ParkingSpot::where('is_active', true)->count() }} spots available right now</span>
            </div>

            <!-- Main heading -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight leading-[1.1] mb-6">
                <span class="text-white">Campus parking,</span><br>
                <span class="bg-gradient-to-r from-zinc-400 via-white to-zinc-400 bg-clip-text text-transparent">simplified.</span>
            </h1>

            <p class="text-lg sm:text-xl text-zinc-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Reserve your parking spot in seconds. No more circling the lot, no more being late to class. Just park and go.
            </p>

            <!-- CTA buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                @auth
                    <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center gap-2 bg-white text-black px-8 py-3.5 rounded-full font-semibold text-base hover:bg-zinc-100 transition-all shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_30px_rgba(255,255,255,0.15)]">
                        Go to Dashboard
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center gap-2 bg-white text-black px-8 py-3.5 rounded-full font-semibold text-base hover:bg-zinc-100 transition-all shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_30px_rgba(255,255,255,0.15)]">
                        Get Started Free
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 border border-zinc-700 text-zinc-300 px-8 py-3.5 rounded-full font-medium text-base hover:border-zinc-500 hover:text-white transition-all">
                        Sign In
                    </a>
                @endauth
            </div>

            <!-- Live stats row -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto">
                <div class="bg-zinc-900/50 border border-zinc-800/50 rounded-xl p-5 backdrop-blur-sm">
                    <p class="text-3xl font-bold text-white">{{ \App\Models\ParkingSpot::count() }}</p>
                    <p class="text-xs text-zinc-500 mt-1 uppercase tracking-wider">Total Spots</p>
                </div>
                <div class="bg-zinc-900/50 border border-zinc-800/50 rounded-xl p-5 backdrop-blur-sm">
                    <p class="text-3xl font-bold text-green-400">{{ \App\Models\ParkingSpot::where('is_active', true)->count() }}</p>
                    <p class="text-xs text-zinc-500 mt-1 uppercase tracking-wider">Available</p>
                </div>
                <div class="bg-zinc-900/50 border border-zinc-800/50 rounded-xl p-5 backdrop-blur-sm">
                    <p class="text-3xl font-bold text-white">{{ \App\Models\Zone::count() }}</p>
                    <p class="text-xs text-zinc-500 mt-1 uppercase tracking-wider">Zones</p>
                </div>
                <div class="bg-zinc-900/50 border border-zinc-800/50 rounded-xl p-5 backdrop-blur-sm">
                    <p class="text-3xl font-bold text-white">{{ \App\Models\User::count() }}</p>
                    <p class="text-xs text-zinc-500 mt-1 uppercase tracking-wider">Users</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section class="border-t border-zinc-800/50 bg-zinc-950/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center mb-16">
                <p class="text-sm text-zinc-500 uppercase tracking-widest mb-3">How it works</p>
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Three steps to stress-free parking</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="group relative">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-8 h-full hover:border-zinc-700 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <span class="text-black font-bold text-lg">1</span>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Register your vehicle</h3>
                        <p class="text-zinc-400 leading-relaxed">Add your vehicle details once. We'll remember your license plate, model, and type for instant bookings.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="group relative">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-8 h-full hover:border-zinc-700 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <span class="text-black font-bold text-lg">2</span>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Pick a spot & time</h3>
                        <p class="text-zinc-400 leading-relaxed">Browse available zones, choose a spot type that fits, and select your date and time with our date picker.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="group relative">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-8 h-full hover:border-zinc-700 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <span class="text-black font-bold text-lg">3</span>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Park & go</h3>
                        <p class="text-zinc-400 leading-relaxed">Show up, park in your reserved spot, and head to class. Check in and out right from your dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="border-t border-zinc-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center mb-16">
                <p class="text-sm text-zinc-500 uppercase tracking-widest mb-3">Features</p>
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Everything you need to park smarter</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature cards -->
                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6 hover:border-zinc-700 transition-colors">
                    <div class="w-10 h-10 bg-zinc-800 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Real-time Availability</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">See which spots are open right now. Live data means no surprises when you arrive.</p>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6 hover:border-zinc-700 transition-colors">
                    <div class="w-10 h-10 bg-zinc-800 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">EV Charging Spots</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">Dedicated spots for electric vehicles. Filter by type and charge while you're in class.</p>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6 hover:border-zinc-700 transition-colors">
                    <div class="w-10 h-10 bg-zinc-800 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Easy Management</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">Edit or cancel reservations in seconds. Manage multiple vehicles from one dashboard.</p>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6 hover:border-zinc-700 transition-colors">
                    <div class="w-10 h-10 bg-zinc-800 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Multiple Zones</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">Choose from different campus zones. Park close to your building, not across campus.</p>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6 hover:border-zinc-700 transition-colors">
                    <div class="w-10 h-10 bg-zinc-800 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2v0m12 0a2 2 0 012 2v0m-6 0a2 2 0 012-2v0" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Multi-Vehicle Support</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">Register cars, electric vehicles, and motorcycles. Switch between them when booking.</p>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6 hover:border-zinc-700 transition-colors">
                    <div class="w-10 h-10 bg-zinc-800 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Accessible Parking</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">Dedicated handicap-accessible spots in every zone. Guaranteed availability for those who need it.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Zones Preview Section -->
    @php
        $zones = \App\Models\Zone::withCount(['parkingspots', 'parkingspots as available_count' => function ($q) {
            $q->where('is_active', true);
        }])->get();
    @endphp

    @if($zones->isNotEmpty())
    <section class="border-t border-zinc-800/50 bg-zinc-950/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center mb-16">
                <p class="text-sm text-zinc-500 uppercase tracking-widest mb-3">Campus Zones</p>
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Find your perfect spot</h2>
                <p class="text-zinc-400 mt-4 max-w-lg mx-auto">Browse parking zones across campus. Each zone shows real-time availability so you know exactly where to head.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($zones as $zone)
                <div class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-6 hover:border-zinc-600 transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-white group-hover:text-white transition-colors">{{ $zone->name }}</h3>
                            @if($zone->description)
                                <p class="text-zinc-500 text-sm mt-1">{{ $zone->description }}</p>
                            @endif
                        </div>
                        <div class="w-10 h-10 bg-zinc-800 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-6 pt-4 border-t border-zinc-800">
                        <div>
                            <p class="text-2xl font-bold text-white">{{ $zone->available_count }}</p>
                            <p class="text-xs text-zinc-500">Available</p>
                        </div>
                        <div class="w-px h-8 bg-zinc-800"></div>
                        <div>
                            <p class="text-2xl font-bold text-zinc-400">{{ $zone->parkingspots_count }}</p>
                            <p class="text-xs text-zinc-500">Total</p>
                        </div>
                        <div class="ml-auto">
                            @php
                                $pct = $zone->parkingspots_count > 0 ? round(($zone->available_count / $zone->parkingspots_count) * 100) : 0;
                            @endphp
                            <div class="w-16 h-1.5 bg-zinc-800 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $pct > 50 ? 'bg-green-500' : ($pct > 20 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-xs text-zinc-500 mt-1 text-right">{{ $pct }}% free</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="border-t border-zinc-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="relative bg-zinc-900 border border-zinc-800 rounded-3xl p-12 sm:p-16 text-center overflow-hidden">
                <!-- Glow effect -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-40 bg-white/[0.03] rounded-full blur-3xl"></div>

                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight mb-4 relative">
                    Ready to never fight for<br>parking again?
                </h2>
                <p class="text-zinc-400 text-lg max-w-xl mx-auto mb-8 relative">
                    Join {{ \App\Models\User::count() }} students and staff who park smarter every day.
                </p>
                <div class="relative">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-white text-black px-8 py-3.5 rounded-full font-semibold hover:bg-zinc-100 transition-all">
                            Go to Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-black px-8 py-3.5 rounded-full font-semibold hover:bg-zinc-100 transition-all shadow-[0_0_20px_rgba(255,255,255,0.1)]">
                            Create Free Account
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</x-layout>