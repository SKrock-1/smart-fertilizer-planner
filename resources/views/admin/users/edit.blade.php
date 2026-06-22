@extends('layouts.app')

@section('title', 'Update User Role')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Update role</h1>
            <p class="sfp-page-subtitle">{{ $user->name }} - {{ $user->email }}</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('admin.users.show', $user) }}" class="sfp-btn sfp-btn-outline">Back</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="sfp-form-group">
                    <label for="role" class="sfp-label">Role <span class="required">*</span></label>
                    <select id="role" name="role" class="sfp-select" required>
                        <option value="farmer" @selected(old('role', $user->role) === 'farmer')>Farmer</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    </select>
                    <p class="sfp-page-subtitle mb-0">Admins can manage crops, fertilizers, users, and all plans.</p>
                </div>

                <div class="sfp-action-row">
                    <button type="submit" class="sfp-btn sfp-btn-primary">Update role</button>
                    <a href="{{ route('admin.users.show', $user) }}" class="sfp-btn sfp-btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
