<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFertilizerRequest;
use App\Http\Requests\Admin\UpdateFertilizerRequest;
use App\Models\Fertilizer;
use Illuminate\Support\Facades\Gate;
use Throwable;

class FertilizerController extends Controller
{
    public function index()
    {
        Gate::authorize('admin');

        return view('admin.fertilizers.index', ['fertilizers' => Fertilizer::latest()->paginate(12)]);
    }

    public function create()
    {
        Gate::authorize('admin');

        return view('admin.fertilizers.create', ['fertilizer' => new Fertilizer()]);
    }

    public function store(StoreFertilizerRequest $request)
    {
        Gate::authorize('admin');

        try {
            Fertilizer::create($request->safe()->merge(['is_active' => $request->boolean('is_active')])->all());

            return redirect()->route('admin.fertilizers.index')->with('success', 'Fertilizer created successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Fertilizer $fertilizer)
    {
        Gate::authorize('admin');

        return view('admin.fertilizers.show', ['fertilizer' => $fertilizer]);
    }

    public function edit(Fertilizer $fertilizer)
    {
        Gate::authorize('admin');

        return view('admin.fertilizers.edit', ['fertilizer' => $fertilizer]);
    }

    public function update(UpdateFertilizerRequest $request, Fertilizer $fertilizer)
    {
        Gate::authorize('admin');

        try {
            $fertilizer->update($request->safe()->merge(['is_active' => $request->boolean('is_active')])->all());

            return redirect()->route('admin.fertilizers.show', $fertilizer)->with('success', 'Fertilizer updated successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Fertilizer $fertilizer)
    {
        Gate::authorize('admin');

        try {
            $fertilizer->delete();

            return redirect()->route('admin.fertilizers.index')->with('success', 'Fertilizer deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
