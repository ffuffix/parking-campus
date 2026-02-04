<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Vehicle Details</h2>
                <p class="text-zinc-400">View vehicle information.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('vehicles.edit', $vehicle) }}" class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                    Edit
                </a>
                <a href="{{ route('vehicles.index') }}" class="text-zinc-400 hover:text-white transition-colors py-2">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider mb-2">Vehicle</h3>
                    <p class="text-white text-lg font-medium">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider mb-2">License Plate</h3>
                    <p class="text-white font-mono text-lg">{{ $vehicle->license_plate }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-zinc-800">
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider mb-2">Color</h3>
                    <div class="flex items-center gap-2">
                        @if($vehicle->color)
                            <span class="w-4 h-4 rounded-full border border-zinc-700" style="background-color: {{ $vehicle->color }}"></span>
                        @endif
                        <p class="text-white">{{ ucfirst($vehicle->color) ?? 'N/A' }}</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider mb-2">Type</h3>
                    <p class="text-white">{{ ucfirst($vehicle->type) }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-zinc-800">
                <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider mb-2">Registration Date</h3>
                <p class="text-zinc-400">{{ $vehicle->created_at->format('F d, Y') }}</p>
            </div>
        </div>
    </div>
</x-layout>