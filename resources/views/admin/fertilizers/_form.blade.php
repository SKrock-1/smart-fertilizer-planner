@csrf

<div class="sfp-form-grid">
    <div>
        <label for="name" class="sfp-label">Name <span class="required">*</span></label>
        <input id="name" name="name" class="sfp-input" value="{{ old('name', $fertilizer->name) }}" required>
    </div>
    <div>
        <label for="type" class="sfp-label">Type</label>
        <input id="type" name="type" class="sfp-input" value="{{ old('type', $fertilizer->type) }}" placeholder="straight, compound, micronutrient">
    </div>
    <div>
        <label for="nitrogen_pct" class="sfp-label">Nitrogen % <span class="required">*</span></label>
        <input id="nitrogen_pct" name="nitrogen_pct" type="number" step="0.01" min="0" max="100" class="sfp-input" value="{{ old('nitrogen_pct', $fertilizer->nitrogen_pct ?? 0) }}" required>
    </div>
    <div>
        <label for="phosphorus_pct" class="sfp-label">Phosphorus % <span class="required">*</span></label>
        <input id="phosphorus_pct" name="phosphorus_pct" type="number" step="0.01" min="0" max="100" class="sfp-input" value="{{ old('phosphorus_pct', $fertilizer->phosphorus_pct ?? 0) }}" required>
    </div>
    <div>
        <label for="potassium_pct" class="sfp-label">Potassium % <span class="required">*</span></label>
        <input id="potassium_pct" name="potassium_pct" type="number" step="0.01" min="0" max="100" class="sfp-input" value="{{ old('potassium_pct', $fertilizer->potassium_pct ?? 0) }}" required>
    </div>
    <div>
        <label for="price_per_kg" class="sfp-label">Subsidized price/kg <span class="required">*</span></label>
        <input id="price_per_kg" name="price_per_kg" type="number" step="0.01" min="0" class="sfp-input" value="{{ old('price_per_kg', $fertilizer->price_per_kg) }}" required>
    </div>
    <div>
        <label for="unsubsidized_price_per_kg" class="sfp-label">Market price/kg</label>
        <input id="unsubsidized_price_per_kg" name="unsubsidized_price_per_kg" type="number" step="0.01" min="0" class="sfp-input" value="{{ old('unsubsidized_price_per_kg', $fertilizer->unsubsidized_price_per_kg ?? 0) }}">
    </div>
    <label class="sfp-checkbox-row">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $fertilizer->exists ? $fertilizer->is_active : true))>
        Active
    </label>
    <div class="sfp-form-full">
        <label for="description" class="sfp-label">Description</label>
        <textarea id="description" name="description" rows="4" class="sfp-textarea">{{ old('description', $fertilizer->description) }}</textarea>
    </div>
</div>

<div class="sfp-action-row">
    <button type="submit" class="sfp-btn sfp-btn-primary">{{ $buttonText }}</button>
    <a href="{{ route('admin.fertilizers.index') }}" class="sfp-btn sfp-btn-outline">Cancel</a>
</div>
