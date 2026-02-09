<x-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Parking Spots</h2>
                <p class="text-zinc-400">Manage all parking spots.</p>
            </div>
            <a href="{{ route('parkingSpots.create') }}" class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                Add Spot
            </a>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden">
            <table class="w-full text-left text-sm text-zinc-400">
                <thead class="bg-black borde r-b border-zinc-800 text-xs uppercase font-medium text-zinc-500">
                    <tr>
                        <th class="px-6 py-4">Spot Number</th>
                        <th class="px-6 py-4">Level</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse ($parkingSpots as $spot)
                        <tr class="hover:bg-zinc-800/50 transition-colors cursor-pointer" onclick="if(event.target.closest('a') === null) { window.location.href = '{{ route('parkingSpots.show', $spot) }}'; }">
                            <td class="px-6 py-4 font-mono text-white">{{ $spot->spot_number }}</td>
                            <td class="px-6 py-4">{{ $spot->level }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs border {{ $spot->is_active ? 'bg-green-900/30 text-green-400 border-green-900' : 'bg-red-900/30 text-red-400 border-red-900' }}">
                                    {{ $spot->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation();">
                                <a href="{{ route('parkingSpots.edit', $spot) }}" class="text-zinc-400 hover:text-white transition-colors">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-500">No spots found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>