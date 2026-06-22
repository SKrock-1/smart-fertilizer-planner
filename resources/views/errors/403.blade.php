@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
    <div class="sfp-card mx-auto" style="max-width: 720px;">
        <div class="sfp-card-body text-center">
            <div class="sfp-stat-label mb-2">Error 403</div>
            <h1 class="sfp-page-title">Access Denied</h1>
            <p class="sfp-page-subtitle mb-4">You do not have permission to view this page.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <button type="button" class="sfp-btn sfp-btn-outline" onclick="window.history.back()">Go back</button>
                <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="sfp-btn sfp-btn-primary">Home</a>
            </div>
        </div>
    </div>
@endsection
