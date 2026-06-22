@extends('layouts.app')

@section('title', 'Edit Crop')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Edit crop</h1>
            <p class="sfp-page-subtitle">{{ $crop->name }} {{ $crop->variety }}</p>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <form method="POST" action="{{ route('admin.crops.update', $crop) }}">
                @method('PUT')
                @include('admin.crops._form', ['buttonText' => 'Update crop'])
            </form>
        </div>
    </div>
@endsection
