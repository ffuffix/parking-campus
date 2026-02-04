<x-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">My Reservations</h2>
                <p class="text-zinc-400">View and manage your parking reservations.</p>
            </div>
            <a href="{{ route('reservations.create') }}" class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                New Reservation
            </a>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-400">
                    <thead class="bg-black border-b border-zinc-800 text-xs uppercase font-medium text-zinc-500">
                        <tr>
                            <th class="px-6 py-4">Vehicle</th>
                            <th class="px-6 py-4">Spot</th>
                            <th class="px-6 py-4">Date & Time</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse ($reservations as $reservation)
                            <tr class="hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-white">{{ $reservation->vehicle->license_plate }}</div>
                                    <div class="text-xs">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-white">{{ $reservation->parkingSpot->spot_number ?? 'N/A' }}</span>
                                    <span class="text-xs block">Level {{ $reservation->parkingSpot->level ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-white">{{ $reservation->start_time->format('M d, Y') }}</div>
                                    <div class="text-xs">{{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-900/30 text-yellow-400 border-yellow-900',
                                            'confirmed' => 'bg-green-900/30 text-green-400 border-green-900',
                                            'checked_in' => 'bg-blue-900/30 text-blue-400 border-blue-900',
                                            'checked_out' => 'bg-zinc-800 text-zinc-400 border-zinc-700',
                                            'cancelled' => 'bg-red-900/30 text-red-400 border-red-900',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs border {{ $statusClasses[$reservation->status] ?? 'bg-zinc-800 text-zinc-400 border-zinc-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-3">
                                    @if($reservation->status === 'pending' || $reservation->status === 'confirmed')
                                        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Cancel this reservation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">Cancel</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-zinc-500">
                                    No reservations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>