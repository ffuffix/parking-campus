<x-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('reservations.show', $reservation) }}" class="text-zinc-400 hover:text-white transition-colors mb-4 inline-block">&larr; Back to Reservation</a>
            <h2 class="text-2xl font-bold tracking-tight">Edit Reservation</h2>
            <p class="text-zinc-400">Update your reservation details.</p>
        </div>

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            .flatpickr-calendar {
                background: #18181b !important;
                border: 1px solid #27272a !important;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
                border-radius: 0.5rem !important;
            }

            .flatpickr-months .flatpickr-month,
            .flatpickr-current-month .flatpickr-monthDropdown-months,
            .flatpickr-weekdays,
            span.flatpickr-weekday {
                background: #18181b !important;
                color: #a1a1aa !important;
            }

            .flatpickr-current-month input.cur-year,
            .flatpickr-current-month .flatpickr-monthDropdown-months {
                color: #fff !important;
            }

            .flatpickr-day {
                color: #d4d4d8 !important;
                border-radius: 0.375rem !important;
            }

            .flatpickr-day:hover {
                background: #27272a !important;
                border-color: #3f3f46 !important;
            }

            .flatpickr-day.selected {
                background: #fff !important;
                color: #000 !important;
                border-color: #fff !important;
            }

            .flatpickr-day.today {
                border-color: #52525b !important;
            }

            .flatpickr-day.today:hover {
                background: #27272a !important;
                border-color: #52525b !important;
            }

            .flatpickr-day.flatpickr-disabled,
            .flatpickr-day.prevMonthDay,
            .flatpickr-day.nextMonthDay {
                color: #3f3f46 !important;
            }

            .flatpickr-months .flatpickr-prev-month,
            .flatpickr-months .flatpickr-next-month {
                fill: #a1a1aa !important;
            }

            .flatpickr-months .flatpickr-prev-month:hover svg,
            .flatpickr-months .flatpickr-next-month:hover svg {
                fill: #fff !important;
            }

            .flatpickr-time {
                border-top: 1px solid #27272a !important;
            }

            .flatpickr-time input,
            .flatpickr-time .flatpickr-am-pm {
                color: #fff !important;
                background: #18181b !important;
            }

            .flatpickr-time input:hover,
            .flatpickr-time input:focus,
            .flatpickr-time .flatpickr-am-pm:hover {
                background: #27272a !important;
            }

            .flatpickr-time .flatpickr-time-separator {
                color: #a1a1aa !important;
            }

            .numInputWrapper span {
                border-color: #27272a !important;
            }

            .numInputWrapper span:hover {
                background: #27272a !important;
            }

            .numInputWrapper span svg path {
                fill: #a1a1aa !important;
            }
        </style>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Current Reservation Summary -->
                <div class="bg-black rounded-lg p-5 border border-zinc-800 space-y-4">
                    <h3 class="text-sm font-medium text-zinc-500 uppercase tracking-wider">Reservation Summary</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-zinc-500 mb-1">Vehicle</p>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $reservation->vehicle->type === 'electric' ? 'bg-green-900/30 text-green-400 border border-green-900' : 'bg-zinc-800 text-zinc-300 border border-zinc-700' }}">
                                    {{ ucfirst($reservation->vehicle->type) }}
                                </span>
                                <p class="text-white text-sm">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</p>
                            </div>
                            <p class="font-mono text-zinc-400 text-xs mt-1">{{ $reservation->vehicle->license_plate }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 mb-1">Parking Spot</p>
                            <p class="text-white text-sm">Spot {{ $reservation->parkingSpot->spot_number }}</p>
                            <p class="text-zinc-400 text-xs mt-1">{{ $reservation->parkingSpot->zone->name ?? 'Zone' }} &middot; {{ ucfirst($reservation->parkingSpot->type) }}</p>
                        </div>
                    </div>

                    @php
                    $statusClasses = [
                    'pending' => 'bg-yellow-900/30 text-yellow-400 border-yellow-900',
                    'confirmed' => 'bg-green-900/30 text-green-400 border-green-900',
                    'checked_in' => 'bg-blue-900/30 text-blue-400 border-blue-900',
                    ];
                    @endphp
                    <div class="pt-3 border-t border-zinc-800 flex items-center justify-between">
                        <span class="text-xs text-zinc-500">Current Status</span>
                        <span class="px-2 py-1 rounded text-xs border {{ $statusClasses[$reservation->status] ?? 'bg-zinc-800 text-zinc-400 border-zinc-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                        </span>
                    </div>
                </div>

                <!-- Start DateTime Picker -->
                <div>
                    <label for="start_picker" class="block text-sm font-medium text-zinc-400 mb-2">Start Date & Time</label>
                    <div class="relative">
                        <input type="text" id="start_picker" placeholder="Pick start date & time" readonly
                            class="w-full bg-black border border-zinc-800 rounded-md py-2.5 px-3 pl-10 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors cursor-pointer">
                        <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @error('start_time') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- End DateTime Picker -->
                <div>
                    <label for="end_picker" class="block text-sm font-medium text-zinc-400 mb-2">End Date & Time</label>
                    <div class="relative">
                        <input type="text" id="end_picker" placeholder="Pick end date & time" readonly
                            class="w-full bg-black border border-zinc-800 rounded-md py-2.5 px-3 pl-10 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors cursor-pointer">
                        <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @error('end_time') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="start_time" id="start_time">
                <input type="hidden" name="end_time" id="end_time">

                <!-- Duration Preview -->
                <div class="bg-black rounded-lg p-4 border border-zinc-800">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-zinc-500">Estimated Duration</span>
                        <span id="duration-preview" class="text-sm text-white font-medium">--</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-zinc-800">
                    <a href="{{ route('reservations.show', $reservation) }}" class="text-zinc-400 hover:text-white transition-colors text-sm">Cancel</a>
                    <button type="submit" class="bg-white text-black px-6 py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                        Update Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        const startDefault = "{{ $reservation->start_time->format('Y-m-d H:i') }}";
        const endDefault = "{{ $reservation->end_time->format('Y-m-d H:i') }}";

        function updateDuration() {
            const startVal = document.getElementById('start_time').value;
            const endVal = document.getElementById('end_time').value;
            const el = document.getElementById('duration-preview');

            if (startVal && endVal) {
                const start = new Date(startVal);
                const end = new Date(endVal);
                const diff = end - start;
                if (diff > 0) {
                    const days = Math.floor(diff / 86400000);
                    const hours = Math.floor((diff % 86400000) / 3600000);
                    const mins = Math.floor((diff % 3600000) / 60000);
                    let text = '';
                    if (days > 0) text += days + 'd ';
                    if (hours > 0) text += hours + 'h ';
                    if (mins > 0) text += mins + 'min';
                    el.textContent = text.trim() || '--';
                } else {
                    el.textContent = 'Invalid range';
                }
            } else {
                el.textContent = '--';
            }
        }

        const startPicker = flatpickr("#start_picker", {
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "M j, Y \\a\\t H:i",
            defaultDate: startDefault,
            minDate: "today",
            minuteIncrement: 15,
            onChange: function(selectedDates, dateStr) {
                document.getElementById('start_time').value = dateStr;
                // Auto-set end picker min date
                if (endPicker) {
                    endPicker.set('minDate', dateStr);
                }
                updateDuration();
            }
        });

        const endPicker = flatpickr("#end_picker", {
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "M j, Y \\a\\t H:i",
            defaultDate: endDefault,
            minDate: startDefault,
            minuteIncrement: 15,
            onChange: function(selectedDates, dateStr) {
                document.getElementById('end_time').value = dateStr;
                updateDuration();
            }
        });

        // Initialize hidden inputs and duration
        document.getElementById('start_time').value = startDefault;
        document.getElementById('end_time').value = endDefault;
        updateDuration();
    </script>
</x-layout>