<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\WeatherAPI;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(WeatherAPI $weatherAPI)
    {
        $reservations = auth()->user()->reservations()->with(['vehicle', 'parkingSpot.zone'])->orderBy('start_time', 'desc')->get();

        // Fetch weather for upcoming reservations (next 7 days)
        $weatherWarnings = [];
        foreach ($reservations as $reservation) {
            if (in_array($reservation->status, ['cancelled', 'checked_out'])) continue;
            if ($reservation->start_time->isPast()) continue;
            if ($reservation->start_time->diffInDays(now()) > 7) continue;

            $forecast = $weatherAPI->get_forecast($reservation->start_time->format('Y-m-d'));
            if ($forecast && !empty($forecast['daily'])) {
                $daily = $forecast['daily'];
                $weatherWarnings[$reservation->id] = [
                    'icon' => $daily['icon'],
                    'description' => $daily['description'],
                    'precipitation_probability' => $daily['precipitation_probability'],
                    'bad_weather' => $daily['precipitation_probability'] > 50 || in_array($daily['weather_code'], [65, 66, 67, 71, 73, 75, 77, 80, 81, 82, 85, 86, 95, 96, 99]),
                ];
            }
        }

        return view('reservations.index', compact('reservations', 'weatherWarnings'));
    }

    /**
     * Display all reservations for admin.
     */
    public function adminIndex()
    {
        $reservations = Reservation::with(['user', 'vehicle', 'parkingSpot'])->orderBy('start_time', 'desc')->get();
        return view('dashboard-admin.reservations', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = auth()->user()->vehicles;
        return view('reservations.create', compact('vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'parking_spot_id' => 'required|exists:parking_spots,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Verify vehicle belongs to user
        $vehicle = \App\Models\Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized vehicle selection.');
        }

        // Verify parking spot is available for the selected time range
        $parkingSpot = \App\Models\ParkingSpot::findOrFail($validated['parking_spot_id']);
        $startTime = new \DateTime($validated['start_time']);
        $endTime = new \DateTime($validated['end_time']);

        if (!$parkingSpot->is_available($startTime, $endTime)) {
            return back()->withErrors([
                'parking_spot_id' => 'This parking spot is already reserved for the selected time range.',
            ])->withInput();
        }

        $request->user()->reservations()->create($validated);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation, WeatherAPI $weatherAPI)
    {
        if ($reservation->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        // Fetch weather if reservation is upcoming (within 7 days)
        $weather = null;
        if (!in_array($reservation->status, ['cancelled', 'checked_out'])
            && $reservation->start_time->isFuture()
            && $reservation->start_time->diffInDays(now()) <= 7
        ) {
            $weather = $weatherAPI->get_forecast($reservation->start_time->format('Y-m-d'));
        }

        return view('reservations.show', compact('reservation', 'weather'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }
        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')->with('success', 'Reservation updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservation cancelled.');
    }
}
