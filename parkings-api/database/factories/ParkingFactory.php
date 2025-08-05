<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Parking>
 */
class ParkingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company,
            'direccion' => $this->faker->address,
            'latitud' => $this->faker->latitude(-90, 90),
            'longitud' => $this->faker->longitude(-180, 180),
        ];
    }
}