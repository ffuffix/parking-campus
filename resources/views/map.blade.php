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

        // Zone color mapping
        const zoneColors = {
            'Visitors': '#3b82f6',
            'Staff': '#10b981',
            'Electric': '#f59e0b',
            'Students': '#8b5cf6',
        };

        // Initialize map
        const map = L.map('map', {
            zoomControl: false,
        }).setView([mapData.center.lat, mapData.center.lng], mapData.zoom);

        // Add zoom control to bottom-left
        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        // Add tile layer
        L.tileLayer(mapData.tileUrl, {
            attribution: mapData.attribution,
            maxZoom: 19,
        }).addTo(map);

        // Store markers for refresh
        let markers = [];

        function addZoneMarkers(zones) {
            // Clear existing markers
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            zones.forEach(zone => {
                if (!zone.lat || !zone.lng) return;

                const color = zoneColors[zone.name] || '#6b7280';
                const available = zone.available_spots;
                const total = zone.max_spots;
                const percentage = zone.occupancy_percentage;

                // Create a circle marker for the zone
                const circle = L.circleMarker([zone.lat, zone.lng], {
                    radius: 25 + (total / 5),
                    fillColor: color,
                    color: available > 0 ? color : '#ef4444',
                    weight: 2,
                    opacity: 0.9,
                    fillOpacity: 0.3,
                }).addTo(map);

                // Popup with zone details
                circle.bindPopup(`
                    <div style="font-family: Inter, sans-serif; min-width: 180px;">
                        <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600;">${zone.name}</h3>
                        <p style="margin: 0 0 8px 0; color: #666; font-size: 13px;">${zone.description || ''}</p>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-size: 13px;">Available</span>
                            <span style="font-weight: 600; color: ${available > 0 ? '#16a34a' : '#dc2626'};">${available} / ${total}</span>
                        </div>
                        <div style="background: #e5e7eb; border-radius: 4px; height: 6px; overflow: hidden;">
                            <div style="background: ${percentage > 80 ? '#ef4444' : percentage > 50 ? '#f59e0b' : '#22c55e'}; width: ${percentage}%; height: 100%; border-radius: 4px;"></div>
                        </div>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #888;">${percentage}% occupied</p>
                    </div>
                `);

                // Add a label on the marker
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

                markers.push(circle, label);
            });
        }

        // Initial render
        addZoneMarkers(mapData.zones);

        // Refresh function
        async function refreshMap() {
            try {
                const response = await fetch('/api/map/zones');
                const data = await response.json();
                addZoneMarkers(data.zones);
            } catch (err) {
                console.error('Failed to refresh map data:', err);
            }
        }

        // Auto-refresh every 30 seconds
        setInterval(refreshMap, 30000);
    </script>
</x-layout>