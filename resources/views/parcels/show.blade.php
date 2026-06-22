@extends('layouts.app')

@section('title', $parcel->parcel_name)

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">{{ $parcel->parcel_name }}</h1>
            <p class="sfp-page-subtitle">{{ $parcel->district }}, {{ $parcel->state }}</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('parcels.soil-tests.create', $parcel) }}" class="sfp-btn sfp-btn-primary">Add soil test</a>
            <a href="{{ route('plans.create', ['parcel_id' => $parcel->id]) }}" class="sfp-btn sfp-btn-accent">Generate plan</a>
            <a href="{{ route('parcels.edit', $parcel) }}" class="sfp-btn sfp-btn-outline">Edit</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="sfp-card h-100">
                <div class="sfp-card-header">
                    <div class="sfp-card-title">Parcel profile</div>
                </div>
                <div class="sfp-card-body">
                    <div class="sfp-kv-grid">
                        <div class="sfp-kv-item">
                            <span class="sfp-kv-label">Area</span>
                            <span class="sfp-kv-value">{{ number_format((float) $parcel->area_acres, 2) }} acres</span>
                        </div>
                        <div class="sfp-kv-item">
                            <span class="sfp-kv-label">Soil type</span>
                            <span class="sfp-kv-value">{{ $parcel->soil_type ? str($parcel->soil_type)->replace('_', ' ')->title() : 'Not set' }}</span>
                        </div>
                        <div class="sfp-kv-item">
                            <span class="sfp-kv-label">Latitude</span>
                            <span class="sfp-kv-value">{{ $parcel->latitude ?: 'Not set' }}</span>
                        </div>
                        <div class="sfp-kv-item">
                            <span class="sfp-kv-label">Longitude</span>
                            <span class="sfp-kv-value">{{ $parcel->longitude ?: 'Not set' }}</span>
                        </div>
                    </div>

                    @if ($parcel->notes)
                        <p class="sfp-page-subtitle mt-3 mb-0">{{ $parcel->notes }}</p>
                    @endif

                    <form method="POST" action="{{ route('parcels.destroy', $parcel) }}" class="sfp-action-row">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="sfp-btn sfp-btn-danger sfp-delete-btn">Delete parcel</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="sfp-card h-100">
                <div class="sfp-card-header">
                    <div>
                        <div class="sfp-card-title">Soil tests</div>
                        <div class="sfp-page-subtitle">Latest records are shown first.</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="sfp-table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>pH</th>
                                <th>N/P/K kg/ha</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($parcel->soilTests as $soilTest)
                                <tr>
                                    <td class="fw-bold">{{ $soilTest->test_date->format('d M Y') }}</td>
                                    <td>{{ $soilTest->ph_level }}</td>
                                    <td>{{ $soilTest->nitrogen_kg_ha }} / {{ $soilTest->phosphorus_kg_ha }} / {{ $soilTest->potassium_kg_ha }}</td>
                                    <td>
                                        <div class="sfp-table-actions">
                                            <a href="{{ route('parcels.soil-tests.show', [$parcel, $soilTest]) }}" class="sfp-btn sfp-btn-outline sfp-btn-sm">View</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No soil tests yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
