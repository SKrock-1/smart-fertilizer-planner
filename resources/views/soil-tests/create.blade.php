@extends('layouts.app')

@section('title', 'Add Soil Test')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Add Soil Test for {{ $parcel->parcel_name }}</h1>
            <p class="sfp-page-subtitle">Enter current soil values to improve plan accuracy.</p>
        </div>
        <a href="{{ route('parcels.show', $parcel) }}" class="sfp-btn sfp-btn-outline">Back to parcel</a>
    </div>

    <div class="sfp-card mb-4">
        <div class="sfp-card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="sfp-stat-label">Area</div>
                    <div class="fw-bold">{{ number_format((float) $parcel->area_acres, 2) }} acres</div>
                </div>
                <div class="col-md-3">
                    <div class="sfp-stat-label">Location</div>
                    <div class="fw-bold">{{ $parcel->district }}, {{ $parcel->state }}</div>
                </div>
                <div class="col-md-3">
                    <div class="sfp-stat-label">Soil type</div>
                    <div class="fw-bold">{{ $parcel->soil_type ? ucfirst($parcel->soil_type) : 'Not set' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="sfp-stat-label">Parcel ID</div>
                    <div class="fw-bold">#{{ $parcel->id }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('parcels.soil-tests.store', $parcel) }}" enctype="multipart/form-data" novalidate>
        @include('soil-tests._form', ['buttonText' => 'Save soil test'])
    </form>
@endsection
