@extends('layouts.app')

@section('title', 'Fertilizer Plans')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Fertilizer plans</h1>
            <p class="sfp-page-subtitle">Review generated recommendations and export farmer-ready PDFs.</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('plans.create') }}" class="sfp-btn sfp-btn-primary">Generate plan</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="table-responsive">
            <table class="sfp-table mb-0">
                <thead>
                    <tr>
                        <th>Season</th>
                        <th>Parcel</th>
                        <th>Crop</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($plans as $plan)
                        <tr>
                            <td class="fw-bold">{{ $plan->season_year }}</td>
                            <td>{{ $plan->landParcel->parcel_name }}</td>
                            <td>{{ $plan->crop->name }}</td>
                            <td>INR {{ number_format((float) $plan->total_cost_inr, 2) }}</td>
                            <td>
                                <span class="sfp-badge {{ $plan->status === 'finalized' ? 'sfp-badge-success' : 'sfp-badge-warning' }}">
                                    {{ ucfirst($plan->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="sfp-table-actions">
                                    <a href="{{ route('plans.show', $plan) }}" class="sfp-btn sfp-btn-outline sfp-btn-sm">View</a>
                                    <a href="{{ route('plans.pdf', $plan) }}" class="sfp-btn sfp-btn-primary sfp-btn-sm">PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No fertilizer plans yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $plans->links() }}</div>
@endsection
