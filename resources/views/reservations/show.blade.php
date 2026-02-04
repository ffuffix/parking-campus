<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Reservation Details</h2>
                <p class="text-zinc-400">View reservation information.</p>
            </div>
            <a href="{{ route('reservations.index') }}" class="text-zinc-400 hover:text-white transition-colors py-2">
                Back to List
            </a>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6 space-y-8">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-white mb-1">Spot {{ $reservation->parkingSpot->spot_number ?? 'N/A' }}</h3>
                    <p class="text-zinc-400">Level {{ $reservation->parkingSpot->level ?? '-' }}</p>
                </div>
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-900/30 text-yellow-400 border-yellow-900',
                        'confirmed' => 'bg-green-900/30 text-green-400 border-green-900',
                        'checked_in' => 'bg-blue-900/30 text-blue-400 border-blue-900',
                        'checked_out' => 'bg-zinc-800 text-zinc-400 border-zinc-700',
                        'cancelled' => 'bg-red-900/30 text-red-400 border-red-900',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-sm border {{ $statusClasses[$reservation->status] ?? 'bg-zinc-800 text-zinc-400 border-zinc-700' }}">
                    {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-zinc-800">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider mb-2">Start Time</h3>
                    <p class="text-white text-lg">{{ $reservation->start_time->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider mb-2">End Time</h3>
                    <p class="text-white text-lg">{{ $reservation->end_time->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-zinc-800">
                <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider mb-2">Vehicle</h3>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-zinc-800 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2v0m12 0a2 2 0 012 2v0m-6 0a2 2 0 012-2v0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</p>
                        <p class="text-zinc-500 font-mono text-sm">{{ $reservation->vehicle->license_plate }}</p>
                    </div>
                </div>
            </div>

            @if($reservation->status === 'pending' || $reservation->status === 'confirmed')
                <div class="pt-6 border-t border-zinc-800 flex justify-end">
                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Cancel this reservation?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors font-medium">Cancel Reservation</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-layout>