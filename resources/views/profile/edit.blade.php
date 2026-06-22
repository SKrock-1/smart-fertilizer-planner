@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Profile</h1>
            <p class="sfp-page-subtitle">Manage your account details and password.</p>
        </div>
    </div>

    <div class="sfp-stack">
        <div class="sfp-card">
            <div class="sfp-card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="sfp-card">
            <div class="sfp-card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="sfp-card">
            <div class="sfp-card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
