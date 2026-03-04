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

        <!-- Filter Tabs -->
        <div class="flex gap-2 border-b border-zinc-800 pb-0">
            <button onclick="filterReservations('all')" id="tab-all" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-white text-white -mb-px">
                All <span class="text-zinc-500 ml-1" id="count-all">{{ $reservations->count() }}</span>
            </button>
            <button onclick="filterReservations('upcoming')" id="tab-upcoming" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-400 hover:text-white -mb-px">
                Upcoming <span class="text-zinc-500 ml-1" id="count-upcoming">{{ $reservations->filter(fn($r) => $r->start_time->isFuture() && !in_array($r->status, ['cancelled', 'checked_out']))->count() }}</span>
            </button>
            <button onclick="filterReservations('past')" id="tab-past" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-400 hover:text-white -mb-px">
                Past <span class="text-zinc-500 ml-1" id="count-past">{{ $reservations->filter(fn($r) => $r->end_time->isPast() || in_array($r->status, ['cancelled', 'checked_out']))->count() }}</span>
            </button>
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
                        @php
                            $isUpcoming = $reservation->start_time->isFuture() && !in_array($reservation->status, ['cancelled', 'checked_out']);
                            $isPast = $reservation->end_time->isPast() || in_array($reservation->status, ['cancelled', 'checked_out']);
                        @endphp
                        <tr class="hover:bg-zinc-800/50 transition-colors cursor-pointer reservation-row"
                            data-upcoming="{{ $isUpcoming ? '1' : '0' }}"
                            data-past="{{ $isPast ? '1' : '0' }}"
                            onclick="if(event.target.closest('form') === null) { window.location.href = '{{ route('reservations.show', $reservation) }}'; }">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $reservation->vehicle->license_plate }}</div>
                                <div class="text-xs">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-white">{{ $reservation->parkingSpot->spot_number ?? 'N/A' }}</span>
                                <span class="text-xs block text-zinc-500">{{ $reservation->parkingSpot->zone->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white flex items-center gap-1.5">
                                    {{ $reservation->start_time->format('M d, Y') }}
                                    @if(isset($weatherWarnings[$reservation->id]))
                                        @if($weatherWarnings[$reservation->id]['bad_weather'])
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 text-xs" title="{{ $weatherWarnings[$reservation->id]['description'] }} — {{ $weatherWarnings[$reservation->id]['precipitation_probability'] }}% rain chance">
                                                {{ $weatherWarnings[$reservation->id]['icon'] }}⚠
                                            </span>
                                        @else
                                            <span class="text-sm" title="{{ $weatherWarnings[$reservation->id]['description'] }}">{{ $weatherWarnings[$reservation->id]['icon'] }}</span>
                                        @endif
                                    @endif
                                </div>
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
                            <td class="px-6 py-4 text-right flex justify-end gap-3" onclick="event.stopPropagation();">
                                @if($reservation->status === 'pending' || $reservation->status === 'confirmed')
                                <a href="{{ route('reservations.edit', $reservation) }}" class="text-zinc-400 hover:text-white transition-colors text-sm">Edit</a>
                                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Cancel this reservation?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-sm">Cancel</button>
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

    <script>
        function filterReservations(filter) {
            const rows = document.querySelectorAll('.reservation-row');
            const tabs = document.querySelectorAll('.tab-btn');

            tabs.forEach(tab => {
                tab.classList.remove('border-white', 'text-white');
                tab.classList.add('border-transparent', 'text-zinc-400');
            });
            document.getElementById('tab-' + filter).classList.remove('border-transparent', 'text-zinc-400');
            document.getElementById('tab-' + filter).classList.add('border-white', 'text-white');

            rows.forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === 'upcoming') {
                    row.style.display = row.dataset.upcoming === '1' ? '' : 'none';
                } else if (filter === 'past') {
                    row.style.display = row.dataset.past === '1' ? '' : 'none';
                }
            });
        }
    </script>
</x-layout>