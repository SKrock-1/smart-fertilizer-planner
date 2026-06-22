<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LandParcel>
 */
class LandParcelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'parcel_name' => $this->faker->words(2, true),
            'area_acres' => $this->faker->randomFloat(2, 0.5, 25),
            'district' => $this->faker->city(),
            'state' => $this->faker->state(),
            'latitude' => $this->faker->optional()->latitude(),
            'longitude' => $this->faker->optional()->longitude(),
            'soil_type' => $this->faker->randomElement(['loamy', 'sandy', 'clay', 'silt', 'black_cotton']),
            'notes' => null,
        ];
    }
}
