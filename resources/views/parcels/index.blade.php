@extends('layouts.app')

@section('title', 'Land Parcels')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Land parcels</h1>
            <p class="sfp-page-subtitle">Manage farm plots, soil tests, and fertilizer planning history.</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('parcels.create') }}" class="sfp-btn sfp-btn-primary">Add parcel</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="table-responsive">
            <table class="sfp-table mb-0">
                <thead>
                    <tr>
                        <th>Parcel</th>
                        <th>Area</th>
                        <th>Location</th>
                        <th>Soil tests</th>
                        <th>Plans</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parcels as $parcel)
                        <tr>
                            <td class="fw-bold">{{ $parcel->parcel_name }}</td>
                            <td>{{ number_format((float) $parcel->area_acres, 2) }} acres</td>
                            <td>{{ $parcel->district }}, {{ $parcel->state }}</td>
                            <td>{{ $parcel->soil_tests_count }}</td>
                            <td>{{ $parcel->fertilizer_plans_count }}</td>
                            <td>
                                <div class="sfp-table-actions">
                                    <a href="{{ route('parcels.show', $parcel) }}" class="sfp-btn sfp-btn-outline sfp-btn-sm">View</a>
                                    <a href="{{ route('parcels.soil-tests.create', $parcel) }}" class="sfp-btn sfp-btn-primary sfp-btn-sm">Soil test</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No parcels yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $parcels->links() }}</div>
@endsection
