<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLandParcelRequest;
use App\Http\Requests\UpdateLandParcelRequest;
use App\Models\LandParcel;
use Illuminate\Http\Request;
use Throwable;

class LandParcelController extends Controller
{
    public function index(Request $request)
    {
        return view('parcels.index', [
            'parcels' => $request->user()->landParcels()->withCount(['soilTests', 'fertilizerPlans'])->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('parcels.create', ['parcel' => new LandParcel()]);
    }

    public function store(StoreLandParcelRequest $request)
    {
        try {
            $request->user()->landParcels()->create($request->validated());

            return redirect()->route('parcels.index')->with('success', 'Land parcel created successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(LandParcel $parcel)
    {
        $this->authorize('view', $parcel);

        return view('parcels.show', [
            'parcel' => $parcel->load(['soilTests' => fn ($query) => $query->latest('test_date'), 'fertilizerPlans.crop']),
        ]);
    }

    public function edit(LandParcel $parcel)
    {
        $this->authorize('update', $parcel);

        return view('parcels.edit', ['parcel' => $parcel]);
    }

    public function update(UpdateLandParcelRequest $request, LandParcel $parcel)
    {
        $this->authorize('update', $parcel);

        try {
            $parcel->update($request->validated());

            return redirect()->route('parcels.show', $parcel)->with('success', 'Land parcel updated successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(LandParcel $parcel)
    {
        $this->authorize('delete', $parcel);

        try {
            $parcel->delete();

            return redirect()->route('parcels.index')->with('success', 'Land parcel deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
