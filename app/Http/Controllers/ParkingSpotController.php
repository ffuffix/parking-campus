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
        return response()->json($parkingSpots); // currently returns raw JSON, change this to a view
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
            'spot_number' => 'required|string|max:10|unique:parking_spots,spot_number',
            'level' => 'required|integer',
            'is_reserved' => 'required|boolean',
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
            'spot_number' => 'required|string|max:10|unique:parking_spots,spot_number,' . $parkingSpot->id,
            'level' => 'required|integer',
            'is_reserved' => 'required|boolean',
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
