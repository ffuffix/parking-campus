<?php

namespace App\Services;

use App\Models\Zone;
use Illuminate\Support\Facades\Cache;

class MapAPI
{
    /**
     * Default map center (Haarlem, Netherlands)
     */
    protected float $centerLat = 52.381;
    protected float $centerLng = 4.636;
    protected int $defaultZoom = 16;

    /**
     * Get map configuration and zone data for rendering.
     * Cached for 5 minutes.
     */
    public function get_map(): array
    {
        return Cache::remember('map_data', 5 * 60, function () {
            $zones = Zone::with('parkingspots')->get();

            return [
                'center' => [
                    'lat' => $this->centerLat,
                    'lng' => $this->centerLng,
                ],
                'zoom' => $this->defaultZoom,
                'tileUrl' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                'zones' => $zones->map(function (Zone $zone) {
                    return [
                        'id' => $zone->id,
                        'name' => $zone->name,
                        'description' => $zone->description,
                        'lat' => $zone->latitude,
                        'lng' => $zone->longitude,
                        'max_spots' => $zone->max_spots,
                        'active_spots' => $zone->get_active_spots_count_attribute(),
                        'occupied_spots' => $zone->get_occupied_spots_count_attribute(),
                        'available_spots' => $zone->get_available_spots_count_attribute(),
                        'occupancy_percentage' => $zone->get_occupancy_percentage_attribute(),
                    ];
                })->toArray(),
            ];
        });
    }
}