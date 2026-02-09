<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.parking-spots') }}" class="text-zinc-400 hover:text-white transition-colors mb-4 inline-block">&larr; Back to Parking Spots</a>
            <h2 class="text-2xl font-bold tracking-tight">Parking Spot #{{ $parkingSpot->spot_number }}</h2>
            <p class="text-zinc-400">View parking spot details.</p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <div class="space-y-6">
                <div>
                    <h3 class="text-sm font-medium text-zinc-400 mb-2">Spot Number</h3>
                    <p class="text-white font-mono">{{ $parkingSpot->spot_number }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-zinc-400 mb-2">Zone</h3>
                    <p class="text-white">{{ $parkingSpot->zone->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-zinc-400 mb-2">Type</h3>
                    <p class="text-white capitalize">{{ $parkingSpot->type }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-zinc-400 mb-2">Status</h3>
                    <p>
                        <span class="px-2 py-1 rounded text-xs border {{ $parkingSpot->is_active ? 'bg-green-900/30 text-green-400 border-green-900' : 'bg-red-900/30 text-red-400 border-red-900' }}">
                            {{ $parkingSpot->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>

                <div class="pt-4 flex gap-4">
                    <a href="{{ route('parkingSpots.edit', $parkingSpot) }}" class="flex-1 bg-white text-black py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors text-center">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('parkingSpots.destroy', $parkingSpot) }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-900/30 text-red-400 border border-red-900 py-2 rounded-full font-medium hover:bg-red-900/50 transition-colors" onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>