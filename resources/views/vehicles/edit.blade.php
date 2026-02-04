<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-bold tracking-tight">Edit Vehicle</h2>
            <p class="text-zinc-400">Update vehicle details.</p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <form action="{{ route('vehicles.update', $vehicle) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="brand" class="block text-sm font-medium text-zinc-400 mb-1">Brand</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $vehicle->brand) }}" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors" required>
                        @error('brand') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-zinc-400 mb-1">Model</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors" required>
                        @error('model') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="license_plate" class="block text-sm font-medium text-zinc-400 mb-1">License Plate</label>
                    <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors font-mono uppercase" required>
                    @error('license_plate') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="color" class="block text-sm font-medium text-zinc-400 mb-1">Color</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $vehicle->color) }}" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                        @error('color') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-zinc-400 mb-1">Type</label>
                        <select name="type" id="type" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                            <option value="regular" {{ old('type', $vehicle->type) == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="electric" {{ old('type', $vehicle->type) == 'electric' ? 'selected' : '' }}>Electric</option>
                            <option value="motorcycle" {{ old('type', $vehicle->type) == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                        </select>
                        @error('type') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-800">
                    <a href="{{ route('vehicles.index') }}" class="text-zinc-400 hover:text-white transition-colors text-sm">Cancel</a>
                    <button type="submit" class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                        Update Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>