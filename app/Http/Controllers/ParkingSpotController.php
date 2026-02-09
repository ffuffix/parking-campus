<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use Illuminate\Http\Request;

class ParkingSpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parkingSpots = ParkingSpot::all();
        return view('dashboard-admin.parking-spots', compact('parkingSpots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('parkingSpots.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'spot_number' => 'required|string|max:10',
            'type' => 'required|in:regular,electric,handicap',
            'is_active' => 'required|boolean',
        ]);

        $parkingSpot = ParkingSpot::create($validated);

        return redirect()->route('parkingSpots.show', $parkingSpot);
    }

    /**
     * Display the specified resource.
     */
    public function show(ParkingSpot $parkingSpot)
    {
        return view('parkingSpots.show', compact('parkingSpot'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkingSpot $parkingSpot)
    {
        return view('parkingSpots.edit', compact('parkingSpot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParkingSpot $parkingSpot)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'spot_number' => 'required|string|max:10',
            'type' => 'required|in:regular,electric,handicap',
            'is_active' => 'required|boolean',
        ]);

        $parkingSpot->update($validated);

        return redirect()->route('parkingSpots.show', $parkingSpot);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingSpot $parkingSpot)
    {
        $parkingSpot->delete();

        return redirect()->route('parkingSpots.index');
    }
}
