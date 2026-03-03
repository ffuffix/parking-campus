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
                    <select name="vehicle_id" id="vehicle_id"
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors"
                        required>
                        <option value="" disabled selected>Choose a vehicle...</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->license_plate }} ({{ $vehicle->brand }} {{ $vehicle->model }})
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    @if($vehicles->isEmpty())
                        <p class="text-xs text-yellow-500 mt-1">You need to <a href="{{ route('vehicles.create') }}"
                                class="underline">add a vehicle</a> first.</p>
                    @endif
                </div>

                <!-- Date & Time (moved before spot selection) -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-zinc-400 mb-1">Start Time</label>
                        <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}"
                            class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors scheme-dark"
                            required>
                        @error('start_time') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-zinc-400 mb-1">End Time</label>
                        <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}"
                            class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors scheme-dark"
                            required>
                        @error('end_time') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Weather Forecast Widget -->
                <div id="weather-widget" class="hidden">
                    <div class="bg-black border border-zinc-800 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                            <h3 class="text-sm font-medium text-zinc-400">Weather Forecast — <span
                                    id="weather-location">Haarlem</span></h3>
                        </div>

                        <div id="weather-loading" class="flex items-center gap-2 text-zinc-500 text-sm">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Loading weather data...
                        </div>

                        <div id="weather-error" class="hidden text-sm text-yellow-500">
                            Weather data unavailable for this date.
                        </div>

                        <div id="weather-data" class="hidden">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <span id="weather-icon" class="text-3xl"></span>
                                    <div>
                                        <p id="weather-description" class="text-white font-medium"></p>
                                        <p class="text-zinc-500 text-xs" id="weather-date"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-white text-lg font-semibold">
                                        <span id="weather-temp-max"></span>° / <span id="weather-temp-min"></span>°
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-zinc-900 rounded-lg p-3 text-center">
                                    <p class="text-zinc-500 text-xs mb-1">Rain chance</p>
                                    <p class="text-white font-semibold text-sm"><span id="weather-precip-prob"></span>%
                                    </p>
                                </div>
                                <div class="bg-zinc-900 rounded-lg p-3 text-center">
                                    <p class="text-zinc-500 text-xs mb-1">Precipitation</p>
                                    <p class="text-white font-semibold text-sm"><span id="weather-precip-sum"></span> mm
                                    </p>
                                </div>
                                <div class="bg-zinc-900 rounded-lg p-3 text-center">
                                    <p class="text-zinc-500 text-xs mb-1">Wind</p>
                                    <p class="text-white font-semibold text-sm"><span id="weather-wind"></span> km/h</p>
                                </div>
                            </div>

                            <div id="weather-warning"
                                class="hidden mt-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3 flex items-start gap-2">
                                <svg class="w-4 h-4 text-yellow-500 mt-0.5 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <p class="text-yellow-500 text-xs">
                                    <strong>Rain expected!</strong> Consider choosing a covered parking spot to protect
                                    your vehicle.
                                </p>
                            </div>

                            <div class="mt-4">
                                <p class="text-zinc-500 text-xs mb-2">Hourly forecast</p>
                                <div id="weather-hourly" class="flex gap-2 overflow-x-auto pb-2 scrollbar-thin"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Spot Selection (dynamic based on date range) -->
                <div>
                    <label for="parking_spot_id" class="block text-sm font-medium text-zinc-400 mb-1">Select Parking
                        Spot</label>

                    <!-- Hint shown before dates are selected -->
                    <div id="spots-hint"
                        class="text-sm text-zinc-500 bg-black border border-zinc-800 rounded-md py-3 px-3">
                        Select start and end time first to see available spots.
                    </div>

                    <!-- Loading state -->
                    <div id="spots-loading" class="hidden flex items-center gap-2 text-zinc-500 text-sm py-3 px-3">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Checking availability...
                    </div>

                    <!-- Spot select (hidden until loaded) -->
                    <select name="parking_spot_id" id="parking_spot_id"
                        class="hidden w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors"
                        required>
                        <option value="" disabled selected>Choose an available spot...</option>
                        {{-- Options populated dynamically --}}
                    </select>

                    <!-- Availability summary -->
                    <p id="spots-summary" class="hidden text-xs text-zinc-500 mt-1"></p>

                    @error('parking_spot_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-zinc-800">
                    <a href="{{ route('reservations.index') }}"
                        class="text-zinc-400 hover:text-white transition-colors text-sm">Cancel</a>
                    <button type="submit" id="submit-btn"
                        class="bg-white text-black px-4 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        Confirm Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const spotSelect = document.getElementById('parking_spot_id');
        const spotsHint = document.getElementById('spots-hint');
        const spotsLoading = document.getElementById('spots-loading');
        const spotsSummary = document.getElementById('spots-summary');
        const submitBtn = document.getElementById('submit-btn');
        const weatherWidget = document.getElementById('weather-widget');
        const weatherLoading = document.getElementById('weather-loading');
        const weatherError = document.getElementById('weather-error');
        const weatherData = document.getElementById('weather-data');
        const weatherWarning = document.getElementById('weather-warning');

        let lastFetchedDate = null;

        // Fetch available spots when both dates are set
        function onDateChange() {
            const startVal = startTimeInput.value;
            const endVal = endTimeInput.value;

            if (startVal && endVal && new Date(endVal) > new Date(startVal)) {
                fetchAvailableSpots(startVal, endVal);
            }

            // Weather: fetch when start date changes
            if (startVal) {
                const date = startVal.split('T')[0];
                if (date !== lastFetchedDate) {
                    lastFetchedDate = date;
                    fetchWeather(date);
                }
            }
        }

        startTimeInput.addEventListener('change', onDateChange);
        endTimeInput.addEventListener('change', onDateChange);

        async function fetchAvailableSpots(startTime, endTime) {
            spotsHint.classList.add('hidden');
            spotsLoading.classList.remove('hidden');
            spotSelect.classList.add('hidden');
            spotsSummary.classList.add('hidden');
            submitBtn.disabled = true;

            try {
                const params = new URLSearchParams({ start_time: startTime, end_time: endTime });
                const response = await fetch(`/api/available-spots?${params}`);
                if (!response.ok) throw new Error('Failed');

                const spots = await response.json();
                renderSpots(spots);
            } catch (err) {
                spotsLoading.classList.add('hidden');
                spotsHint.textContent = 'Failed to load available spots. Try again.';
                spotsHint.classList.remove('hidden');
            }
        }

        function renderSpots(spots) {
            spotsLoading.classList.add('hidden');

            const oldValue = spotSelect.value;
            spotSelect.innerHTML = '<option value="" disabled selected>Choose an available spot...</option>';

            const availableSpots = spots.filter(s => s.available);
            const unavailableSpots = spots.filter(s => !s.available);

            // Group by zone
            const zones = {};
            availableSpots.forEach(spot => {
                if (!zones[spot.zone_name]) zones[spot.zone_name] = [];
                zones[spot.zone_name].push(spot);
            });

            for (const [zoneName, zoneSpots] of Object.entries(zones)) {
                const group = document.createElement('optgroup');
                group.label = `${zoneName} (${zoneSpots.length} available)`;

                zoneSpots.forEach(spot => {
                    const option = document.createElement('option');
                    option.value = spot.id;
                    option.textContent = `Spot ${spot.spot_number} — ${spot.type.charAt(0).toUpperCase() + spot.type.slice(1)}`;
                    if (String(spot.id) === oldValue) option.selected = true;
                    group.appendChild(option);
                });

                spotSelect.appendChild(group);
            }

            spotSelect.classList.remove('hidden');
            spotsSummary.classList.remove('hidden');
            spotsSummary.textContent = `${availableSpots.length} of ${spots.length} spots available for this time range.`;

            if (availableSpots.length === 0) {
                spotSelect.classList.add('hidden');
                spotsHint.textContent = 'No spots available for the selected time range. Try different dates.';
                spotsHint.classList.remove('hidden');
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        }

        // Enable submit only when a spot is selected
        spotSelect.addEventListener('change', function () {
            submitBtn.disabled = !this.value;
        });

        // Weather functions
        async function fetchWeather(date) {
            weatherWidget.classList.remove('hidden');
            weatherLoading.classList.remove('hidden');
            weatherError.classList.add('hidden');
            weatherData.classList.add('hidden');

            try {
                const response = await fetch(`/api/weather/${date}`);
                if (!response.ok) throw new Error('Failed');

                const data = await response.json();
                renderWeather(data, date);
            } catch (err) {
                weatherLoading.classList.add('hidden');
                weatherError.classList.remove('hidden');
            }
        }

        function renderWeather(data, date) {
            weatherLoading.classList.add('hidden');
            weatherData.classList.remove('hidden');

            const daily = data.daily;
            if (!daily || !daily.temp_max) {
                weatherError.classList.remove('hidden');
                weatherData.classList.add('hidden');
                return;
            }

            document.getElementById('weather-icon').textContent = daily.icon;
            document.getElementById('weather-description').textContent = daily.description;
            document.getElementById('weather-date').textContent = new Date(date).toLocaleDateString('nl-NL', { weekday: 'long', day: 'numeric', month: 'long' });
            document.getElementById('weather-temp-max').textContent = Math.round(daily.temp_max);
            document.getElementById('weather-temp-min').textContent = Math.round(daily.temp_min);
            document.getElementById('weather-precip-prob').textContent = daily.precipitation_probability;
            document.getElementById('weather-precip-sum').textContent = daily.precipitation_sum;
            document.getElementById('weather-wind').textContent = Math.round(daily.wind_speed_max);

            if (daily.precipitation_probability > 50) {
                weatherWarning.classList.remove('hidden');
            } else {
                weatherWarning.classList.add('hidden');
            }

            const hourlyContainer = document.getElementById('weather-hourly');
            hourlyContainer.innerHTML = '';

            if (data.hourly && data.hourly.length > 0) {
                data.hourly.filter((_, i) => i % 2 === 0).forEach(hour => {
                    const el = document.createElement('div');
                    el.className = 'flex-shrink-0 bg-zinc-900 rounded-lg p-2 text-center min-w-[60px]';
                    el.innerHTML = `
                        <p class="text-zinc-500 text-xs">${hour.time}</p>
                        <p class="text-lg my-1">${hour.icon}</p>
                        <p class="text-white text-xs font-medium">${Math.round(hour.temperature)}°</p>
                    `;
                    hourlyContainer.appendChild(el);
                });
            }
        }

        // Trigger on page load if values exist (e.g., validation error redirect)
        if (startTimeInput.value && endTimeInput.value) {
            onDateChange();
        }
    </script>
</x-layout>