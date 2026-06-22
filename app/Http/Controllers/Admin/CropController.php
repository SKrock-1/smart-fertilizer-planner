<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCropRequest;
use App\Http\Requests\Admin\UpdateCropRequest;
use App\Models\Crop;
use Illuminate\Support\Facades\Gate;
use Throwable;

class CropController extends Controller
{
    public function index()
    {
        Gate::authorize('admin');

        return view('admin.crops.index', ['crops' => Crop::latest()->paginate(12)]);
    }

    public function create()
    {
        Gate::authorize('admin');

        return view('admin.crops.create', ['crop' => new Crop()]);
    }

    public function store(StoreCropRequest $request)
    {
        Gate::authorize('admin');

        try {
            Crop::create($request->safe()->merge(['is_active' => $request->boolean('is_active')])->all());

            return redirect()->route('admin.crops.index')->with('success', 'Crop created successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Crop $crop)
    {
        Gate::authorize('admin');

        return view('admin.crops.show', ['crop' => $crop]);
    }

    public function edit(Crop $crop)
    {
        Gate::authorize('admin');

        return view('admin.crops.edit', ['crop' => $crop]);
    }

    public function update(UpdateCropRequest $request, Crop $crop)
    {
        Gate::authorize('admin');

        try {
            $crop->update($request->safe()->merge(['is_active' => $request->boolean('is_active')])->all());

            return redirect()->route('admin.crops.show', $crop)->with('success', 'Crop updated successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Crop $crop)
    {
        Gate::authorize('admin');

        try {
            $crop->delete();

            return redirect()->route('admin.crops.index')->with('success', 'Crop deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
