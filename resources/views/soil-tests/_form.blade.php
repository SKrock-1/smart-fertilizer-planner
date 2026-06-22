@csrf

<div class="sfp-form-stack">
    <section class="sfp-card">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Basic soil parameters</div>
                <div class="sfp-page-subtitle">Test date and pH reading from the lab report.</div>
            </div>
        </div>
        <div class="sfp-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="test_date" class="sfp-label">Test date <span class="required">*</span></label>
                    <input id="test_date" name="test_date" type="date" class="sfp-input" value="{{ old('test_date', optional($soilTest->test_date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    @error('test_date') <div class="sfp-error">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="ph_level" class="sfp-label">pH level <span class="required">*</span></label>
                    <input id="ph_level" name="ph_level" type="number" step="0.01" min="0" max="14" class="sfp-input" value="{{ old('ph_level', $soilTest->ph_level) }}" required>
                    <div id="ph-indicator" class="sfp-ph-indicator neutral">Enter pH to classify soil reaction</div>
                    @error('ph_level') <div class="sfp-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </section>

    <section class="sfp-card sfp-card-emphasis">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Macronutrient analysis (NPK)</div>
                <div class="sfp-page-subtitle">Available nutrients measured in kg/ha.</div>
            </div>
        </div>
        <div class="sfp-card-body">
            <div class="row g-3">
                @foreach ([
                    'nitrogen' => ['field' => 'nitrogen_kg_ha', 'label' => 'Available Nitrogen', 'range' => 'Typical range: 100-600 kg/ha'],
                    'phosphorus' => ['field' => 'phosphorus_kg_ha', 'label' => 'Available Phosphorus', 'range' => 'Typical range: 5-100 kg/ha'],
                    'potassium' => ['field' => 'potassium_kg_ha', 'label' => 'Available Potassium', 'range' => 'Typical range: 50-400 kg/ha'],
                ] as $nutrient => $config)
                    <div class="col-lg-4">
                        <label for="{{ $config['field'] }}" class="sfp-label">{{ $config['label'] }} <span class="required">*</span></label>
                        <input
                            id="{{ $config['field'] }}"
                            name="{{ $config['field'] }}"
                            type="number"
                            step="0.01"
                            min="0"
                            class="sfp-input sfp-npk-input"
                            value="{{ old($config['field'], $soilTest->{$config['field']}) }}"
                            data-nutrient="{{ $nutrient }}"
                            required
                        >
                        <div class="sfp-help-text">{{ $config['range'] }}</div>
                        <div class="sfp-npk-status">
                            <span class="sfp-badge sfp-badge-warning" data-npk-status="{{ $nutrient }}">Waiting</span>
                            <div class="sfp-soil-bar">
                                <div class="sfp-soil-fill" data-npk-fill="{{ $nutrient }}" style="width: 0%"></div>
                            </div>
                        </div>
                        @error($config['field']) <div class="sfp-error">{{ $message }}</div> @enderror
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="sfp-card">
        <button class="sfp-card-header sfp-collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#micronutrientsPanel" aria-expanded="false" aria-controls="micronutrientsPanel">
            <span>
                <span class="sfp-card-title d-block">Optional micronutrients</span>
                <span class="sfp-page-subtitle">Organic carbon, zinc, and sulfur values if available.</span>
            </span>
            <span>Show</span>
        </button>
        <div id="micronutrientsPanel" class="collapse">
            <div class="sfp-card-body">
                <div class="row g-3">
                    @foreach ([
                        'organic_carbon_pct' => 'Organic Carbon %',
                        'zinc_ppm' => 'Zinc (ppm)',
                        'sulfur_ppm' => 'Sulfur (ppm)',
                    ] as $field => $label)
                        <div class="col-md-4">
                            <label for="{{ $field }}" class="sfp-label">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="number" step="0.01" min="0" class="sfp-input" value="{{ old($field, $soilTest->{$field}) }}">
                            @error($field) <div class="sfp-error">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="sfp-card">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Upload lab report</div>
                <div class="sfp-page-subtitle">PDF or image report, up to 4 MB.</div>
            </div>
        </div>
        <div class="sfp-card-body">
            <input id="lab_report" name="lab_report" type="file" class="sfp-input" accept=".pdf,image/*">
            @error('lab_report') <div class="sfp-error">{{ $message }}</div> @enderror
        </div>
    </section>

    <div class="d-flex align-items-center gap-3 sfp-print-hide">
        <button type="submit" class="sfp-btn sfp-btn-primary">{{ $buttonText }}</button>
        <a href="{{ route('parcels.show', $parcel) }}" class="sfp-btn sfp-btn-outline">Cancel</a>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const phInput = document.getElementById('ph_level');
            const phIndicator = document.getElementById('ph-indicator');
            const npkThresholds = {
                nitrogen: { low: 140, medium: 280 },
                phosphorus: { low: 11, medium: 22 },
                potassium: { low: 108, medium: 280 }
            };

            const updatePhIndicator = () => {
                if (!phInput || !phIndicator) {
                    return;
                }

                const ph = parseFloat(phInput.value);

                if (Number.isNaN(ph)) {
                    phIndicator.textContent = 'Enter pH to classify soil reaction';
                    phIndicator.className = 'sfp-ph-indicator neutral';
                    return;
                }

                if (ph < 6.5) {
                    phIndicator.textContent = 'Acidic - consider liming';
                    phIndicator.className = 'sfp-ph-indicator acidic';
                } else if (ph <= 7.5) {
                    phIndicator.textContent = 'Neutral - ideal for most crops';
                    phIndicator.className = 'sfp-ph-indicator neutral';
                } else {
                    phIndicator.textContent = 'Alkaline - may limit micronutrient availability';
                    phIndicator.className = 'sfp-ph-indicator alkaline';
                }
            };

            const updateNpkStatus = (input) => {
                const nutrient = input.dataset.nutrient;
                const thresholds = npkThresholds[nutrient];
                const badge = document.querySelector(`[data-npk-status="${nutrient}"]`);
                const fill = document.querySelector(`[data-npk-fill="${nutrient}"]`);
                const value = parseFloat(input.value);

                if (!thresholds || !badge || !fill || Number.isNaN(value)) {
                    if (badge) {
                        badge.textContent = 'Waiting';
                        badge.className = 'sfp-badge sfp-badge-warning';
                    }
                    if (fill) {
                        fill.style.width = '0%';
                    }
                    return;
                }

                let label = 'High';
                let badgeClass = 'sfp-badge-success';
                let width = 100;

                if (value < thresholds.low) {
                    label = 'Low';
                    badgeClass = 'sfp-badge-danger';
                    width = 33;
                } else if (value < thresholds.medium) {
                    label = 'Medium';
                    badgeClass = 'sfp-badge-warning';
                    width = 66;
                }

                badge.textContent = label;
                badge.className = `sfp-badge ${badgeClass}`;
                fill.style.width = `${width}%`;
            };

            phInput?.addEventListener('input', updatePhIndicator);
            document.querySelectorAll('.sfp-npk-input').forEach((input) => {
                input.addEventListener('input', () => updateNpkStatus(input));
                updateNpkStatus(input);
            });
            updatePhIndicator();
        });
    </script>
@endpush
