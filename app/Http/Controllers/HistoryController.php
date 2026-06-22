<?php

namespace App\Http\Controllers;

use App\Models\FertilizerPlan;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $parcelIds = $request->user()->landParcels()->pluck('id');
        $plans = FertilizerPlan::with(['landParcel', 'crop'])
            ->whereIn('parcel_id', $parcelIds)
            ->latest()
            ->get();

        $costTrend = $plans
            ->sortBy('created_at')
            ->groupBy('season_year')
            ->map(fn ($seasonPlans) => (float) $seasonPlans->sum('total_cost_inr'));

        return view('history.index', [
            'plansBySeason' => $plans->groupBy('season_year'),
            'historySeasonLabels' => $costTrend->keys()->values(),
            'historySeasonCosts' => $costTrend->values()->values(),
        ]);
    }
}
