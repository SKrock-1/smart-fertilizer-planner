@extends('layouts.app')

@section('title', 'Fertilizer Master')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Fertilizers</h1>
            <p class="sfp-page-subtitle">Manage nutrient percentages and market prices used by fertilizer plans.</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('admin.fertilizers.create') }}" class="sfp-btn sfp-btn-primary">Add fertilizer</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="table-responsive">
            <table class="sfp-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>N/P/K</th>
                        <th>Subsidized</th>
                        <th>Market</th>
                        <th>Type</th>
                        <th>Active</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fertilizers as $fertilizer)
                        <tr>
                            <td class="fw-bold">{{ $fertilizer->name }}</td>
                            <td>{{ $fertilizer->nitrogen_pct }} / {{ $fertilizer->phosphorus_pct }} / {{ $fertilizer->potassium_pct }}</td>
                            <td>INR {{ number_format((float) $fertilizer->price_per_kg, 2) }}</td>
                            <td>INR {{ number_format((float) ($fertilizer->unsubsidized_price_per_kg ?: 0), 2) }}</td>
                            <td>{{ $fertilizer->type ?: 'Not set' }}</td>
                            <td>
                                <span class="sfp-badge {{ $fertilizer->is_active ? 'sfp-badge-success' : 'sfp-badge-warning' }}">
                                    {{ $fertilizer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="sfp-table-actions">
                                    <a href="{{ route('admin.fertilizers.show', $fertilizer) }}" class="sfp-btn sfp-btn-outline sfp-btn-sm">View</a>
                                    <a href="{{ route('admin.fertilizers.edit', $fertilizer) }}" class="sfp-btn sfp-btn-primary sfp-btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No fertilizers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $fertilizers->links() }}</div>
@endsection
