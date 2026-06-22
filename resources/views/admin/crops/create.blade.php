@extends('layouts.app')

@section('title', 'Add Crop')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Add crop</h1>
            <p class="sfp-page-subtitle">Create a crop RDF profile.</p>
        </div>
    </div>

    <div class="sfp-card">
        <div class="sfp-card-body">
            <form method="POST" action="{{ route('admin.crops.store') }}">
                @include('admin.crops._form', ['buttonText' => 'Create crop'])
            </form>
        </div>
    </div>
@endsection
