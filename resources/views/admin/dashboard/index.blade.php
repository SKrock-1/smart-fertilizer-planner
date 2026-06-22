@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Admin dashboard</h1>
            <p class="sfp-page-subtitle">System-wide farming activity, plan volume, and fertilizer configuration.</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('admin.crops.index') }}" class="sfp-btn sfp-btn-outline">Crops</a>
            <a href="{{ route('admin.fertilizers.index') }}" class="sfp-btn sfp-btn-primary">Fertilizers</a>
        </div>
    </div>

    <div class="sfp-stat-grid mb-4">
        <div class="sfp-stat-card">
            <span class="sfp-stat-icon">US</span>
            <div class="sfp-stat-label">Users</div>
            <div class="sfp-stat-value">{{ number_format($stats['users'] ?? 0) }}</div>
            <div class="sfp-stat-sub">{{ number_format($stats['farmers'] ?? 0) }} farmers registered</div>
        </div>
        <div class="sfp-stat-card">
            <span class="sfp-stat-icon">LP</span>
            <div class="sfp-stat-label">Land parcels</div>
            <div class="sfp-stat-value">{{ number_format($stats['land_parcels'] ?? 0) }}</div>
            <div class="sfp-stat-sub">{{ number_format($stats['soil_tests'] ?? 0) }} soil tests recorded</div>
        </div>
        <div class="sfp-stat-card accent">
            <span class="sfp-stat-icon">PL</span>
            <div class="sfp-stat-label">Plans</div>
            <div class="sfp-stat-value">{{ number_format($stats['plans'] ?? 0) }}</div>
            <div class="sfp-stat-sub">INR {{ number_format((float) ($stats['total_cost'] ?? 0), 2) }} total value</div>
        </div>
        <div class="sfp-stat-card">
            <span class="sfp-stat-icon">RD</span>
            <div class="sfp-stat-label">Master data</div>
            <div class="sfp-stat-value">{{ number_format(($stats['crops'] ?? 0) + ($stats['fertilizers'] ?? 0)) }}</div>
            <div class="sfp-stat-sub">{{ number_format($stats['crops'] ?? 0) }} crops, {{ number_format($stats['fertilizers'] ?? 0) }} fertilizers</div>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Recent plans</div>
                <div class="sfp-page-subtitle mb-0">Latest fertilizer plans generated across the system.</div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="sfp-table mb-0">
                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Parcel</th>
                        <th>Crop</th>
                        <th>Season</th>
                        <th class="text-end">Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentPlans as $plan)
                        <tr>
                            <td class="fw-bold">{{ $plan->landParcel->user->name ?? 'Unknown' }}</td>
                            <td>{{ $plan->landParcel->parcel_name ?? 'Deleted parcel' }}</td>
                            <td>{{ $plan->crop->name ?? 'Deleted crop' }}</td>
                            <td>{{ $plan->season_year }}</td>
                            <td class="text-end">INR {{ number_format((float) $plan->total_cost_inr, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No plans generated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
