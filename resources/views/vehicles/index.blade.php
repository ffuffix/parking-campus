<x-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">My Vehicles</h2>
                <p class="text-zinc-400">Manage your registered vehicles.</p>
            </div>
            <a href="{{ route('vehicles.create') }}" class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                Add Vehicle
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($vehicles as $vehicle)
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6 hover:border-zinc-700 transition-colors group cursor-pointer" onclick="window.location.href = '{{ route('vehicles.show', $vehicle) }}';">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-lg">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                        <p class="text-zinc-400 font-mono text-sm">{{ $vehicle->license_plate }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-medium {{ $vehicle->type === 'electric' ? 'bg-green-900/30 text-green-400 border border-green-900' : 'bg-zinc-800 text-zinc-300 border border-zinc-700' }}">
                        {{ ucfirst($vehicle->type) }}
                    </span>
                </div>

                <div class="flex items-center gap-4 mt-6 text-sm text-zinc-500">
                    @if($vehicle->color)
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full border border-zinc-700" style="background-color: {{ $vehicle->color }}"></span>
                        {{ ucfirst($vehicle->color) }}
                    </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-zinc-800 flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity" onclick="event.stopPropagation();">
                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-zinc-400 hover:text-white transition-colors">Edit</a>
                    <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 border border-dashed border-zinc-800 rounded-lg">
                <p class="text-zinc-500 mb-4">No vehicles found.</p>
                <a href="{{ route('vehicles.create') }}" class="text-white hover:underline">Add your first vehicle</a>
            </div>
            @endforelse
        </div>
    </div>
</x-layout>