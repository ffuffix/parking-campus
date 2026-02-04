<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return redirect()->route('dashboard.admin');
        } else {
            return redirect()->route('dashboard.user');
        }
    }

    public function user()
    {
        $user = Auth::user();
        $vehicles = $user->vehicles()->with(['reservations' => function ($query) {
            $query->whereNotIn('status', ['cancelled', 'checked_out'])
                ->orderBy('start_time', 'desc');
        }])->get();

        $upcomingReservations = $user->reservations()
            ->whereNotIn('status', ['cancelled', 'checked_out'])
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->with(['vehicle', 'parkingSpot.zone'])
            ->limit(5)
            ->get();

        return view('dashboard-user.index', compact('user', 'vehicles', 'upcomingReservations'));
    }

    public function admin()
    {
        $stats = [
            'totalSpots' => \App\Models\ParkingSpot::count(),
            'availableSpots' => \App\Models\ParkingSpot::where('is_active', true)
                ->whereDoesntHave('reservations', function ($query) {
                    $query->where('status', 'checked_in')
                        ->orWhere(function ($q) {
                            $q->whereIn('status', ['pending', 'confirmed'])
                                ->where('start_time', '<=', now())
                                ->where('end_time', '>=', now());
                        });
                })
                ->count(),
            'activeReservations' => \App\Models\Reservation::whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->count(),
            'totalUsers' => \App\Models\User::count(),
        ];

        $zones = \App\Models\Zone::withCount(['parkingspots as active_spots_count' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        return view('dashboard-admin.index', compact('stats', 'zones'));
    }
}
