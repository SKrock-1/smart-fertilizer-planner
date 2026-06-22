@extends('layouts.app')

@section('title', 'Add Land Parcel')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Add land parcel</h1>
            <p class="sfp-page-subtitle">Register a field before adding soil test data.</p>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <form method="POST" action="{{ route('parcels.store') }}">
                @include('parcels._form', ['buttonText' => 'Create parcel'])
            </form>
        </div>
    </div>
@endsection
