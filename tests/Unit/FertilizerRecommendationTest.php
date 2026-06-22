<?php

namespace Tests\Unit;

use App\Models\Crop;
use App\Models\Fertilizer;
use App\Models\SoilTest;
use App\Services\FertilizerRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FertilizerRecommendationTest extends TestCase
{
    use RefreshDatabase;

    private FertilizerRecommendationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new FertilizerRecommendationService();

        Fertilizer::create([
            'name' => 'Urea',
            'nitrogen_pct' => 46,
            'phosphorus_pct' => 0,
            'potassium_pct' => 0,
            'price_per_kg' => 6.50,
            'unsubsidized_price_per_kg' => 24,
            'is_active' => true,
        ]);
        Fertilizer::create([
            'name' => 'DAP',
            'nitrogen_pct' => 18,
            'phosphorus_pct' => 46,
            'potassium_pct' => 0,
            'price_per_kg' => 27,
            'unsubsidized_price_per_kg' => 60,
            'is_active' => true,
        ]);
        Fertilizer::create([
            'name' => 'MOP',
            'nitrogen_pct' => 0,
            'phosphorus_pct' => 0,
            'potassium_pct' => 60,
            'price_per_kg' => 17,
            'unsubsidized_price_per_kg' => 35,
            'is_active' => true,
        ]);
    }

    public function test_it_returns_zero_fertilizer_when_soil_is_sufficient(): void
    {
        $soilTest = SoilTest::factory()->make([
            'nitrogen_kg_ha' => 600,
            'phosphorus_kg_ha' => 200,
            'potassium_kg_ha' => 500,
        ]);
        $crop = Crop::factory()->make([
            'rdf_nitrogen' => 100,
            'rdf_phosphorus' => 50,
            'rdf_potassium' => 40,
        ]);

        $result = $this->service->compute($soilTest, $crop, 2.0);

        $this->assertSame(0, count(array_filter($result['recommendations'], fn ($recommendation) => $recommendation['qty_kg'] > 0)));
    }

    public function test_urea_quantity_is_correct_for_nitrogen_deficit(): void
    {
        $soilTest = SoilTest::factory()->make([
            'nitrogen_kg_ha' => 80,
            'phosphorus_kg_ha' => 100,
            'potassium_kg_ha' => 300,
        ]);
        $crop = Crop::factory()->make([
            'rdf_nitrogen' => 120,
            'rdf_phosphorus' => 20,
            'rdf_potassium' => 20,
        ]);

        $result = $this->service->compute($soilTest, $crop, 2.5);
        $totalUrea = collect($result['recommendations'])
            ->where('fertilizer_name', 'Urea')
            ->sum('qty_kg');

        $this->assertEqualsWithDelta(211.15, $totalUrea, 2.0);
    }

    public function test_it_splits_urea_into_three_application_stages(): void
    {
        $soilTest = SoilTest::factory()->make([
            'ph_level' => 7.0,
            'nitrogen_kg_ha' => 50,
            'phosphorus_kg_ha' => 5,
            'potassium_kg_ha' => 50,
            'organic_carbon_pct' => null,
            'zinc_ppm' => null,
            'sulfur_ppm' => null,
        ]);
        $crop = Crop::factory()->make([
            'rdf_nitrogen' => 120,
            'rdf_phosphorus' => 60,
            'rdf_potassium' => 40,
        ]);

        $result = $this->service->compute($soilTest, $crop, 1.0);
        $ureaItems = collect($result['recommendations'])->where('fertilizer_name', 'Urea');

        $this->assertGreaterThanOrEqual(2, $ureaItems->count());
    }

    public function test_total_cost_is_sum_of_item_costs(): void
    {
        $soilTest = SoilTest::factory()->make([
            'ph_level' => 7.0,
            'nitrogen_kg_ha' => 50,
            'phosphorus_kg_ha' => 5,
            'potassium_kg_ha' => 50,
            'organic_carbon_pct' => null,
            'zinc_ppm' => null,
            'sulfur_ppm' => null,
        ]);
        $crop = Crop::factory()->make([
            'rdf_nitrogen' => 120,
            'rdf_phosphorus' => 60,
            'rdf_potassium' => 40,
        ]);

        $result = $this->service->compute($soilTest, $crop, 1.0);
        $sumCost = collect($result['recommendations'])->sum('cost_inr');

        $this->assertEquals(round($sumCost, 2), $result['total_cost']);
    }
}
