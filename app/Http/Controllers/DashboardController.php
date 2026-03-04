<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MapAPI;

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
        $vehicles = $user->vehicles()->with([
            'reservations' => function ($query) {
                $query->whereNotIn('status', ['cancelled', 'checked_out'])
                    ->orderBy('start_time', 'desc');
            }
        ])->get();

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

        // Per-zone occupancy stats
        $zones = \App\Models\Zone::withCount([
            'parkingspots as active_spots_count' => function ($query) {
                $query->where('is_active', true);
            }
        ])->get();

        $zoneStats = $zones->map(function ($zone) {
            $occupied = $zone->get_occupied_spots_count_attribute();
            $active = $zone->active_spots_count;
            $available = max(0, $active - $occupied);
            $occupancy = $active > 0 ? round(($occupied / $active) * 100, 1) : 0;

            return [
                'name' => $zone->name,
                'active' => $active,
                'occupied' => $occupied,
                'available' => $available,
                'occupancy' => $occupancy,
            ];
        });

        // Time-slot analysis: reservations per hour (last 7 days)
        $timeSlots = \App\Models\Reservation::where('start_time', '>=', now()->subDays(7))
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->groupBy(function ($reservation) {
                return \Carbon\Carbon::parse($reservation->start_time)->format('H');
            })
            ->map(function ($group, $hour) {
                return [
                    'hour' => $hour . ':00',
                    'count' => $group->count(),
                ];
            })
            ->sortKeys();

        // Fill in missing hours
        $allTimeSlots = collect();
        for ($h = 6; $h <= 22; $h++) {
            $key = str_pad($h, 2, '0', STR_PAD_LEFT);
            $allTimeSlots->push([
                'hour' => $key . ':00',
                'count' => $timeSlots->has($key) ? $timeSlots[$key]['count'] : 0,
            ]);
        }
        $maxSlotCount = $allTimeSlots->max('count') ?: 1;

        // Reservations by day of week (last 30 days)
        $dayOfWeekStats = \App\Models\Reservation::where('start_time', '>=', now()->subDays(30))
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->groupBy(function ($reservation) {
                return \Carbon\Carbon::parse($reservation->start_time)->format('l');
            })
            ->map->count();

        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $dayStats = collect($weekDays)->map(fn($day) => [
            'day' => substr($day, 0, 3),
            'count' => $dayOfWeekStats->get($day, 0),
        ]);
        $maxDayCount = $dayStats->max('count') ?: 1;

        // Recent reservations
        $recentReservations = \App\Models\Reservation::with(['user', 'vehicle', 'parkingSpot.zone'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard-admin.index', compact(
            'stats',
            'zones',
            'zoneStats',
            'allTimeSlots',
            'maxSlotCount',
            'dayStats',
            'maxDayCount',
            'recentReservations'
        ));
    }

    public function adminMap(MapAPI $mapAPI)
    {
        $mapData = $mapAPI->get_map();
        $zones = \App\Models\Zone::all();

        return view('dashboard-admin.map', compact('mapData', 'zones'));
    }
}
