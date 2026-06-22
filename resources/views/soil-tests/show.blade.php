@extends('layouts.app')

@section('title', 'Soil Test')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Soil test</h1>
            <p class="sfp-page-subtitle">{{ $parcel->parcel_name }} - {{ $soilTest->test_date->format('d M Y') }}</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('parcels.show', $parcel) }}" class="sfp-btn sfp-btn-outline">Back to parcel</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Soil analysis</div>
                <div class="sfp-page-subtitle">Lab values used by the fertilizer recommendation engine.</div>
            </div>
        </div>
        <div class="sfp-card-body">
            <div class="sfp-kv-grid">
                <div class="sfp-kv-item"><span class="sfp-kv-label">Date</span><span class="sfp-kv-value">{{ $soilTest->test_date->format('d M Y') }}</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">pH</span><span class="sfp-kv-value">{{ $soilTest->ph_level }}</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">Nitrogen</span><span class="sfp-kv-value">{{ $soilTest->nitrogen_kg_ha }} kg/ha</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">Phosphorus</span><span class="sfp-kv-value">{{ $soilTest->phosphorus_kg_ha }} kg/ha</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">Potassium</span><span class="sfp-kv-value">{{ $soilTest->potassium_kg_ha }} kg/ha</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">Organic carbon</span><span class="sfp-kv-value">{{ $soilTest->organic_carbon_pct ?: 'Not set' }}</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">Zinc</span><span class="sfp-kv-value">{{ $soilTest->zinc_ppm ?: 'Not set' }}</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">Sulfur</span><span class="sfp-kv-value">{{ $soilTest->sulfur_ppm ?: 'Not set' }}</span></div>
            </div>

            <form method="POST" action="{{ route('parcels.soil-tests.destroy', [$parcel, $soilTest]) }}" class="sfp-action-row">
                @csrf
                @method('DELETE')
                <button type="submit" class="sfp-btn sfp-btn-danger sfp-delete-btn">Delete soil test</button>
            </form>
        </div>
    </div>
@endsection
