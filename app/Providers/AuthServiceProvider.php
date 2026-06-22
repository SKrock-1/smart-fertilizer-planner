<?php

namespace App\Providers;

use App\Models\FertilizerPlan;
use App\Models\LandParcel;
use App\Models\SoilTest;
use App\Policies\FertilizerPlanPolicy;
use App\Policies\LandParcelPolicy;
use App\Policies\SoilTestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        LandParcel::class => LandParcelPolicy::class,
        SoilTest::class => SoilTestPolicy::class,
        FertilizerPlan::class => FertilizerPlanPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', fn ($user) => $user->role === 'admin');
    }
}
