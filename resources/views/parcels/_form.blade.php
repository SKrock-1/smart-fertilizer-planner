@csrf

<div class="sfp-form-grid">
    <div>
        <label for="parcel_name" class="sfp-label">Parcel name <span class="required">*</span></label>
        <input id="parcel_name" name="parcel_name" class="sfp-input" value="{{ old('parcel_name', $parcel->parcel_name) }}" required>
        @error('parcel_name') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="area_acres" class="sfp-label">Area acres <span class="required">*</span></label>
        <input id="area_acres" name="area_acres" type="number" step="0.01" min="0.01" class="sfp-input" value="{{ old('area_acres', $parcel->area_acres) }}" required>
        @error('area_acres') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="district" class="sfp-label">District <span class="required">*</span></label>
        <input id="district" name="district" class="sfp-input" value="{{ old('district', $parcel->district) }}" required>
        @error('district') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="state" class="sfp-label">State <span class="required">*</span></label>
        <input id="state" name="state" class="sfp-input" value="{{ old('state', $parcel->state) }}" required>
        @error('state') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="latitude" class="sfp-label">Latitude</label>
        <input id="latitude" name="latitude" type="number" step="0.0000001" class="sfp-input" value="{{ old('latitude', $parcel->latitude) }}">
        @error('latitude') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="longitude" class="sfp-label">Longitude</label>
        <input id="longitude" name="longitude" type="number" step="0.0000001" class="sfp-input" value="{{ old('longitude', $parcel->longitude) }}">
        @error('longitude') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="soil_type" class="sfp-label">Soil type</label>
        <select id="soil_type" name="soil_type" class="sfp-select">
            <option value="">Select soil type</option>
            @foreach (['loamy' => 'Loamy', 'sandy' => 'Sandy', 'clay' => 'Clay', 'silt' => 'Silt', 'black_cotton' => 'Black cotton'] as $type => $label)
                <option value="{{ $type }}" @selected(old('soil_type', $parcel->soil_type) === $type)>{{ $label }}</option>
            @endforeach
        </select>
        @error('soil_type') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>

    <div class="sfp-form-full">
        <label for="notes" class="sfp-label">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="sfp-textarea">{{ old('notes', $parcel->notes) }}</textarea>
        @error('notes') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="sfp-action-row">
    <button type="submit" class="sfp-btn sfp-btn-primary">{{ $buttonText }}</button>
    <a href="{{ route('parcels.index') }}" class="sfp-btn sfp-btn-outline">Cancel</a>
</div>
