<?php

namespace Tests\Feature;

use App\Models\Crop;
use App\Models\Fertilizer;
use App\Models\FertilizerPlan;
use App\Models\LandParcel;
use App\Models\SoilTest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FertilizerPlanFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_farmer_can_generate_a_fertilizer_plan_from_latest_soil_test(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $parcel = LandParcel::create([
            'user_id' => $farmer->id,
            'parcel_name' => 'North field',
            'area_acres' => 2,
            'district' => 'Ludhiana',
            'state' => 'Punjab',
        ]);
        SoilTest::create([
            'parcel_id' => $parcel->id,
            'test_date' => '2026-05-01',
            'ph_level' => 7.1,
            'nitrogen_kg_ha' => 100,
            'phosphorus_kg_ha' => 20,
            'potassium_kg_ha' => 50,
        ]);
        $crop = Crop::create([
            'name' => 'Wheat',
            'season' => 'rabi',
            'rdf_nitrogen' => 120,
            'rdf_phosphorus' => 60,
            'rdf_potassium' => 40,
            'is_active' => true,
        ]);
        Fertilizer::create(['name' => 'Urea', 'nitrogen_pct' => 46, 'price_per_kg' => 6.50]);
        Fertilizer::create(['name' => 'DAP', 'nitrogen_pct' => 18, 'phosphorus_pct' => 46, 'price_per_kg' => 27.00]);
        Fertilizer::create(['name' => 'MOP', 'potassium_pct' => 60, 'price_per_kg' => 17.00]);

        $response = $this->actingAs($farmer)->post(route('plans.store'), [
            'parcel_id' => $parcel->id,
            'crop_id' => $crop->id,
            'season_year' => 'Rabi-2026',
        ]);

        $plan = FertilizerPlan::with('planItems')->first();

        $response->assertRedirect(route('plans.show', $plan));
        $this->assertSame('finalized', $plan->status);
        $this->assertSame('4198.80', $plan->total_cost_inr);
        $this->assertCount(5, $plan->planItems);
    }
}
