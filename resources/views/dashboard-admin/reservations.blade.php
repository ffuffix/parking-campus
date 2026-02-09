<x-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">All Reservations</h2>
                <p class="text-zinc-400">View and manage all system reservations.</p>
            </div>
            <!-- Export or other actions could go here -->
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-400">
                    <thead class="bg-black border-b border-zinc-800 text-xs uppercase font-medium text-zinc-500">
                        <tr>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Vehicle</th>
                            <th class="px-6 py-4">Spot</th>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse ($reservations as $reservation)
                        <tr class="hover:bg-zinc-800/50 transition-colors cursor-pointer" onclick="if(event.target.closest('form') === null && event.target.closest('button') === null) { window.location.href = '{{ route('parkingSpots.show', $reservation->parkingSpot) }}'; }">
                            <td class="px-6 py-4 text-white font-medium">{{ $reservation->user->name }}</td>
                            <td class="px-6 py-4">
                                <div class="text-white">{{ $reservation->vehicle->license_plate }}</div>
                                <div class="text-xs">{{ $reservation->vehicle->brand }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($reservation->parkingSpot)
                                <span class="font-mono text-white">
                                    {{ $reservation->parkingSpot->spot_number }}
                                </span>
                                @else
                                <span class="font-mono text-white">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white">{{ $reservation->start_time->format('M d') }}</div>
                                <div class="text-xs">{{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs border {{ $reservation->status === 'cancelled' ? 'bg-red-900/30 text-red-400 border-red-900' : 'bg-green-900/30 text-green-400 border-green-900' }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation();">
                                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Cancel this reservation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">Cancel</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-500">No reservations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>