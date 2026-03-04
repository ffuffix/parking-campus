<?php

namespace Database\Seeders;

use App\Models\ParkingSpot;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@email.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name'     => 'john',
            'email'    => 'admin@email.com',
            'role'     => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name'     => 'bob',
            'email'    => 'user@email.com',
            'role'     => 'user',
            'password' => bcrypt('password'),
        ]);

        // ── Random users (simulate other drivers) ─────────────────────
        $randomUsers = User::factory(10)->create();

        // ── Zones with coordinates around Haarlem campus ──────────────
        $zonesData = [
            ['name' => 'Visitors', 'description' => 'Parking zone for visitors',            'max_spots' => 20, 'latitude' => 52.3812, 'longitude' => 4.6335],
            ['name' => 'Staff',    'description' => 'Parking zone for staff members',       'max_spots' => 30, 'latitude' => 52.3822, 'longitude' => 4.6360],
            ['name' => 'Electric', 'description' => 'Parking zone with EV charging stations','max_spots' => 10, 'latitude' => 52.3805, 'longitude' => 4.6385],
            ['name' => 'Students', 'description' => 'Parking zone for students',            'max_spots' => 50, 'latitude' => 52.3818, 'longitude' => 4.6405],
        ];

        // Grid layout constants
        // At ~52.38° lat: 1° lat ≈ 111 000 m, 1° lng ≈ 68 000 m
        $colsPerRow   = 5;
        $rowSpacingLat = 0.000055;  // ~6 m between rows  (north → south)
        $colSpacingLng = 0.000050;  // ~3.4 m between cols (west → east)
        $startOffsetLat =  0.00012; // start N of zone centre
        $startOffsetLng = -0.00012; // start W of zone centre

        foreach ($zonesData as $zoneData) {
            $zone   = Zone::create($zoneData);
            $prefix = strtoupper(substr($zone->name, 0, 1));

            for ($i = 1; $i <= $zone->max_spots; $i++) {
                $col = ($i - 1) % $colsPerRow;
                $row = (int)(($i - 1) / $colsPerRow);

                ParkingSpot::create([
                    'zone_id'     => $zone->id,
                    'spot_number' => sprintf('%s-%02d', $prefix, $i),
                    'type'        => $zone->name === 'Electric' ? 'electric' : 'regular',
                    'is_active'   => true,
                    'latitude'    => round($zone->latitude  + $startOffsetLat - ($row * $rowSpacingLat), 7),
                    'longitude'   => round($zone->longitude + $startOffsetLng + ($col * $colSpacingLng), 7),
                ]);
            }
        }

        // ── Vehicles for every user ───────────────────────────────────
        foreach (User::all() as $user) {
            Vehicle::factory(rand(1, 2))->create(['user_id' => $user->id]);
        }

        // ── Simulate reservations from other users ───────────────────
        // ~45 % of spots get a reservation; mix of checked_in and confirmed.
        $allSpots    = ParkingSpot::where('is_active', true)->get()->shuffle();
        $targetCount = (int)round($allSpots->count() * 0.45);
        $spotsToUse  = $allSpots->take($targetCount);

        // Build a pool: random users paired with one of their vehicles
        $vehiclePool = $randomUsers->flatMap(function ($user) {
            return $user->vehicles()->get()->map(fn($v) => ['user' => $user, 'vehicle' => $v]);
        })->values();

        if ($vehiclePool->isEmpty()) {
            return; // nothing to reserve
        }

        $poolIndex       = 0;
        $usedVehicleNow  = []; // vehicle_id → true  (prevent double-booking for checked_in)

        foreach ($spotsToUse as $spot) {
            // Pick next user+vehicle from pool (round-robin)
            $pair    = $vehiclePool[$poolIndex % $vehiclePool->count()];
            $poolIndex++;
            $user    = $pair['user'];
            $vehicle = $pair['vehicle'];

            // Decide status – prefer checked_in unless vehicle already in use
            $wantCheckedIn = rand(1, 10) <= 6;
            if ($wantCheckedIn && isset($usedVehicleNow[$vehicle->id])) {
                $wantCheckedIn = false; // downgrade to confirmed
            }

            if ($wantCheckedIn) {
                $usedVehicleNow[$vehicle->id] = true;
                $startTime    = now()->subHours(rand(1, 3));
                $endTime      = now()->addHours(rand(1, 4));
                $checkedInAt  = $startTime->copy()->addMinutes(rand(2, 15));

                Reservation::create([
                    'user_id'         => $user->id,
                    'vehicle_id'      => $vehicle->id,
                    'parking_spot_id' => $spot->id,
                    'start_time'      => $startTime,
                    'end_time'        => $endTime,
                    'status'          => 'checked_in',
                    'checked_in_at'   => $checkedInAt,
                ]);
            } else {
                // Confirmed: starts in the near future
                $startTime = now()->addMinutes(rand(10, 120));
                $endTime   = $startTime->copy()->addHours(rand(1, 4));

                Reservation::create([
                    'user_id'         => $user->id,
                    'vehicle_id'      => $vehicle->id,
                    'parking_spot_id' => $spot->id,
                    'start_time'      => $startTime,
                    'end_time'        => $endTime,
                    'status'          => 'confirmed',
                ]);
            }
        }
    }
}
