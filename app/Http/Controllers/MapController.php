<?php

namespace App\Http\Controllers;

use App\Services\MapAPI;

class MapController extends Controller
{
    public function index(MapAPI $mapAPI)
    {
        $mapData = $mapAPI->get_map();

        return view('map', compact('mapData'));
    }

    /**
     * API endpoint returning zone data as JSON (for AJAX refreshes).
     */
    public function zones(MapAPI $mapAPI)
    {
        return response()->json($mapAPI->get_map());
    }
}
