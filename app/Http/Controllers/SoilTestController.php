<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoilTestRequest;
use App\Models\LandParcel;
use App\Models\SoilTest;
use Throwable;

class SoilTestController extends Controller
{
    public function create(LandParcel $parcel)
    {
        $this->authorize('view', $parcel);

        return view('soil-tests.create', ['parcel' => $parcel, 'soilTest' => new SoilTest()]);
    }

    public function store(StoreSoilTestRequest $request, LandParcel $parcel)
    {
        $this->authorize('view', $parcel);

        try {
            $data = $request->validated();

            if ($request->hasFile('lab_report')) {
                $data['lab_report_path'] = $request->file('lab_report')->store('soil-reports', 'public');
            }

            unset($data['lab_report'], $data['parcel_id']);

            $parcel->soilTests()->create($data);

            return redirect()->route('parcels.show', $parcel)->with('success', 'Soil test added successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(LandParcel $parcel, SoilTest $soilTest)
    {
        abort_unless($soilTest->parcel_id === $parcel->id, 404);
        $this->authorize('view', $soilTest);

        return view('soil-tests.show', ['parcel' => $parcel, 'soilTest' => $soilTest]);
    }

    public function destroy(LandParcel $parcel, SoilTest $soilTest)
    {
        abort_unless($soilTest->parcel_id === $parcel->id, 404);
        $this->authorize('delete', $soilTest);

        try {
            $soilTest->delete();

            return redirect()->route('parcels.show', $parcel)->with('success', 'Soil test deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
