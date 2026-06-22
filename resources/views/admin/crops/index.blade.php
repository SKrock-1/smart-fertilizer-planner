@extends('layouts.app')

@section('title', 'Crop Master')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Crops</h1>
            <p class="sfp-page-subtitle">Manage RDF values used by plan calculations.</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('admin.crops.create') }}" class="sfp-btn sfp-btn-primary">Add crop</a>
        </div>
    </div>

    <div class="sfp-card">
        <div class="table-responsive">
            <table class="sfp-table mb-0">
                <thead>
                    <tr>
                        <th>Crop</th>
                        <th>Season</th>
                        <th>RDF N/P/K</th>
                        <th>Duration</th>
                        <th>Active</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($crops as $crop)
                        <tr>
                            <td class="fw-bold">{{ $crop->name }} {{ $crop->variety }}</td>
                            <td><span class="sfp-badge sfp-badge-{{ $crop->season }}">{{ ucfirst($crop->season) }}</span></td>
                            <td>{{ $crop->rdf_nitrogen }} / {{ $crop->rdf_phosphorus }} / {{ $crop->rdf_potassium }}</td>
                            <td>{{ $crop->duration_days ?: 'Not set' }}</td>
                            <td>{{ $crop->is_active ? 'Yes' : 'No' }}</td>
                            <td>
                                <div class="sfp-table-actions">
                                    <a href="{{ route('admin.crops.show', $crop) }}" class="sfp-btn sfp-btn-outline sfp-btn-sm">View</a>
                                    <a href="{{ route('admin.crops.edit', $crop) }}" class="sfp-btn sfp-btn-primary sfp-btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No crops found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $crops->links() }}</div>
@endsection
