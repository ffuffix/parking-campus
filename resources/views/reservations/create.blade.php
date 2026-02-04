<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-bold tracking-tight">New Reservation</h2>
            <p class="text-zinc-400">Book a parking spot for your vehicle.</p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <form action="{{ route('reservations.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Vehicle Selection -->
                <div>
                    <label for="vehicle_id" class="block text-sm font-medium text-zinc-400 mb-1">Select Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors" required>
                        <option value="" disabled selected>Choose a vehicle...</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->license_plate }} ({{ $vehicle->brand }} {{ $vehicle->model }})
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    @if($vehicles->isEmpty())
                        <p class="text-xs text-yellow-500 mt-1">You need to <a href="{{ route('vehicles.create') }}" class="underline">add a vehicle</a> first.</p>
                    @endif
                </div>

                <!-- Spot Selection (Simplified) -->
                <div>
                    <label for="parking_spot_id" class="block text-sm font-medium text-zinc-400 mb-1">Select Parking Spot</label>
                    <select name="parking_spot_id" id="parking_spot_id" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors" required>
                        <option value="" disabled selected>Choose a spot...</option>
                        @foreach($parkingSpots as $spot)
                            <option value="{{ $spot->id }}" {{ old('parking_spot_id') == $spot->id ? 'selected' : '' }}>
                                Spot {{ $spot->spot_number }} (Level {{ $spot->level }}) - {{ $spot->description ?? 'Standard Spot' }}
                            </option>
                        @endforeach
                    </select>
                    @error('parking_spot_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Date & Time -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-zinc-400 mb-1">Start Time</label>
                        <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors scheme-dark" required>
                        @error('start_time') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-zinc-400 mb-1">End Time</label>
                        <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}" class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors scheme-dark" required>
                        @error('end_time') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-800">
                    <a href="{{ route('reservations.index') }}" class="text-zinc-400 hover:text-white transition-colors text-sm">Cancel</a>
                    <button type="submit" class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                        Confirm Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>