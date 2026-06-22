@extends('layouts.app')

@section('title', $fertilizer->name)

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">{{ $fertilizer->name }}</h1>
            <p class="sfp-page-subtitle">{{ $fertilizer->type ?: 'Fertilizer' }}</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('admin.fertilizers.edit', $fertilizer) }}" class="sfp-btn sfp-btn-primary">Edit</a>
            <a href="{{ route('admin.fertilizers.index') }}" class="sfp-btn sfp-btn-outline">Back</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <div class="sfp-kv-grid">
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Type</span>
                    <span class="sfp-kv-value">{{ $fertilizer->type ?: 'Not set' }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Subsidized price/kg</span>
                    <span class="sfp-kv-value">INR {{ number_format((float) $fertilizer->price_per_kg, 2) }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Market price/kg</span>
                    <span class="sfp-kv-value">INR {{ number_format((float) ($fertilizer->unsubsidized_price_per_kg ?: 0), 2) }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">N/P/K</span>
                    <span class="sfp-kv-value">{{ $fertilizer->nitrogen_pct }} / {{ $fertilizer->phosphorus_pct }} / {{ $fertilizer->potassium_pct }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Status</span>
                    <span class="sfp-kv-value">{{ $fertilizer->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>

            @if ($fertilizer->description)
                <p class="sfp-page-subtitle mt-3 mb-0">{{ $fertilizer->description }}</p>
            @endif

            <form method="POST" action="{{ route('admin.fertilizers.destroy', $fertilizer) }}" class="sfp-action-row">
                @csrf
                @method('DELETE')
                <button type="submit" class="sfp-btn sfp-btn-danger sfp-delete-btn">Delete fertilizer</button>
            </form>
        </div>
    </div>
@endsection
