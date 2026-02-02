<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParkingSpot>
 */
class ParkingSpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $spot_counters = [];
        
        $zone = Zone::inRandomOrder()->first() ?? Zone::factory()->create();
        
        if (!isset($spot_counters[$zone->id])) {
            $spot_counters[$zone->id] = 1;
        }
        
        $spotNumber = sprintf('%s-%02d', strtoupper(substr($zone->name, 0, 1)), $spot_counters[$zone->id]++);
        
        return [
            'zone_id' => $zone->id,
            'spot_number' => $spotNumber,
            'type' => fake()->randomElement(['regular', 'electric', 'handicap']),
            'is_active' => fake()->boolean(90), // 90% chance of being active
        ];
    }
}
