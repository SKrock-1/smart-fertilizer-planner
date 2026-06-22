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

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_land_parcel_validation_rejects_invalid_values(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer']);

        $response = $this->actingAs($farmer)->post(route('parcels.store'), [
            'parcel_name' => 'Demo field',
            'area_acres' => 10001,
            'district' => 'Ludhiana',
            'state' => 'Punjab',
            'soil_type' => 'black',
            'latitude' => 91,
            'longitude' => 181,
        ]);

        $response->assertSessionHasErrors(['area_acres', 'soil_type', 'latitude', 'longitude']);
    }

    public function test_soil_test_validation_rejects_out_of_range_nutrients(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $parcel = LandParcel::factory()->create(['user_id' => $farmer->id]);

        $response = $this->actingAs($farmer)->post(route('parcels.soil-tests.store', $parcel), [
            'test_date' => now()->addDay()->format('Y-m-d'),
            'ph_level' => 15,
            'nitrogen_kg_ha' => 2001,
            'phosphorus_kg_ha' => 501,
            'potassium_kg_ha' => 2001,
            'organic_carbon_pct' => 21,
        ]);

        $response->assertSessionHasErrors([
            'test_date',
            'ph_level',
            'nitrogen_kg_ha',
            'phosphorus_kg_ha',
            'potassium_kg_ha',
            'organic_carbon_pct',
        ]);
    }

    public function test_farmer_cannot_view_another_farmers_plan(): void
    {
        [$plan, $owner, $otherFarmer] = $this->createPlanFixture();

        $this->actingAs($owner)->get(route('plans.show', $plan))->assertOk();
        $this->actingAs($otherFarmer)->get(route('plans.show', $plan))->assertForbidden();
    }

    public function test_admin_can_download_farmer_plan_pdf(): void
    {
        [$plan] = $this->createPlanFixture();
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('plans.pdf', $plan));

        $response->assertOk();
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    private function createPlanFixture(): array
    {
        $owner = User::factory()->create(['role' => 'farmer']);
        $otherFarmer = User::factory()->create(['role' => 'farmer']);
        $parcel = LandParcel::factory()->create([
            'user_id' => $owner->id,
            'parcel_name' => 'North field',
            'area_acres' => 2,
        ]);
        $soilTest = SoilTest::factory()->create([
            'parcel_id' => $parcel->id,
            'test_date' => now()->subWeek()->format('Y-m-d'),
            'ph_level' => 7.1,
            'nitrogen_kg_ha' => 100,
            'phosphorus_kg_ha' => 20,
            'potassium_kg_ha' => 50,
        ]);
        $crop = Crop::factory()->create([
            'name' => 'Wheat',
            'season' => 'rabi',
            'rdf_nitrogen' => 120,
            'rdf_phosphorus' => 60,
            'rdf_potassium' => 40,
        ]);
        $fertilizer = Fertilizer::create([
            'name' => 'Urea',
            'nitrogen_pct' => 46,
            'phosphorus_pct' => 0,
            'potassium_pct' => 0,
            'price_per_kg' => 6.50,
            'is_active' => true,
        ]);
        $plan = FertilizerPlan::create([
            'parcel_id' => $parcel->id,
            'soil_test_id' => $soilTest->id,
            'crop_id' => $crop->id,
            'season_year' => 'Rabi-2026',
            'total_cost_inr' => 650,
            'status' => 'finalized',
        ]);

        $plan->planItems()->create([
            'fertilizer_id' => $fertilizer->id,
            'quantity_kg' => 100,
            'application_stage' => 'Base Dose (At Sowing)',
            'application_method' => 'Broadcasting',
            'cost_inr' => 650,
        ]);

        return [$plan, $owner, $otherFarmer];
    }
}
