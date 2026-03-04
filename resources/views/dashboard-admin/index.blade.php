<x-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold tracking-tight mb-2">Admin Dashboard</h1>
                <p class="text-zinc-400">Overview of the parking system.</p>
            </div>
            <div class="text-right">
                <span class="text-zinc-500 text-sm block">System Status</span>
                <span class="text-green-400 font-mono font-bold flex items-center gap-2 justify-end">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Operational
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <p class="text-zinc-400 text-sm font-medium">Total Spots</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $stats['totalSpots'] }}</p>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <p class="text-zinc-400 text-sm font-medium">Available Now</p>
                <p class="text-3xl font-bold text-green-400 mt-2">{{ $stats['availableSpots'] }}</p>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <p class="text-zinc-400 text-sm font-medium">Active Reservations</p>
                <p class="text-3xl font-bold text-amber-400 mt-2">{{ $stats['activeReservations'] }}</p>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <p class="text-zinc-400 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $stats['totalUsers'] }}</p>
            </div>
        </div>

        <!-- Zone Occupancy -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <h2 class="text-lg font-bold text-white mb-4">Per-Zone Occupancy</h2>
            <div class="space-y-4">
                @foreach($zoneStats as $zone)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-white font-medium">{{ $zone['name'] }}</span>
                            <span class="text-xs font-mono {{ $zone['available'] > 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $zone['occupied'] }} / {{ $zone['active'] }} occupied &middot; {{ $zone['available'] }} free
                            </span>
                        </div>
                        <div class="w-full bg-zinc-800 rounded-full h-3 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $zone['occupancy'] > 80 ? 'bg-red-500' : ($zone['occupancy'] > 50 ? 'bg-amber-500' : 'bg-green-500') }}"
                                 style="width: {{ $zone['occupancy'] }}%;"></div>
                        </div>
                        <p class="text-right text-xs text-zinc-500 mt-0.5">{{ $zone['occupancy'] }}%</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Hourly Distribution -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <h2 class="text-lg font-bold text-white mb-1">Reservations by Hour</h2>
                <p class="text-zinc-500 text-xs mb-4">Last 7 days</p>
                <div class="flex items-end gap-1 h-40">
                    @foreach($allTimeSlots as $slot)
                        <div class="flex-1 flex flex-col items-center justify-end h-full">
                            <div class="w-full bg-blue-500/80 rounded-t transition-all duration-500 min-h-[2px]"
                                 style="height: {{ ($slot['count'] / $maxSlotCount) * 100 }}%;"
                                 title="{{ $slot['hour'] }}: {{ $slot['count'] }} reservations"></div>
                        </div>
                    @endforeach
                </div>
                <div class="flex gap-1 mt-1">
                    @foreach($allTimeSlots as $i => $slot)
                        <div class="flex-1 text-center">
                            @if($i % 2 === 0)
                                <span class="text-[10px] text-zinc-600">{{ $slot['hour'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Day of Week -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <h2 class="text-lg font-bold text-white mb-1">Reservations by Day</h2>
                <p class="text-zinc-500 text-xs mb-4">Last 30 days</p>
                <div class="flex items-end gap-3 h-40">
                    @foreach($dayStats as $day)
                        <div class="flex-1 flex flex-col items-center justify-end h-full">
                            <span class="text-xs text-zinc-400 font-mono mb-1">{{ $day['count'] }}</span>
                            <div class="w-full bg-purple-500/80 rounded-t transition-all duration-500 min-h-[2px]"
                                 style="height: {{ ($day['count'] / $maxDayCount) * 100 }}%;"></div>
                            <span class="text-xs text-zinc-500 mt-1">{{ $day['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Reservations -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <h2 class="text-lg font-bold text-white mb-4">Recent Reservations</h2>
            @if($recentReservations->isEmpty())
                <p class="text-zinc-500 text-sm">No reservations yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-zinc-500 border-b border-zinc-800">
                                <th class="pb-2 font-medium">User</th>
                                <th class="pb-2 font-medium">Vehicle</th>
                                <th class="pb-2 font-medium">Spot</th>
                                <th class="pb-2 font-medium">Time</th>
                                <th class="pb-2 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50">
                            @foreach($recentReservations as $reservation)
                                <tr class="text-zinc-300">
                                    <td class="py-2.5">{{ $reservation->user->name ?? '—' }}</td>
                                    <td class="py-2.5 font-mono text-xs">{{ $reservation->vehicle->license_plate ?? '—' }}</td>
                                    <td class="py-2.5">
                                        {{ $reservation->parkingSpot->zone->name ?? '' }}
                                        {{ $reservation->parkingSpot->spot_number ?? '—' }}
                                    </td>
                                    <td class="py-2.5 text-xs font-mono">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('M d, H:i') }}
                                        &rarr;
                                        {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                    </td>
                                    <td class="py-2.5">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ match($reservation->status) {
                                                'confirmed' => 'bg-green-500/20 text-green-400',
                                                'pending' => 'bg-amber-500/20 text-amber-400',
                                                'checked_in' => 'bg-blue-500/20 text-blue-400',
                                                'checked_out' => 'bg-zinc-500/20 text-zinc-400',
                                                'cancelled' => 'bg-red-500/20 text-red-400',
                                                default => 'bg-zinc-500/20 text-zinc-400',
                                            } }}">
                                            {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.parking-spots') }}" class="group bg-zinc-900 border border-zinc-800 rounded-lg p-6 hover:border-zinc-600 transition-colors">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-white mb-2">Manage Parking Spots</h3>
                        <p class="text-zinc-400 text-sm">Create, edit, or deactivate parking spots.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('dashboard.admin.map') }}" class="group bg-zinc-900 border border-zinc-800 rounded-lg p-6 hover:border-zinc-600 transition-colors">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-white mb-2">Map — Add Spots</h3>
                        <p class="text-zinc-400 text-sm">Click on the map to place new parking spots.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.reservations') }}" class="group bg-zinc-900 border border-zinc-800 rounded-lg p-6 hover:border-zinc-600 transition-colors">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-white mb-2">Manage Reservations</h3>
                        <p class="text-zinc-400 text-sm">View all reservations and manage cancellations.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-layout>