<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFertilizerPlanRequest;
use App\Models\Crop;
use App\Models\FertilizerPlan;
use App\Models\LandParcel;
use App\Services\FertilizerRecommendationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class FertilizerPlanController extends Controller
{
    public function index(Request $request)
    {
        $parcelIds = $request->user()->landParcels()->pluck('id');

        return view('plans.index', [
            'plans' => FertilizerPlan::with(['landParcel', 'crop'])
                ->whereIn('parcel_id', $parcelIds)
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        return view('plans.create', [
            'parcels' => $request->user()->landParcels()->with('latestSoilTest')->orderBy('parcel_name')->get(),
            'crops' => Crop::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreFertilizerPlanRequest $request, FertilizerRecommendationService $service)
    {
        try {
            $parcel = $request->user()->landParcels()->with('latestSoilTest')->findOrFail($request->validated('parcel_id'));
            $soilTest = $parcel->latestSoilTest;

            if (! $soilTest) {
                return redirect()->back()->withErrors(['parcel_id' => 'Add a soil test before generating a fertilizer plan.'])->withInput();
            }

            $crop = Crop::where('is_active', true)->findOrFail($request->validated('crop_id'));
            $computed = $service->compute($soilTest, $crop, (float) $parcel->area_acres);

            $plan = DB::transaction(function () use ($request, $parcel, $soilTest, $crop, $computed) {
                $plan = FertilizerPlan::create([
                    'parcel_id' => $parcel->id,
                    'soil_test_id' => $soilTest->id,
                    'crop_id' => $crop->id,
                    'season_year' => $request->validated('season_year'),
                    'total_cost_inr' => $computed['total_cost'],
                    'status' => 'finalized',
                    'notes' => $request->validated('notes'),
                ]);

                foreach ($computed['recommendations'] as $recommendation) {
                    if (! $recommendation['fertilizer_id']) {
                        throw new \RuntimeException($recommendation['fertilizer_name'].' is missing from fertilizer master data.');
                    }

                    $plan->planItems()->create([
                        'fertilizer_id' => $recommendation['fertilizer_id'],
                        'quantity_kg' => $recommendation['qty_kg'],
                        'application_stage' => $recommendation['stage'],
                        'application_method' => $recommendation['method'],
                        'cost_inr' => $recommendation['cost_inr'],
                    ]);
                }

                return $plan;
            });

            return redirect()->route('plans.show', $plan)->with('success', 'Fertilizer plan generated successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(FertilizerPlan $plan, FertilizerRecommendationService $service)
    {
        $this->authorize('view', $plan);

        $plan->load(['landParcel.user', 'soilTest', 'crop', 'planItems.fertilizer']);
        $computed = $service->compute($plan->soilTest, $plan->crop, (float) $plan->landParcel->area_acres);

        return view('plans.show', [
            'plan' => $plan,
            'computed' => $computed,
            'areaHa' => $computed['area_ha'],
            'soilSupply' => $computed['soil_supply'],
            'cropDemand' => $computed['crop_demand'],
            'deficits' => $computed['deficits'],
        ]);
    }

    public function destroy(FertilizerPlan $plan)
    {
        $this->authorize('delete', $plan);

        try {
            $plan->delete();

            return redirect()->route('plans.index')->with('success', 'Fertilizer plan deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function downloadPdf(FertilizerPlan $plan, FertilizerRecommendationService $service)
    {
        $this->authorize('view', $plan);

        $plan->load(['landParcel.user', 'soilTest', 'crop', 'planItems.fertilizer']);
        $computed = $service->compute($plan->soilTest, $plan->crop, (float) $plan->landParcel->area_acres);
        $filename = 'fertilizer-plan-'.$plan->id.'-'.((string) str($plan->landParcel->parcel_name)->slug()).'.pdf';

        return Pdf::loadView('plans.pdf', [
            'plan' => $plan,
            'computed' => $computed,
            'areaHa' => $computed['area_ha'],
            'soilSupply' => $computed['soil_supply'],
            'cropDemand' => $computed['crop_demand'],
            'deficits' => $computed['deficits'],
        ])
            ->setPaper('a4')
            ->download($filename);
    }
}
