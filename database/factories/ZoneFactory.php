<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Zone>
 */
class ZoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $zoneTypes = ['Visitors', 'Staff', 'Electric', 'Students', 'VIP', 'Handicap'];
        
        return [
            'name' => fake()->unique()->randomElement($zoneTypes),
            'description' => fake()->sentence(),
            'max_spots' => fake()->numberBetween(10, 50),
        ];
    }
}
