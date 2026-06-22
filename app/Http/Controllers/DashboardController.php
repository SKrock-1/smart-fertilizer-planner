<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Fertilizer;
use App\Models\FertilizerPlan;
use App\Models\LandParcel;
use App\Models\SoilTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $parcelIds = $user->landParcels()->pluck('id');
        $plans = FertilizerPlan::whereIn('parcel_id', $parcelIds);
        $seasonTotals = (clone $plans)
            ->select('season_year', DB::raw('SUM(total_cost_inr) as total_cost'), DB::raw('MAX(created_at) as latest_plan_at'))
            ->groupBy('season_year')
            ->orderByDesc('latest_plan_at')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        $stats = [
            'land_parcels' => LandParcel::where('user_id', $user->id)->count(),
            'soil_tests' => SoilTest::whereIn('parcel_id', $parcelIds)->count(),
            'plans' => (clone $plans)->count(),
            'plan_count' => (clone $plans)->count(),
            'total_cost_this_season' => (clone $plans)->where('season_year', 'like', '%'.now()->year.'%')->sum('total_cost_inr'),
            'crops' => Crop::where('is_active', true)->count(),
            'fertilizers' => Fertilizer::where('is_active', true)->count(),
        ];

        $cropNutrients = Crop::where('is_active', true)
            ->orderBy('name')
            ->get(['name', 'rdf_nitrogen', 'rdf_phosphorus', 'rdf_potassium']);

        return view('dashboard', [
            'stats' => $stats,
            'recentPlans' => (clone $plans)->with(['landParcel', 'crop'])->latest()->take(5)->get(),
            'seasonLabels' => $seasonTotals->pluck('season_year')->values(),
            'seasonCosts' => $seasonTotals->pluck('total_cost')->map(fn ($value) => (float) $value)->values(),
            'cropNutrients' => $cropNutrients,
            'cropChartData' => [
                'labels' => $cropNutrients->pluck('name')->values(),
                'nitrogen' => $cropNutrients->pluck('rdf_nitrogen')->map(fn ($value) => (float) $value)->values(),
                'phosphorus' => $cropNutrients->pluck('rdf_phosphorus')->map(fn ($value) => (float) $value)->values(),
                'potassium' => $cropNutrients->pluck('rdf_potassium')->map(fn ($value) => (float) $value)->values(),
            ],
            'fertilizers' => Fertilizer::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
