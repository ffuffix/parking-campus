<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand' => fake()->company(),
            'model' => fake()->word(),
            'license_plate' => strtoupper(fake()->bothify('???-####')),
            'type' => fake()->randomElement(['regular', 'electric', 'motorcycle']),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
