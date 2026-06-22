@extends('layouts.app')

@section('title', 'Generate Fertilizer Plan')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Generate Fertilizer Plan</h1>
            <p class="sfp-page-subtitle">Select a tested parcel, crop, and season to calculate fertilizer needs.</p>
        </div>
        <a href="{{ route('plans.index') }}" class="sfp-btn sfp-btn-outline">Back to plans</a>
    </div>

    <div class="sfp-stepper" aria-label="Plan generation steps">
        <div class="sfp-step-progress"><span id="planStepProgress" style="width: 33.33%"></span></div>
        <div class="sfp-step is-active" data-step-indicator="1">1. Select Parcel</div>
        <div class="sfp-step" data-step-indicator="2">2. Select Crop</div>
        <div class="sfp-step" data-step-indicator="3">3. Review & Generate</div>
    </div>

    <form method="POST" action="{{ route('plans.store') }}" id="planGeneratorForm" data-old-parcel="{{ old('parcel_id') }}" data-old-crop="{{ old('crop_id') }}">
        @csrf

        <section class="sfp-step-panel is-active" data-step-panel="1">
            <div class="sfp-card">
                <div class="sfp-card-header">
                    <div>
                        <div class="sfp-card-title">Select land parcel</div>
                        <div class="sfp-page-subtitle">A recent soil test is required before plan generation.</div>
                    </div>
                </div>
                <div class="sfp-card-body">
                    <label for="parcel_id" class="sfp-label">Land parcel <span class="required">*</span></label>
                    <select id="parcel_id" name="parcel_id" class="sfp-select" required>
                        <option value="">Select parcel</option>
                        @foreach ($parcels as $parcel)
                            <option value="{{ $parcel->id }}" @selected(old('parcel_id') == $parcel->id)>
                                {{ $parcel->parcel_name }} - {{ number_format((float) $parcel->area_acres, 2) }} acres
                            </option>
                        @endforeach
                    </select>
                    @error('parcel_id') <div class="sfp-error">{{ $message }}</div> @enderror

                    <div id="parcelDetails" class="sfp-detail-panel mt-3 d-none"></div>
                    <div id="parcelWarning" class="sfp-alert sfp-alert-danger mt-3 d-none">
                        <span>!</span> This parcel has no soil test. Please add a soil test first.
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="sfp-btn sfp-btn-primary" data-next-step>Next</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="sfp-step-panel" data-step-panel="2">
            <div class="sfp-card">
                <div class="sfp-card-header">
                    <div>
                        <div class="sfp-card-title">Select crop & season</div>
                        <div class="sfp-page-subtitle">RDF values are loaded from the crop master data.</div>
                    </div>
                </div>
                <div class="sfp-card-body">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label for="crop_id" class="sfp-label">Crop <span class="required">*</span></label>
                            <select id="crop_id" name="crop_id" class="sfp-select" required>
                                <option value="">Select crop</option>
                                @foreach ($crops->groupBy('season') as $season => $seasonCrops)
                                    <optgroup label="{{ ucfirst($season) }}">
                                        @foreach ($seasonCrops as $crop)
                                            <option value="{{ $crop->id }}" @selected(old('crop_id') == $crop->id)>
                                                {{ $crop->name }}{{ $crop->variety ? ' - '.$crop->variety : '' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('crop_id') <div class="sfp-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-5">
                            <label for="season_year" class="sfp-label">Season year <span class="required">*</span></label>
                            <input id="season_year" name="season_year" class="sfp-input" value="{{ old('season_year', 'Kharif-'.now()->year) }}" placeholder="Kharif-{{ now()->year }}" required>
                            @error('season_year') <div class="sfp-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div id="cropDetails" class="sfp-detail-panel mt-3 d-none"></div>

                    <div class="mt-3">
                        <label for="notes" class="sfp-label">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="sfp-textarea">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="sfp-btn sfp-btn-outline" data-prev-step>Previous</button>
                        <button type="button" class="sfp-btn sfp-btn-primary" data-next-step>Next</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="sfp-step-panel" data-step-panel="3">
            <div class="sfp-card">
                <div class="sfp-card-header">
                    <div>
                        <div class="sfp-card-title">Review & generate</div>
                        <div class="sfp-page-subtitle">Confirm the selected parcel, crop, and season.</div>
                    </div>
                </div>
                <div class="sfp-card-body">
                    <div id="planReview" class="sfp-review-grid">
                        <div>
                            <span>Parcel</span>
                            <strong data-review-parcel>Not selected</strong>
                        </div>
                        <div>
                            <span>Crop</span>
                            <strong data-review-crop>Not selected</strong>
                        </div>
                        <div>
                            <span>Season</span>
                            <strong data-review-season>{{ old('season_year', 'Kharif-'.now()->year) }}</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="sfp-btn sfp-btn-outline" data-prev-step>Previous</button>
                        <button type="submit" class="sfp-btn sfp-btn-accent sfp-btn-lg">Generate Plan</button>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('planGeneratorForm');
            const panels = [...document.querySelectorAll('[data-step-panel]')];
            const indicators = [...document.querySelectorAll('[data-step-indicator]')];
            const progress = document.getElementById('planStepProgress');
            const parcelSelect = document.getElementById('parcel_id');
            const cropSelect = document.getElementById('crop_id');
            const seasonInput = document.getElementById('season_year');
            const parcelDetails = document.getElementById('parcelDetails');
            const parcelWarning = document.getElementById('parcelWarning');
            const cropDetails = document.getElementById('cropDetails');
            const parcelBaseUrl = @json(url('/api/parcel'));
            const cropBaseUrl = @json(url('/api/crop'));
            let currentStep = 1;
            let selectedParcel = null;
            let selectedCrop = null;

            const showStep = (step) => {
                currentStep = step;
                panels.forEach((panel) => panel.classList.toggle('is-active', Number(panel.dataset.stepPanel) === step));
                indicators.forEach((indicator) => indicator.classList.toggle('is-active', Number(indicator.dataset.stepIndicator) <= step));
                if (progress) {
                    progress.style.width = `${(step / panels.length) * 100}%`;
                }
                updateReview();
            };

            const fetchJson = async (url) => {
                const response = await fetch(url, { headers: { Accept: 'application/json' } });
                if (!response.ok) {
                    throw new Error('Unable to load details');
                }
                return response.json();
            };

            const renderParcel = (parcel) => {
                selectedParcel = parcel;
                parcelDetails.classList.remove('d-none');
                parcelWarning.classList.toggle('d-none', Boolean(parcel.last_soil_test_date));
                parcelDetails.innerHTML = `
                    <div class="sfp-detail-grid">
                        <div><span>Area</span><strong>${Number(parcel.area_acres || 0).toFixed(2)} acres</strong></div>
                        <div><span>Soil type</span><strong>${parcel.soil_type || 'Not set'}</strong></div>
                        <div><span>Location</span><strong>${parcel.district}, ${parcel.state}</strong></div>
                        <div><span>Last test</span><strong>${parcel.last_soil_test_date || 'No test yet'}</strong></div>
                    </div>
                    ${parcel.last_soil_test_date ? `
                        <div class="sfp-detail-npk">
                            <span>N: ${parcel.npk.nitrogen ?? '-'} kg/ha</span>
                            <span>P: ${parcel.npk.phosphorus ?? '-'} kg/ha</span>
                            <span>K: ${parcel.npk.potassium ?? '-'} kg/ha</span>
                        </div>
                    ` : ''}
                `;
                updateReview();
            };

            const renderCrop = (crop) => {
                selectedCrop = crop;
                cropDetails.classList.remove('d-none');
                cropDetails.innerHTML = `
                    <div class="sfp-detail-grid">
                        <div><span>Crop</span><strong>${crop.name}${crop.variety ? ' - ' + crop.variety : ''}</strong></div>
                        <div><span>Season</span><strong>${crop.season}</strong></div>
                        <div><span>Duration</span><strong>${crop.duration_days || '-'} days</strong></div>
                        <div><span>RDF N/P/K</span><strong>${crop.rdf_nitrogen}/${crop.rdf_phosphorus}/${crop.rdf_potassium} kg/ha</strong></div>
                    </div>
                `;
                updateReview();
            };

            const updateReview = () => {
                document.querySelector('[data-review-parcel]').textContent = selectedParcel?.name || 'Not selected';
                document.querySelector('[data-review-crop]').textContent = selectedCrop ? `${selectedCrop.name}${selectedCrop.variety ? ' - ' + selectedCrop.variety : ''}` : 'Not selected';
                document.querySelector('[data-review-season]').textContent = seasonInput.value || 'Not set';
            };

            const validateStep = () => {
                if (currentStep === 1) {
                    if (!parcelSelect.value) {
                        parcelSelect.classList.add('is-invalid');
                        return false;
                    }
                    if (selectedParcel && !selectedParcel.last_soil_test_date) {
                        parcelWarning.classList.remove('d-none');
                        return false;
                    }
                }

                if (currentStep === 2) {
                    let valid = true;
                    [cropSelect, seasonInput].forEach((field) => {
                        field.classList.toggle('is-invalid', !field.value);
                        valid = valid && Boolean(field.value);
                    });
                    return valid;
                }

                return true;
            };

            parcelSelect?.addEventListener('change', async () => {
                parcelSelect.classList.remove('is-invalid');
                selectedParcel = null;
                parcelDetails.classList.add('d-none');
                parcelWarning.classList.add('d-none');
                if (!parcelSelect.value) {
                    updateReview();
                    return;
                }
                try {
                    renderParcel(await fetchJson(`${parcelBaseUrl}/${parcelSelect.value}/details`));
                } catch (error) {
                    parcelWarning.textContent = 'Unable to load parcel details.';
                    parcelWarning.classList.remove('d-none');
                }
            });

            cropSelect?.addEventListener('change', async () => {
                cropSelect.classList.remove('is-invalid');
                selectedCrop = null;
                cropDetails.classList.add('d-none');
                if (!cropSelect.value) {
                    updateReview();
                    return;
                }
                try {
                    renderCrop(await fetchJson(`${cropBaseUrl}/${cropSelect.value}/details`));
                } catch (error) {
                    cropDetails.textContent = 'Unable to load crop details.';
                    cropDetails.classList.remove('d-none');
                }
            });

            seasonInput?.addEventListener('input', () => {
                seasonInput.classList.remove('is-invalid');
                updateReview();
            });

            document.querySelectorAll('[data-next-step]').forEach((button) => {
                button.addEventListener('click', () => {
                    if (validateStep()) {
                        showStep(Math.min(currentStep + 1, panels.length));
                    }
                });
            });

            document.querySelectorAll('[data-prev-step]').forEach((button) => {
                button.addEventListener('click', () => showStep(Math.max(currentStep - 1, 1)));
            });

            if (form?.dataset.oldParcel) {
                parcelSelect.dispatchEvent(new Event('change'));
            }
            if (form?.dataset.oldCrop) {
                cropSelect.dispatchEvent(new Event('change'));
            }
            updateReview();
        });
    </script>
@endpush
