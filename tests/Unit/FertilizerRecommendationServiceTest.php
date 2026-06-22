<?php

namespace Tests\Unit;

use App\Models\Crop;
use App\Models\Fertilizer;
use App\Models\LandParcel;
use App\Models\SoilTest;
use App\Models\User;
use App\Services\FertilizerRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FertilizerRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_computes_fertilizer_recommendations_from_soil_deficits(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer']);
        $parcel = LandParcel::create([
            'user_id' => $farmer->id,
            'parcel_name' => 'North field',
            'area_acres' => 2,
            'district' => 'Ludhiana',
            'state' => 'Punjab',
        ]);
        $soilTest = SoilTest::create([
            'parcel_id' => $parcel->id,
            'test_date' => '2026-05-01',
            'ph_level' => 7.1,
            'nitrogen_kg_ha' => 100,
            'phosphorus_kg_ha' => 20,
            'potassium_kg_ha' => 50,
        ]);
        $crop = Crop::create([
            'name' => 'Wheat',
            'variety' => 'HD-2967',
            'season' => 'rabi',
            'rdf_nitrogen' => 120,
            'rdf_phosphorus' => 60,
            'rdf_potassium' => 40,
            'is_active' => true,
        ]);

        Fertilizer::create(['name' => 'Urea', 'nitrogen_pct' => 46, 'price_per_kg' => 6.50]);
        Fertilizer::create(['name' => 'DAP', 'nitrogen_pct' => 18, 'phosphorus_pct' => 46, 'price_per_kg' => 27.00]);
        Fertilizer::create(['name' => 'MOP', 'potassium_pct' => 60, 'price_per_kg' => 17.00]);

        $result = app(FertilizerRecommendationService::class)->compute($soilTest, $crop, 2);

        $this->assertSame(0.8094, $result['area_ha']);
        $this->assertSame(['N' => 97.13, 'P' => 48.56, 'K' => 32.38], $result['crop_demand']);
        $this->assertSame(['N' => 24.28, 'P' => 4.05, 'K' => 4.05], $result['soil_supply']);
        $this->assertSame(['N' => 72.85, 'P' => 44.52, 'K' => 28.33], $result['deficits']);
        $this->assertSame(4198.80, $result['total_cost']);
        $this->assertCount(5, $result['recommendations']);
        
        $recommendations = collect($result['recommendations']);
        
        $dap = $recommendations->firstWhere('fertilizer_name', 'DAP');
        $this->assertNotNull($dap);
        $this->assertEquals(96.78, $dap['qty_kg']);
        $this->assertSame('Base Dose (At Sowing)', $dap['stage']);

        $mop = $recommendations->firstWhere('fertilizer_name', 'MOP');
        $this->assertNotNull($mop);
        $this->assertEquals(47.22, $mop['qty_kg']);
        $this->assertSame('Base Dose (At Sowing)', $mop['stage']);

        $urea = $recommendations->where('fertilizer_name', 'Urea');
        $this->assertCount(3, $urea);
        
        $this->assertTrue($urea->contains('stage', 'Base Dose (At Sowing)'));
        $this->assertTrue($urea->contains('stage', 'Top Dressing 1 (30 days after sowing)'));
        $this->assertTrue($urea->contains('stage', 'Top Dressing 2 (60 days after sowing)'));
    }
}
