<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = auth()->user()->reservations()->with(['vehicle', 'parkingSpot'])->orderBy('start_time', 'desc')->get();
        return view('reservations.index', compact('reservations'));
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
        $parkingSpots = \App\Models\ParkingSpot::where('is_active', true)->get();
        return view('reservations.create', compact('vehicles', 'parkingSpots'));
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

        $request->user()->reservations()->create($validated);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        return view('reservations.show', compact('reservation'));
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
