<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.parking-spots') }}" class="text-zinc-400 hover:text-white transition-colors mb-4 inline-block">&larr; Back to Parking Spots</a>
            <h2 class="text-2xl font-bold tracking-tight">Edit Parking Spot #{{ $parkingSpot->spot_number }}</h2>
            <p class="text-zinc-400">Update parking spot details.</p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <form method="POST" action="{{ route('parkingSpots.update', $parkingSpot) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Zone -->
                <div>
                    <label for="zone_id" class="block text-sm font-medium text-zinc-400 mb-1">Zone</label>
                    <select id="zone_id" name="zone_id" required
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                        <option value="">Select a zone</option>
                        @foreach (\App\Models\Zone::all() as $zone)
                        <option value="{{ $zone->id }}" {{ old('zone_id', $parkingSpot->zone_id) == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                        @endforeach
                    </select>
                    @error('zone_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Spot Number -->
                <div>
                    <label for="spot_number" class="block text-sm font-medium text-zinc-400 mb-1">Spot Number</label>
                    <input id="spot_number" type="text" name="spot_number" value="{{ old('spot_number', $parkingSpot->spot_number) }}" required
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                    @error('spot_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-zinc-400 mb-1">Type</label>
                    <select id="type" name="type" required
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                        <option value="">Select type</option>
                        <option value="regular" {{ old('type', $parkingSpot->type) == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="electric" {{ old('type', $parkingSpot->type) == 'electric' ? 'selected' : '' }}>Electric</option>
                        <option value="handicap" {{ old('type', $parkingSpot->type) == 'handicap' ? 'selected' : '' }}>Handicap</option>
                    </select>
                    @error('type') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label for="is_active" class="inline-flex items-center">
                        <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', $parkingSpot->is_active) ? 'checked' : '' }} class="rounded bg-zinc-900 border-zinc-700 text-white focus:ring-white">
                        <span class="ml-2 text-sm text-zinc-400">Active</span>
                    </label>
                    @error('is_active') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-white text-black py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                        Update Parking Spot
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>