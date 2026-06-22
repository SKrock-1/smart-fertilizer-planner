<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\LandParcel;
use Illuminate\Http\JsonResponse;

class LookupController extends Controller
{
    public function parcelDetails(LandParcel $parcel): JsonResponse
    {
        abort_unless($parcel->user_id === auth()->id() || auth()->user()?->role === 'admin', 403);

        $parcel->load('latestSoilTest');
        $soilTest = $parcel->latestSoilTest;

        return response()->json([
            'id' => $parcel->id,
            'name' => $parcel->parcel_name,
            'area_acres' => (float) $parcel->area_acres,
            'soil_type' => $parcel->soil_type,
            'district' => $parcel->district,
            'state' => $parcel->state,
            'last_soil_test_date' => $soilTest?->test_date?->format('Y-m-d'),
            'npk' => [
                'nitrogen' => $soilTest ? (float) $soilTest->nitrogen_kg_ha : null,
                'phosphorus' => $soilTest ? (float) $soilTest->phosphorus_kg_ha : null,
                'potassium' => $soilTest ? (float) $soilTest->potassium_kg_ha : null,
            ],
        ]);
    }

    public function cropDetails(Crop $crop): JsonResponse
    {
        abort_unless($crop->is_active, 404);

        return response()->json([
            'id' => $crop->id,
            'name' => $crop->name,
            'variety' => $crop->variety,
            'season' => $crop->season,
            'rdf_nitrogen' => (float) $crop->rdf_nitrogen,
            'rdf_phosphorus' => (float) $crop->rdf_phosphorus,
            'rdf_potassium' => (float) $crop->rdf_potassium,
            'duration_days' => $crop->duration_days,
        ]);
    }
}
