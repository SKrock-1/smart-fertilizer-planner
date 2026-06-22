<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\Fertilizer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $crops = [
            ['name' => 'Wheat', 'variety' => 'HD-2967', 'season' => 'rabi', 'rdf_nitrogen' => 120, 'rdf_phosphorus' => 60, 'rdf_potassium' => 40, 'duration_days' => 120],
            ['name' => 'Rice', 'variety' => 'PR-126', 'season' => 'kharif', 'rdf_nitrogen' => 100, 'rdf_phosphorus' => 50, 'rdf_potassium' => 50, 'duration_days' => 130],
            ['name' => 'Maize', 'variety' => 'Pioneer', 'season' => 'kharif', 'rdf_nitrogen' => 120, 'rdf_phosphorus' => 60, 'rdf_potassium' => 40, 'duration_days' => 90],
            ['name' => 'Mustard', 'variety' => 'Varuna', 'season' => 'rabi', 'rdf_nitrogen' => 80, 'rdf_phosphorus' => 40, 'rdf_potassium' => 40, 'duration_days' => 120],
            ['name' => 'Sugarcane', 'variety' => 'CoJ-89', 'season' => 'kharif', 'rdf_nitrogen' => 250, 'rdf_phosphorus' => 100, 'rdf_potassium' => 100, 'duration_days' => 330],
            ['name' => 'Cotton', 'variety' => 'H-1226', 'season' => 'kharif', 'rdf_nitrogen' => 150, 'rdf_phosphorus' => 60, 'rdf_potassium' => 60, 'duration_days' => 180],
            ['name' => 'Soybean', 'variety' => 'JS-335', 'season' => 'kharif', 'rdf_nitrogen' => 30, 'rdf_phosphorus' => 60, 'rdf_potassium' => 40, 'duration_days' => 100],
            ['name' => 'Chickpea', 'variety' => 'GPF-2', 'season' => 'rabi', 'rdf_nitrogen' => 20, 'rdf_phosphorus' => 50, 'rdf_potassium' => 30, 'duration_days' => 110],
        ];

        foreach ($crops as $crop) {
            Crop::updateOrCreate(
                ['name' => $crop['name'], 'variety' => $crop['variety']],
                $crop + ['is_active' => true]
            );
        }

        $fertilizers = [
            ['name' => 'Urea', 'nitrogen_pct' => 46.0, 'phosphorus_pct' => 0, 'potassium_pct' => 0, 'price_per_kg' => 6.50, 'unsubsidized_price_per_kg' => 38.50, 'type' => 'straight'],
            ['name' => 'DAP', 'nitrogen_pct' => 18.0, 'phosphorus_pct' => 46.0, 'potassium_pct' => 0, 'price_per_kg' => 27.00, 'unsubsidized_price_per_kg' => 64.00, 'type' => 'straight'],
            ['name' => 'MOP', 'nitrogen_pct' => 0, 'phosphorus_pct' => 0, 'potassium_pct' => 60.0, 'price_per_kg' => 17.00, 'unsubsidized_price_per_kg' => 34.00, 'type' => 'straight'],
            ['name' => 'SSP', 'nitrogen_pct' => 0, 'phosphorus_pct' => 16.0, 'potassium_pct' => 0, 'price_per_kg' => 8.00, 'unsubsidized_price_per_kg' => 14.50, 'type' => 'straight'],
            ['name' => 'NPK 10-26-26', 'nitrogen_pct' => 10.0, 'phosphorus_pct' => 26.0, 'potassium_pct' => 26.0, 'price_per_kg' => 22.00, 'unsubsidized_price_per_kg' => 36.00, 'type' => 'compound'],
            ['name' => 'NPK 12-32-16', 'nitrogen_pct' => 12.0, 'phosphorus_pct' => 32.0, 'potassium_pct' => 16.0, 'price_per_kg' => 24.00, 'unsubsidized_price_per_kg' => 40.00, 'type' => 'compound'],
            ['name' => 'Ammonium Sulphate', 'nitrogen_pct' => 20.0, 'phosphorus_pct' => 0, 'potassium_pct' => 0, 'price_per_kg' => 12.00, 'unsubsidized_price_per_kg' => 22.00, 'type' => 'straight'],
            ['name' => 'Zinc Sulphate', 'nitrogen_pct' => 0, 'phosphorus_pct' => 0, 'potassium_pct' => 0, 'price_per_kg' => 45.00, 'unsubsidized_price_per_kg' => 75.00, 'type' => 'micronutrient'],
        ];

        foreach ($fertilizers as $fertilizer) {
            Fertilizer::updateOrCreate(
                ['name' => $fertilizer['name']],
                $fertilizer + ['is_active' => true]
            );
        }

        User::updateOrCreate(
            ['email' => 'admin@fertilizer.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'farmer@test.com'],
            [
                'name' => 'Sample Farmer',
                'password' => Hash::make('farmer123'),
                'role' => 'farmer',
            ]
        );
    }
}
