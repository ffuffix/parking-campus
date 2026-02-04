<x-layout>
    <div class="space-y-8">
        <!-- Welcome Section -->
        <div>
            <h1 class="text-3xl font-bold tracking-tight mb-2">Welcome back, {{ Auth::user()->name }}</h1>
            <p class="text-zinc-400">Here's what's happening with your parking.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-zinc-800 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-400">Upcoming Reservations</p>
                        <p class="text-2xl font-bold text-white">{{ $upcomingReservations->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-zinc-800 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2v0m12 0a2 2 0 012 2v0m-6 0a2 2 0 012-2v0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-400">My Vehicles</p>
                        <p class="text-2xl font-bold text-white">{{ $vehicles->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                 <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-zinc-800 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-400">Next Reservation</p>
                        <p class="text-2xl font-bold text-white">
                            @if($upcomingReservations->isNotEmpty())
                                {{ $upcomingReservations->first()->start_time->format('H:i') }}
                            @else
                                --
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('reservations.create') }}" class="group bg-gradient-to-br from-zinc-900 to-black border border-zinc-800 rounded-lg p-6 hover:border-zinc-600 transition-colors">
                <h3 class="font-bold text-lg mb-1 group-hover:text-white transition-colors">Book a Spot &rarr;</h3>
                <p class="text-zinc-400 text-sm">Find and reserve a parking spot for your vehicle.</p>
            </a>
            
            <a href="{{ route('vehicles.create') }}" class="group bg-gradient-to-br from-zinc-900 to-black border border-zinc-800 rounded-lg p-6 hover:border-zinc-600 transition-colors">
                <h3 class="font-bold text-lg mb-1 group-hover:text-white transition-colors">Register Vehicle &rarr;</h3>
                <p class="text-zinc-400 text-sm">Add a new vehicle to your profile.</p>
            </a>
        </div>

        <!-- Recent Activity -->
        <div>
            <h3 class="text-lg font-bold mb-4">Upcoming Reservations</h3>
             <div class="bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden">
                <table class="w-full text-left text-sm text-zinc-400">
                    <thead class="bg-black border-b border-zinc-800 text-xs uppercase font-medium text-zinc-500">
                        <tr>
                            <th class="px-6 py-4">Vehicle</th>
                            <th class="px-6 py-4">Spot</th>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse($upcomingReservations as $reservation)
                            <tr class="hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-4 text-white">{{ $reservation->vehicle->license_plate }}</td>
                                <td class="px-6 py-4">{{ $reservation->parkingSpot->spot_number ?? 'Assigned on arrival' }}</td>
                                <td class="px-6 py-4">{{ $reservation->start_time->format('M d, H:i') }}</td>
                                <td class="px-6 py-4">
                                     <span class="px-2 py-1 rounded text-xs border bg-green-900/30 text-green-400 border-green-900">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-zinc-500">No upcoming reservations.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>