@extends('layouts.app')

@section('title', 'Edit Parcel')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Edit {{ $parcel->parcel_name }}</h1>
            <p class="sfp-page-subtitle">Update field profile and location details.</p>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <form method="POST" action="{{ route('parcels.update', $parcel) }}">
                @method('PUT')
                @include('parcels._form', ['buttonText' => 'Update parcel'])
            </form>
        </div>
    </div>
@endsection
