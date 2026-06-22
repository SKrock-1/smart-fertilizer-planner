@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Users</h1>
            <p class="sfp-page-subtitle">Review farmer and administrator accounts.</p>
        </div>
    </div>

    <div class="sfp-card">
        <div class="table-responsive">
            <table class="sfp-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="sfp-badge {{ $user->role === 'admin' ? 'sfp-badge-info' : 'sfp-badge-success' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ optional($user->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="sfp-table-actions">
                                    <a class="sfp-btn sfp-btn-outline sfp-btn-sm" href="{{ route('admin.users.show', $user) }}">View</a>
                                    <a class="sfp-btn sfp-btn-primary sfp-btn-sm" href="{{ route('admin.users.edit', $user) }}">Role</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
