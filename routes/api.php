<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/map/zones', [MapController::class, 'zones'])->name('api.map.zones');

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
