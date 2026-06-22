@extends('layouts.app')

@section('title', 'Add Fertilizer')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Add fertilizer</h1>
            <p class="sfp-page-subtitle">Create a fertilizer profile for recommendation calculations.</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('admin.fertilizers.index') }}" class="sfp-btn sfp-btn-outline">Back</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <form method="POST" action="{{ route('admin.fertilizers.store') }}">
                @include('admin.fertilizers._form', ['buttonText' => 'Create fertilizer'])
            </form>
        </div>
    </div>
@endsection
