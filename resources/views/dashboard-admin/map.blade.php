<x-layout>
    <x-slot name="title">Admin — Manage Parking Map</x-slot>
    <x-slot name="fullWidth">true</x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <div class="relative">
        <!-- Map container -->
        <div id="map" class="w-full" style="height: calc(100vh - 64px - 73px);"></div>

        <!-- Admin Controls Panel -->
        <div
            class="absolute top-4 right-4 z-[1000] bg-black/90 backdrop-blur-xl border border-zinc-800 rounded-xl p-4 w-[300px]">
            <h3 class="text-white font-semibold text-sm mb-3">Admin: Manage Spots</h3>

            <!-- Mode Toggle -->
            <div class="flex gap-2 mb-4">
                <button onclick="setMode('view')" id="btn-view"
                    class="flex-1 text-xs py-1.5 px-3 rounded-lg bg-white text-black font-medium transition-colors">
                    View
                </button>
                <button onclick="setMode('add')" id="btn-add"
                    class="flex-1 text-xs py-1.5 px-3 rounded-lg bg-zinc-800 text-zinc-400 hover:bg-zinc-700 transition-colors">
                    + Add Spot
                </button>
            </div>

            <!-- Add Spot Form (hidden by default) -->
            <div id="add-spot-form" class="hidden space-y-3">
                <p class="text-zinc-400 text-xs">Click on the map to set the location, then fill in the details below.
                </p>

                <div id="coords-display" class="text-xs text-zinc-500 bg-zinc-900 rounded px-2 py-1.5 font-mono hidden">
                    Lat: <span id="coord-lat">—</span>, Lng: <span id="coord-lng">—</span>
                </div>

                <div>
                    <label class="block text-xs text-zinc-400 mb-1">Zone</label>
                    <select id="new-zone"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-md py-1.5 px-2 text-white text-sm focus:outline-none focus:border-white">
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-zinc-400 mb-1">Spot Number</label>
                    <input type="text" id="new-spot-number" placeholder="e.g. A-01"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-md py-1.5 px-2 text-white text-sm focus:outline-none focus:border-white">
                </div>

                <div>
                    <label class="block text-xs text-zinc-400 mb-1">Type</label>
                    <select id="new-type"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-md py-1.5 px-2 text-white text-sm focus:outline-none focus:border-white">
                        <option value="regular">Regular</option>
                        <option value="electric">Electric</option>
                        <option value="handicap">Handicap</option>
                    </select>
                </div>

                <div id="add-spot-error" class="hidden text-red-400 text-xs"></div>
                <div id="add-spot-success" class="hidden text-green-400 text-xs"></div>

                <button onclick="createSpot()" id="create-spot-btn"
                    class="w-full text-sm bg-white text-black py-2 rounded-lg font-medium hover:bg-zinc-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    Create Parking Spot
                </button>
            </div>

            <!-- Zone Legend -->
            <div id="zone-legend" class="space-y-2 mt-4 pt-4 border-t border-zinc-800">
                <p class="text-zinc-500 text-xs uppercase font-medium mb-2">Zones</p>
                @foreach($mapData['zones'] as $zone)
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full inline-block" style="background-color: {{ match ($zone['name']) {
                        'Visitors' => '#3b82f6',
                        'Staff' => '#10b981',
                        'Electric' => '#f59e0b',
                        'Students' => '#8b5cf6',
                        default => '#6b7280'
                    } }};"></span>
                                        <span class="text-zinc-300 text-sm">{{ $zone['name'] }}</span>
                                    </div>
                                    <span
                                        class="text-xs font-mono {{ $zone['available_spots'] > 0 ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $zone['available_spots'] }}/{{ $zone['max_spots'] }}
                                    </span>
                                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        const mapData = @json($mapData);
        const csrfToken = '{{ csrf_token() }}';

        const zoneColors = {
            'Visitors': '#3b82f6',
            'Staff': '#10b981',
            'Electric': '#f59e0b',
            'Students': '#8b5cf6',
        };

        let mode = 'view';
        let clickMarker = null;
        let selectedLat = null;
        let selectedLng = null;

        // Initialize map
        const map = L.map('map', { zoomControl: false })
            .setView([mapData.center.lat, mapData.center.lng], mapData.zoom);

        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer(mapData.tileUrl, {
            attribution: mapData.attribution,
            maxZoom: 19,
        }).addTo(map);

        // Store markers
        let markers = [];

        function addZoneMarkers(zones) {
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            zones.forEach(zone => {
                if (!zone.lat || !zone.lng) return;

                const color = zoneColors[zone.name] || '#6b7280';

                const circle = L.circleMarker([zone.lat, zone.lng], {
                    radius: 25 + (zone.max_spots / 5),
                    fillColor: color,
                    color: zone.available_spots > 0 ? color : '#ef4444',
                    weight: 2,
                    opacity: 0.9,
                    fillOpacity: 0.3,
                }).addTo(map);

                circle.bindPopup(`
                    <div style="font-family: Inter, sans-serif; min-width: 180px;">
                        <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600;">${zone.name}</h3>
                        <p style="margin: 0 0 8px 0; color: #666; font-size: 13px;">${zone.description || ''}</p>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-size: 13px;">Available</span>
                            <span style="font-weight: 600; color: ${zone.available_spots > 0 ? '#16a34a' : '#dc2626'};">${zone.available_spots} / ${zone.max_spots}</span>
                        </div>
                        <div style="background: #e5e7eb; border-radius: 4px; height: 6px; overflow: hidden;">
                            <div style="background: ${zone.occupancy_percentage > 80 ? '#ef4444' : zone.occupancy_percentage > 50 ? '#f59e0b' : '#22c55e'}; width: ${zone.occupancy_percentage}%; height: 100%; border-radius: 4px;"></div>
                        </div>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #888;">${zone.occupancy_percentage}% occupied</p>
                    </div>
                `);

                const label = L.marker([zone.lat, zone.lng], {
                    icon: L.divIcon({
                        className: 'zone-label',
                        html: `<div style="
                            background: ${color};
                            color: white;
                            padding: 4px 10px;
                            border-radius: 20px;
                            font-size: 12px;
                            font-weight: 600;
                            font-family: Inter, sans-serif;
                            white-space: nowrap;
                            text-align: center;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                            border: 2px solid rgba(255,255,255,0.3);
                        ">${zone.name}<br><span style="font-size: 10px; font-weight: 400;">${zone.available_spots} spots</span></div>`,
                        iconSize: [0, 0],
                        iconAnchor: [0, 0],
                    })
                }).addTo(map);

                markers.push(circle, label);
            });
        }

        addZoneMarkers(mapData.zones);

        // Mode switching
        function setMode(newMode) {
            mode = newMode;
            const btnView = document.getElementById('btn-view');
            const btnAdd = document.getElementById('btn-add');
            const form = document.getElementById('add-spot-form');

            if (mode === 'view') {
                btnView.className = 'flex-1 text-xs py-1.5 px-3 rounded-lg bg-white text-black font-medium transition-colors';
                btnAdd.className = 'flex-1 text-xs py-1.5 px-3 rounded-lg bg-zinc-800 text-zinc-400 hover:bg-zinc-700 transition-colors';
                form.classList.add('hidden');
                map.getContainer().style.cursor = '';
                if (clickMarker) { map.removeLayer(clickMarker); clickMarker = null; }
            } else {
                btnView.className = 'flex-1 text-xs py-1.5 px-3 rounded-lg bg-zinc-800 text-zinc-400 hover:bg-zinc-700 transition-colors';
                btnAdd.className = 'flex-1 text-xs py-1.5 px-3 rounded-lg bg-white text-black font-medium transition-colors';
                form.classList.remove('hidden');
                map.getContainer().style.cursor = 'crosshair';
            }
        }

        // Map click handler for adding spots
        map.on('click', function (e) {
            if (mode !== 'add') return;

            selectedLat = e.latlng.lat.toFixed(7);
            selectedLng = e.latlng.lng.toFixed(7);

            document.getElementById('coord-lat').textContent = selectedLat;
            document.getElementById('coord-lng').textContent = selectedLng;
            document.getElementById('coords-display').classList.remove('hidden');
            document.getElementById('create-spot-btn').disabled = false;

            if (clickMarker) map.removeLayer(clickMarker);

            clickMarker = L.marker([e.latlng.lat, e.latlng.lng], {
                icon: L.divIcon({
                    className: '',
                    html: '<div style="width: 24px; height: 24px; background: #ef4444; border: 3px solid white; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.5);"></div>',
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                })
            }).addTo(map);
        });

        // Create parking spot
        async function createSpot() {
            const errorEl = document.getElementById('add-spot-error');
            const successEl = document.getElementById('add-spot-success');
            const btn = document.getElementById('create-spot-btn');

            errorEl.classList.add('hidden');
            successEl.classList.add('hidden');

            const zoneId = document.getElementById('new-zone').value;
            const spotNumber = document.getElementById('new-spot-number').value.trim();
            const type = document.getElementById('new-type').value;

            if (!spotNumber) {
                errorEl.textContent = 'Please enter a spot number.';
                errorEl.classList.remove('hidden');
                return;
            }

            if (!selectedLat || !selectedLng) {
                errorEl.textContent = 'Please click on the map to set the location.';
                errorEl.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Creating...';

            try {
                const response = await fetch('/api/admin/parking-spots', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        zone_id: zoneId,
                        spot_number: spotNumber,
                        type: type,
                        latitude: parseFloat(selectedLat),
                        longitude: parseFloat(selectedLng),
                        is_active: true,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    const msg = data.errors
                        ? Object.values(data.errors).flat().join(' ')
                        : (data.message || 'Failed to create spot.');
                    throw new Error(msg);
                }

                successEl.textContent = `Spot ${spotNumber} created successfully!`;
                successEl.classList.remove('hidden');

                // Reset form
                document.getElementById('new-spot-number').value = '';
                if (clickMarker) { map.removeLayer(clickMarker); clickMarker = null; }
                document.getElementById('coords-display').classList.add('hidden');
                selectedLat = null;
                selectedLng = null;

                // Refresh map data
                const mapResponse = await fetch('/api/map/zones');
                const newMapData = await mapResponse.json();
                addZoneMarkers(newMapData.zones);

                setTimeout(() => successEl.classList.add('hidden'), 3000);
            } catch (err) {
                errorEl.textContent = err.message;
                errorEl.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Create Parking Spot';
            }
        }
    </script>
</x-layout>