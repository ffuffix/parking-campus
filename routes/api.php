<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/map/zones', [MapController::class, 'zones'])->name('api.map.zones');

// Individual parking spots with coordinates and live status
Route::get('/map/spots', function () {
    $spots = \App\Models\ParkingSpot::with('zone')
        ->where('is_active', true)
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get()
        ->map(function ($spot) {
            $currentRes = $spot->current_reservation();
            return [
                'id'          => $spot->id,
                'spot_number' => $spot->spot_number,
                'type'        => $spot->type,
                'latitude'    => $spot->latitude,
                'longitude'   => $spot->longitude,
                'zone_name'   => $spot->zone->name ?? 'Unknown',
                'zone_id'     => $spot->zone_id,
                'is_occupied' => $spot->is_occupied(),
                'is_reserved' => (bool)$currentRes,
            ];
        });

    return response()->json(['spots' => $spots]);
})->name('api.map.spots');

Route::get('/weather/{date}', function (string $date) {
    // Validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return response()->json(['error' => 'Invalid date format. Use YYYY-MM-DD.'], 422);
    }

    $weatherAPI = new \App\Services\WeatherAPI();
    $forecast = $weatherAPI->get_forecast($date);

    if (!$forecast) {
        return response()->json(['error' => 'Could not fetch weather data.'], 503);
    }

    return response()->json($forecast);
})->name('api.weather');

Route::get('/available-spots', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
    ]);

    $start = new \DateTime($request->start_time);
    $end = new \DateTime($request->end_time);

    $spots = \App\Models\ParkingSpot::where('is_active', true)
        ->with('zone')
        ->get()
        ->map(function ($spot) use ($start, $end) {
            return [
                'id' => $spot->id,
                'spot_number' => $spot->spot_number,
                'type' => $spot->type,
                'zone_name' => $spot->zone->name ?? 'Zone',
                'available' => $spot->is_available($start, $end),
            ];
        });

    return response()->json($spots);
})->name('api.available-spots');

// Admin: Create parking spot from map
Route::post('/admin/parking-spots', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'zone_id' => 'required|exists:zones,id',
        'spot_number' => 'required|string|max:20',
        'type' => 'required|in:regular,electric,handicap',
        'is_active' => 'boolean',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    $validated['is_active'] = $validated['is_active'] ?? true;

    // Check uniqueness within zone
    $exists = \App\Models\ParkingSpot::where('zone_id', $validated['zone_id'])
        ->where('spot_number', $validated['spot_number'])
        ->exists();

    if ($exists) {
        return response()->json([
            'message' => 'A spot with this number already exists in this zone.',
            'errors' => ['spot_number' => ['Spot number already taken in this zone.']]
        ], 422);
    }

    $spot = \App\Models\ParkingSpot::create($validated);

    // Clear map cache so zone counts refresh
    \Illuminate\Support\Facades\Cache::forget('map_data');

    return response()->json($spot->load('zone'), 201);
})->name('api.admin.parking-spots.store');

// Admin: Get all parking spots with coordinates
Route::get('/admin/parking-spots', function () {
    $spots = \App\Models\ParkingSpot::with('zone')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get()
        ->map(function ($spot) {
            return [
                'id' => $spot->id,
                'spot_number' => $spot->spot_number,
                'type' => $spot->type,
                'is_active' => $spot->is_active,
                'latitude' => $spot->latitude,
                'longitude' => $spot->longitude,
                'zone_name' => $spot->zone->name ?? 'Unknown',
                'zone_id' => $spot->zone_id,
                'is_occupied' => $spot->is_occupied(),
            ];
        });

    return response()->json($spots);
})->name('api.admin.parking-spots.index');
