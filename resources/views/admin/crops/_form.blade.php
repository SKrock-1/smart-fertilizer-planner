@csrf

<div class="sfp-form-grid">
    <div>
        <label for="name" class="sfp-label">Name <span class="required">*</span></label>
        <input id="name" name="name" class="sfp-input" value="{{ old('name', $crop->name) }}" required>
        @error('name') <div class="sfp-error">{{ $message }}</div> @enderror
    </div>
    <div>
        <label for="variety" class="sfp-label">Variety</label>
        <input id="variety" name="variety" class="sfp-input" value="{{ old('variety', $crop->variety) }}">
    </div>
    <div>
        <label for="season" class="sfp-label">Season <span class="required">*</span></label>
        <select id="season" name="season" class="sfp-select" required>
            @foreach (['kharif', 'rabi', 'zaid'] as $season)
                <option value="{{ $season }}" @selected(old('season', $crop->season) === $season)>{{ ucfirst($season) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="duration_days" class="sfp-label">Duration days</label>
        <input id="duration_days" name="duration_days" type="number" class="sfp-input" value="{{ old('duration_days', $crop->duration_days) }}">
    </div>
    <div>
        <label for="rdf_nitrogen" class="sfp-label">RDF nitrogen kg/ha <span class="required">*</span></label>
        <input id="rdf_nitrogen" name="rdf_nitrogen" type="number" step="0.01" min="0" class="sfp-input" value="{{ old('rdf_nitrogen', $crop->rdf_nitrogen) }}" required>
    </div>
    <div>
        <label for="rdf_phosphorus" class="sfp-label">RDF phosphorus kg/ha <span class="required">*</span></label>
        <input id="rdf_phosphorus" name="rdf_phosphorus" type="number" step="0.01" min="0" class="sfp-input" value="{{ old('rdf_phosphorus', $crop->rdf_phosphorus) }}" required>
    </div>
    <div>
        <label for="rdf_potassium" class="sfp-label">RDF potassium kg/ha <span class="required">*</span></label>
        <input id="rdf_potassium" name="rdf_potassium" type="number" step="0.01" min="0" class="sfp-input" value="{{ old('rdf_potassium', $crop->rdf_potassium) }}" required>
    </div>
    <label class="sfp-checkbox-row">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $crop->exists ? $crop->is_active : true))>
        Active
    </label>
    <div class="sfp-form-full">
        <label for="description" class="sfp-label">Description</label>
        <textarea id="description" name="description" rows="4" class="sfp-textarea">{{ old('description', $crop->description) }}</textarea>
    </div>
</div>

<div class="sfp-action-row">
    <button type="submit" class="sfp-btn sfp-btn-primary">{{ $buttonText }}</button>
    <a href="{{ route('admin.crops.index') }}" class="sfp-btn sfp-btn-outline">Cancel</a>
</div>
