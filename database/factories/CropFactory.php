<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crop>
 */
class CropFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Wheat', 'Rice', 'Maize']),
            'variety' => $this->faker->optional()->bothify('VAR-###'),
            'season' => $this->faker->randomElement(['kharif', 'rabi', 'zaid']),
            'rdf_nitrogen' => $this->faker->randomFloat(2, 20, 150),
            'rdf_phosphorus' => $this->faker->randomFloat(2, 20, 80),
            'rdf_potassium' => $this->faker->randomFloat(2, 20, 80),
            'duration_days' => $this->faker->numberBetween(80, 150),
            'description' => null,
            'is_active' => true,
        ];
    }
}
