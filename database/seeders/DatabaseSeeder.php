<?php

namespace Database\Seeders;

use App\Models\ParkingSpot;
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
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'john',
            'email' => 'admin@email.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'bob',
            'email' => 'user@email.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // Create zones (coordinates around Haarlem campus)
        $zones = [
            ['name' => 'Visitors', 'description' => 'Parking zone for visitors', 'max_spots' => 20, 'latitude' => 52.3812, 'longitude' => 4.6335],
            ['name' => 'Staff', 'description' => 'Parking zone for staff members', 'max_spots' => 30, 'latitude' => 52.3822, 'longitude' => 4.6360],
            ['name' => 'Electric', 'description' => 'Parking zone with EV charging stations', 'max_spots' => 10, 'latitude' => 52.3805, 'longitude' => 4.6385],
            ['name' => 'Students', 'description' => 'Parking zone for students', 'max_spots' => 50, 'latitude' => 52.3818, 'longitude' => 4.6405],
        ];

        foreach ($zones as $zoneData) {
            $zone = Zone::create($zoneData);

            // Create parking spots for each zone
            $prefix = strtoupper(substr($zone->name, 0, 1));
            for ($i = 1; $i <= $zone->max_spots; $i++) {
                ParkingSpot::create([
                    'zone_id' => $zone->id,
                    'spot_number' => sprintf('%s-%02d', $prefix, $i),
                    'type' => $zone->name === 'Electric' ? 'electric' : 'regular',
                    'is_active' => true,
                ]);
            }
        }

        // Create vehicles for each user
        $users = User::all();
        foreach ($users as $user) {
            Vehicle::factory(rand(1, 2))->create(['user_id' => $user->id]);
        }
    }
}
