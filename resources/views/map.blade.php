<x-layout>
    <x-slot name="title">Campus Parking Map</x-slot>
    <x-slot name="fullWidth">true</x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <div class="relative">
        <!-- Map container -->
        <div id="map" class="w-full" style="height: calc(100vh - 64px - 73px);"></div>

        <!-- Legend overlay -->
        <div class="absolute top-4 right-4 z-[1000] bg-black/80 backdrop-blur-xl border border-zinc-800 rounded-xl p-4 min-w-[220px]">
            <h3 class="text-white font-semibold text-sm mb-3">Parking Zones</h3>
            <div id="zone-legend" class="space-y-2">
                @foreach($mapData['zones'] as $zone)
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full inline-block" 
                                style="background-color: {{ match($zone['name']) {
                                    'Visitors' => '#3b82f6',
                                    'Staff' => '#10b981',
                                    'Electric' => '#f59e0b',
                                    'Students' => '#8b5cf6',
                                    default => '#6b7280'
                                } }};"></span>
                            <span class="text-zinc-300 text-sm">{{ $zone['name'] }}</span>
                        </div>
                        <span class="text-xs font-mono {{ $zone['available_spots'] > 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $zone['available_spots'] }}/{{ $zone['max_spots'] }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 pt-3 border-t border-zinc-700">
                <button onclick="refreshMap()" class="w-full text-xs bg-zinc-800 hover:bg-zinc-700 text-white py-1.5 px-3 rounded-lg transition-colors">
                    ↻ Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        const mapData = @json($mapData);

        // Zone colour mapping
        const zoneColors = {
            'Visitors': '#3b82f6',
            'Staff':    '#10b981',
            'Electric': '#f59e0b',
            'Students': '#8b5cf6',
        };

        // Half-dimensions of a parking-spot rectangle in degrees
        const SPOT_HALF_LAT = 0.000022;
        const SPOT_HALF_LNG = 0.000015;

        // Initialize map
        const map = L.map('map', {
            zoomControl: false,
        }).setView([mapData.center.lat, mapData.center.lng], mapData.zoom);

        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer(mapData.tileUrl, {
            attribution: mapData.attribution,
            maxZoom: 19,
        }).addTo(map);

        // Store layers for refresh
        let zoneMarkers = [];
        let spotLayers  = [];

        // ── Zone circles (area overview) ─────────────────────────────
        function addZoneMarkers(zones) {
            zoneMarkers.forEach(m => map.removeLayer(m));
            zoneMarkers = [];

            zones.forEach(zone => {
                if (!zone.lat || !zone.lng) return;

                const color     = zoneColors[zone.name] || '#6b7280';
                const available = zone.available_spots;
                const total     = zone.max_spots;
                const pct       = zone.occupancy_percentage;

                const circle = L.circleMarker([zone.lat, zone.lng], {
                    radius:      25 + (total / 5),
                    fillColor:   color,
                    color:       available > 0 ? color : '#ef4444',
                    weight:      2,
                    opacity:     0.6,
                    fillOpacity: 0.12,
                }).addTo(map);

                circle.bindPopup(`
                    <div style="font-family: Inter, sans-serif; min-width: 180px;">
                        <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600;">${zone.name}</h3>
                        <p style="margin: 0 0 8px 0; color: #666; font-size: 13px;">${zone.description || ''}</p>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-size: 13px;">Available</span>
                            <span style="font-weight: 600; color: ${available > 0 ? '#16a34a' : '#dc2626'};">${available} / ${total}</span>
                        </div>
                        <div style="background: #e5e7eb; border-radius: 4px; height: 6px; overflow: hidden;">
                            <div style="background: ${pct > 80 ? '#ef4444' : pct > 50 ? '#f59e0b' : '#22c55e'}; width: ${pct}%; height: 100%; border-radius: 4px;"></div>
                        </div>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #888;">${pct}% occupied</p>
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
                        ">${zone.name}<br><span style="font-size: 10px; font-weight: 400;">${available} spots</span></div>`,
                        iconSize: [0, 0],
                        iconAnchor: [0, 0],
                    })
                }).addTo(map);

                zoneMarkers.push(circle, label);
            });
        }

        // ── Individual spot rectangles ────────────────────────────────
        async function loadSpotRectangles() {
            try {
                const res  = await fetch('/api/map/spots');
                const data = await res.json();

                spotLayers.forEach(l => map.removeLayer(l));
                spotLayers = [];

                data.spots.forEach(spot => {
                    const color = zoneColors[spot.zone_name] || '#6b7280';

                    let fillColor, borderColor;
                    if (spot.is_occupied) {
                        fillColor   = '#ef4444';
                        borderColor = '#b91c1c';
                    } else if (spot.is_reserved) {
                        fillColor   = '#f59e0b';
                        borderColor = '#b45309';
                    } else {
                        fillColor   = color;
                        borderColor = color;
                    }

                    const bounds = [
                        [spot.latitude  - SPOT_HALF_LAT, spot.longitude - SPOT_HALF_LNG],
                        [spot.latitude  + SPOT_HALF_LAT, spot.longitude + SPOT_HALF_LNG],
                    ];

                    const rect = L.rectangle(bounds, {
                        color:       borderColor,
                        fillColor:   fillColor,
                        weight:      1,
                        opacity:     0.9,
                        fillOpacity: spot.is_occupied ? 0.75 : 0.55,
                    }).addTo(map);

                    const statusLabel = spot.is_occupied
                        ? '🔴 Occupied'
                        : spot.is_reserved
                            ? '🟠 Reserved'
                            : '🟢 Available';

                    rect.bindTooltip(`
                        <strong>${spot.zone_name} – ${spot.spot_number}</strong><br>
                        Type: ${spot.type}<br>
                        ${statusLabel}
                    `, { sticky: true });

                    spotLayers.push(rect);
                });
            } catch (err) {
                console.error('Failed to load spot rectangles:', err);
            }
        }

        // Initial render
        addZoneMarkers(mapData.zones);
        loadSpotRectangles();

        // Refresh function
        async function refreshMap() {
            try {
                const response = await fetch('/api/map/zones');
                const data = await response.json();
                addZoneMarkers(data.zones);
                loadSpotRectangles();
            } catch (err) {
                console.error('Failed to refresh map data:', err);
            }
        }

        // Auto-refresh every 30 seconds
        setInterval(refreshMap, 30000);
    </script>
</x-layout>