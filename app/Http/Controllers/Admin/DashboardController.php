<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\Fertilizer;
use App\Models\FertilizerPlan;
use App\Models\LandParcel;
use App\Models\SoilTest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        Gate::authorize('admin');

        return view('admin.dashboard.index', [
            'stats' => [
                'users' => User::count(),
                'farmers' => User::where('role', 'farmer')->count(),
                'land_parcels' => LandParcel::count(),
                'soil_tests' => SoilTest::count(),
                'plans' => FertilizerPlan::count(),
                'total_cost' => FertilizerPlan::sum('total_cost_inr'),
                'crops' => Crop::count(),
                'fertilizers' => Fertilizer::count(),
            ],
            'recentPlans' => FertilizerPlan::with(['landParcel.user', 'crop'])->latest()->take(8)->get(),
        ]);
    }
}
