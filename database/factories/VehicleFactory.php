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
        $carsData = app(\App\Services\CarAPI::class)->get_cars();
        $car = fake()->randomElement($carsData['cars']);

        return [
            'brand' => $car['car'],
            'model' => $car['car_model'],
            'vin' => $car['car_vin'] . '-' . fake()->unique()->numerify('####'), // Ensure uniqueness
            'color' => $car['car_color'],
            'year' => $car['car_model_year'],
            'license_plate' => app(\App\Services\LicensePlateGenerator::class)->generate(),
            'type' => fake()->randomElement(['regular', 'electric', 'motorcycle']),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
