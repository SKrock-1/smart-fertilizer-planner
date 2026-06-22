@extends('layouts.app')

@section('title', $crop->name)

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">{{ $crop->name }}</h1>
            <p class="sfp-page-subtitle">{{ $crop->variety ?: 'No variety set' }}</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('admin.crops.edit', $crop) }}" class="sfp-btn sfp-btn-primary">Edit</a>
            <a href="{{ route('admin.crops.index') }}" class="sfp-btn sfp-btn-outline">Back</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <div class="sfp-kv-grid">
                <div class="sfp-kv-item"><span class="sfp-kv-label">Season</span><span class="sfp-kv-value">{{ ucfirst($crop->season) }}</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">RDF N/P/K</span><span class="sfp-kv-value">{{ $crop->rdf_nitrogen }} / {{ $crop->rdf_phosphorus }} / {{ $crop->rdf_potassium }}</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">Duration</span><span class="sfp-kv-value">{{ $crop->duration_days ?: 'Not set' }}</span></div>
                <div class="sfp-kv-item"><span class="sfp-kv-label">Active</span><span class="sfp-kv-value">{{ $crop->is_active ? 'Yes' : 'No' }}</span></div>
            </div>
            @if ($crop->description)
                <p class="sfp-page-subtitle mt-3 mb-0">{{ $crop->description }}</p>
            @endif
            <form method="POST" action="{{ route('admin.crops.destroy', $crop) }}" class="sfp-action-row">
                @csrf
                @method('DELETE')
                <button type="submit" class="sfp-btn sfp-btn-danger sfp-delete-btn">Delete crop</button>
            </form>
        </div>
    </div>
@endsection
