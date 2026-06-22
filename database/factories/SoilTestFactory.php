<?php

namespace Database\Factories;

use App\Models\LandParcel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SoilTest>
 */
class SoilTestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'parcel_id' => LandParcel::factory(),
            'test_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'ph_level' => $this->faker->randomFloat(2, 5.5, 8.5),
            'nitrogen_kg_ha' => $this->faker->randomFloat(2, 50, 600),
            'phosphorus_kg_ha' => $this->faker->randomFloat(2, 5, 120),
            'potassium_kg_ha' => $this->faker->randomFloat(2, 50, 500),
            'organic_carbon_pct' => $this->faker->optional()->randomFloat(2, 0.2, 2.5),
            'zinc_ppm' => $this->faker->optional()->randomFloat(2, 0.2, 5),
            'sulfur_ppm' => $this->faker->optional()->randomFloat(2, 5, 80),
            'lab_report_path' => null,
        ];
    }
}
