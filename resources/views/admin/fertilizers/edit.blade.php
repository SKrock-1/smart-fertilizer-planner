@extends('layouts.app')

@section('title', 'Edit Fertilizer')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Edit fertilizer</h1>
            <p class="sfp-page-subtitle">{{ $fertilizer->name }}</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('admin.fertilizers.show', $fertilizer) }}" class="sfp-btn sfp-btn-outline">Back</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <form method="POST" action="{{ route('admin.fertilizers.update', $fertilizer) }}">
                @method('PUT')
                @include('admin.fertilizers._form', ['buttonText' => 'Update fertilizer'])
            </form>
        </div>
    </div>
@endsection
