@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="sfp-card mx-auto" style="max-width: 720px;">
        <div class="sfp-card-body text-center">
            <div class="sfp-stat-label mb-2">Error 404</div>
            <h1 class="sfp-page-title">Page Not Found</h1>
            <p class="sfp-page-subtitle mb-4">The page you are looking for may have moved, or the link may be incorrect.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <button type="button" class="sfp-btn sfp-btn-outline" onclick="window.history.back()">Go back</button>
                <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="sfp-btn sfp-btn-primary">Home</a>
            </div>
        </div>
    </div>
@endsection
