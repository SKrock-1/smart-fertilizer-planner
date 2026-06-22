@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">{{ $user->name }}</h1>
            <p class="sfp-page-subtitle">{{ $user->email }}</p>
        </div>
        <div class="sfp-page-actions">
            <a class="sfp-btn sfp-btn-primary" href="{{ route('admin.users.edit', $user) }}">Update role</a>
            <a class="sfp-btn sfp-btn-outline" href="{{ route('admin.users.index') }}">Back</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <div class="sfp-kv-grid">
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Email</span>
                    <span class="sfp-kv-value">{{ $user->email }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Role</span>
                    <span class="sfp-kv-value">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Parcels</span>
                    <span class="sfp-kv-value">{{ $user->land_parcels_count }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Phone</span>
                    <span class="sfp-kv-value">{{ $user->phone ?: 'Not set' }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Joined</span>
                    <span class="sfp-kv-value">{{ optional($user->created_at)->format('M d, Y') }}</span>
                </div>
                <div class="sfp-kv-item">
                    <span class="sfp-kv-label">Email verified</span>
                    <span class="sfp-kv-value">{{ $user->email_verified_at ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            @if ($user->address)
                <p class="sfp-page-subtitle mt-3 mb-0">{{ $user->address }}</p>
            @endif

            @if (auth()->id() !== $user->id)
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="sfp-action-row">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="sfp-btn sfp-btn-danger sfp-delete-btn">Delete user</button>
                </form>
            @endif
        </div>
    </div>
@endsection
