@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')
    <div class="sfp-page-header">
        <div>
            <p class="sfp-page-subtitle text-uppercase fw-bold mb-1">{{ __('app.app_name') }}</p>
            <h1 class="sfp-page-title">{{ __('app.welcome_back') }}, {{ Auth::user()->name }}</h1>
            <p class="sfp-page-subtitle">{{ ucfirst(Auth::user()->role) }} - {{ now()->translatedFormat('d M Y') }}</p>
        </div>
        <div class="sfp-page-actions">
            <a href="{{ route('parcels.create') }}" class="sfp-btn sfp-btn-accent">{{ __('app.my_parcels') }} +</a>
            <a href="{{ route('plans.create') }}" class="sfp-btn sfp-btn-outline">{{ __('app.create_plan') }}</a>
        </div>
    </div>

    <section class="sfp-plan-header mb-4">
        <div class="row align-items-end g-4">
            <div class="col-lg-8">
                <p class="text-uppercase fw-bold text-white-50 small mb-2">{{ __('app.nutrient_overview') }}</p>
                <h2 class="sfp-plan-title mb-3">Soil tests, crop RDFs, and fertilizer economics in one workspace.</h2>
                <p class="sfp-plan-meta mb-0">Use this dashboard to review parcel activity, weather signals, plan spend, and master data.</p>
            </div>
            <div class="col-lg-4">
                <div class="sfp-kv-item bg-white border-0">
                    <span class="sfp-kv-label">This season spend</span>
                    <span class="sfp-kv-value">INR {{ number_format((float) ($stats['total_cost_this_season'] ?? 0), 2) }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="sfp-stat-grid mb-4">
        <div class="sfp-stat-card">
            <span class="sfp-stat-icon">LD</span>
            <div class="sfp-stat-label">{{ __('app.total_parcels') }}</div>
            <div class="sfp-stat-value">{{ number_format($stats['land_parcels'] ?? 0) }}</div>
            <div class="sfp-stat-sub">Registered fields</div>
        </div>
        <div class="sfp-stat-card">
            <span class="sfp-stat-icon">ST</span>
            <div class="sfp-stat-label">{{ __('app.soil_tests') }}</div>
            <div class="sfp-stat-value">{{ number_format($stats['soil_tests'] ?? 0) }}</div>
            <div class="sfp-stat-sub">Lab nutrient records</div>
        </div>
        <div class="sfp-stat-card">
            <span class="sfp-stat-icon">CR</span>
            <div class="sfp-stat-label">Crop RDFs</div>
            <div class="sfp-stat-value">{{ number_format($stats['crops'] ?? 0) }}</div>
            <div class="sfp-stat-sub">Active crop masters</div>
        </div>
        <div class="sfp-stat-card">
            <span class="sfp-stat-icon">FT</span>
            <div class="sfp-stat-label">{{ __('app.fertilizer') }}</div>
            <div class="sfp-stat-value">{{ number_format($stats['fertilizers'] ?? 0) }}</div>
            <div class="sfp-stat-sub">Input products</div>
        </div>
        <div class="sfp-stat-card accent">
            <span class="sfp-stat-icon">PL</span>
            <div class="sfp-stat-label">{{ __('app.active_plans') }}</div>
            <div class="sfp-stat-value">{{ number_format($stats['plans'] ?? 0) }}</div>
            <div class="sfp-stat-sub">Seasonal recommendations</div>
        </div>
    </div>

    <div class="sfp-weather-card mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-2">
            <div>
                <div class="sfp-weather-title">
                    {{ __('app.weather_advisory') }}
                    <span id="weather-location-label" class="badge bg-light text-dark ms-2" style="font-size: 0.65rem; font-family: 'Inter', sans-serif;">New Delhi</span>
                </div>
                <div class="sfp-weather-subtitle mb-0">{{ __('app.weather_desc') }}</div>
            </div>
            <button id="btn-locate-me" class="sfp-btn sfp-btn-outline bg-white" type="button">
                <span id="locate-icon">GPS</span>
                <span id="locate-text">Use My Location</span>
            </button>
        </div>
        <div class="sfp-weather-grid mt-4">
            <div class="sfp-weather-metric">
                <span class="sfp-weather-metric-icon">W</span>
                <span class="sfp-weather-metric-value" id="weather-wind">-- km/h</span>
                <span class="sfp-weather-metric-label">{{ __('app.wind_speed') }}</span>
            </div>
            <div class="sfp-weather-metric">
                <span class="sfp-weather-metric-icon">T</span>
                <span class="sfp-weather-metric-value" id="weather-temp">-- C</span>
                <span class="sfp-weather-metric-label">{{ __('app.temperature') }}</span>
            </div>
            <div class="sfp-weather-metric">
                <span class="sfp-weather-metric-icon">H</span>
                <span class="sfp-weather-metric-value" id="weather-humidity">-- %</span>
                <span class="sfp-weather-metric-label">{{ __('app.humidity') }}</span>
            </div>
            <div class="sfp-weather-metric">
                <span class="sfp-weather-metric-icon">R</span>
                <span class="sfp-weather-metric-value" id="weather-rain">-- %</span>
                <span class="sfp-weather-metric-label">{{ __('app.rain_forecast') }}</span>
            </div>
        </div>
        <div class="sfp-weather-status mt-3" id="weather-status">Loading...</div>
    </div>

    <div class="sfp-card mb-4">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Fertilizer usage over seasons</div>
                <div class="sfp-page-subtitle">Total fertilizer spend from your generated plans.</div>
            </div>
            <span class="sfp-badge sfp-badge-success">Season trend</span>
        </div>
        <div class="sfp-card-body sfp-chart-shell">
            @if ($seasonLabels->isNotEmpty())
                <canvas id="usageChart" height="120"></canvas>
            @else
                <div class="sfp-empty-state">
                    <strong>No seasonal plan data yet.</strong>
                    <span>Generate fertilizer plans to see fertilizer cost trends over time.</span>
                </div>
            @endif
        </div>
    </div>

    <div class="sfp-card mb-4">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Recent plans</div>
                <div class="sfp-page-subtitle">Your latest generated fertilizer schedules.</div>
            </div>
            <a href="{{ route('plans.index') }}" class="sfp-btn sfp-btn-outline sfp-btn-sm">View all</a>
        </div>
        <div class="table-responsive">
            <table class="sfp-table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Parcel</th>
                        <th>Crop</th>
                        <th>Season Year</th>
                        <th class="text-end">Total Cost</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentPlans as $plan)
                        <tr>
                            <td>{{ $plan->created_at->format('d M Y') }}</td>
                            <td class="fw-bold">{{ $plan->landParcel->parcel_name ?? 'N/A' }}</td>
                            <td><span class="sfp-badge sfp-badge-success">{{ $plan->crop->name ?? 'N/A' }}</span></td>
                            <td>{{ $plan->season_year }}</td>
                            <td class="text-end fw-bold">INR {{ number_format((float) $plan->total_cost_inr, 2) }}</td>
                            <td>
                                <div class="sfp-table-actions">
                                    <a href="{{ route('plans.show', $plan) }}" class="sfp-btn sfp-btn-outline sfp-btn-sm">View</a>
                                    <a href="{{ route('plans.pdf', $plan) }}" class="sfp-btn sfp-btn-primary sfp-btn-sm">PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No plans generated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="sfp-card h-100">
                <div class="sfp-card-header">
                    <div>
                        <div class="sfp-card-title">Crop RDF comparison</div>
                        <div class="sfp-page-subtitle">Recommended NPK values seeded for plan calculations.</div>
                    </div>
                    <span class="sfp-badge sfp-badge-info">{{ __('app.kg_ha') }}</span>
                </div>
                <div class="sfp-card-body sfp-chart-shell">
                    @if ($cropNutrients->isNotEmpty())
                        <canvas id="cropRdfChart" height="150"></canvas>
                    @else
                        <div class="sfp-empty-state">
                            <strong>No crop RDF data yet.</strong>
                            <span>Run the database seeder or add crops from the admin panel to render this chart.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="sfp-card h-100">
                <div class="sfp-card-header">
                    <div>
                        <div class="sfp-card-title">Fertilizer master</div>
                        <div class="sfp-page-subtitle">Active fertilizers: subsidized vs market prices.</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="sfp-table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>NPK</th>
                                <th class="text-end">Subsidized</th>
                                <th class="text-end">Market</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fertilizers as $fertilizer)
                                <tr>
                                    <td class="fw-bold">{{ $fertilizer->name }}</td>
                                    <td>{{ $fertilizer->nitrogen_pct }} / {{ $fertilizer->phosphorus_pct }} / {{ $fertilizer->potassium_pct }}</td>
                                    <td class="text-end">INR {{ number_format((float) $fertilizer->price_per_kg, 2) }}</td>
                                    <td class="text-end text-muted">INR {{ number_format((float) ($fertilizer->unsubsidized_price_per_kg ?: 0), 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No fertilizers available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const windEl = document.getElementById('weather-wind');
            const tempEl = document.getElementById('weather-temp');
            const humidEl = document.getElementById('weather-humidity');
            const rainEl = document.getElementById('weather-rain');
            const statusEl = document.getElementById('weather-status');
            const locLabel = document.getElementById('weather-location-label');
            const btnLocate = document.getElementById('btn-locate-me');
            const btnLocateText = document.getElementById('locate-text');

            function fetchWeather(lat, lon, locationName) {
                if (!windEl || !statusEl || !locLabel) return;

                statusEl.textContent = 'Loading...';
                statusEl.style.background = 'rgba(255,255,255,0.1)';
                locLabel.textContent = locationName;

                fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,precipitation,wind_speed_10m`)
                    .then((response) => response.json())
                    .then((data) => {
                        const current = data.current || {};
                        const wind = Math.round(current.wind_speed_10m || 0);
                        const temp = Math.round(current.temperature_2m || 0);
                        const humidity = Math.round(current.relative_humidity_2m || 0);
                        const rain = Number(current.precipitation || 0);
                        const rainChance = rain > 0 ? Math.min(100, Math.round(rain * 20)) : 0;

                        windEl.textContent = wind + ' km/h';
                        tempEl.textContent = temp + ' C';
                        humidEl.textContent = humidity + '%';
                        rainEl.textContent = rainChance + '%';

                        if (wind > 20) {
                            statusEl.textContent = '{{ __('app.caution_wind') }}';
                            statusEl.style.background = 'rgba(230, 126, 34, 0.3)';
                        } else if (rain > 2) {
                            statusEl.textContent = '{{ __('app.caution_rain') }}';
                            statusEl.style.background = 'rgba(41, 128, 185, 0.3)';
                        } else {
                            statusEl.textContent = '{{ __('app.safe_to_apply') }}';
                            statusEl.style.background = 'rgba(255,255,255,0.15)';
                        }
                    })
                    .catch(() => {
                        windEl.textContent = '-- km/h';
                        tempEl.textContent = '-- C';
                        humidEl.textContent = '-- %';
                        rainEl.textContent = '-- %';
                        statusEl.textContent = 'Weather unavailable';
                    });
            }

            fetchWeather(28.6139, 77.2090, 'New Delhi');

            if (btnLocate) {
                btnLocate.addEventListener('click', () => {
                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser.');
                        return;
                    }

                    btnLocateText.textContent = 'Locating...';
                    btnLocate.disabled = true;

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            fetchWeather(position.coords.latitude.toFixed(4), position.coords.longitude.toFixed(4), 'My Location');
                            btnLocateText.textContent = 'Use My Location';
                            btnLocate.disabled = false;
                        },
                        () => {
                            alert('Unable to retrieve your location. Please enable location permission.');
                            btnLocateText.textContent = 'Use My Location';
                            btnLocate.disabled = false;
                        }
                    );
                });
            }

            const usageCanvas = document.getElementById('usageChart');
            if (usageCanvas && typeof Chart !== 'undefined') {
                new Chart(usageCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($seasonLabels),
                        datasets: [{
                            label: 'Total Fertilizer Cost (INR)',
                            data: @json($seasonCosts),
                            borderColor: '#2D6A4F',
                            backgroundColor: 'rgba(45,106,79,0.08)',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#2D6A4F',
                            pointRadius: 5,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => 'INR ' + Number(ctx.raw || 0).toLocaleString('en-IN')
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => 'INR ' + Number(value).toLocaleString('en-IN'),
                                    font: { family: 'Inter', size: 11 }
                                },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            x: {
                                ticks: { font: { family: 'Inter', size: 11 } },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            const chartCanvas = document.getElementById('cropRdfChart');
            if (chartCanvas && typeof Chart !== 'undefined') {
                new Chart(chartCanvas, {
                    type: 'bar',
                    data: {
                        labels: @json($cropChartData['labels']),
                        datasets: [
                            { label: 'Nitrogen', data: @json($cropChartData['nitrogen']), backgroundColor: '#2D6A4F', borderRadius: 4 },
                            { label: 'Phosphorus', data: @json($cropChartData['phosphorus']), backgroundColor: '#D4A017', borderRadius: 4 },
                            { label: 'Potassium', data: @json($cropChartData['potassium']), backgroundColor: '#52B788', borderRadius: 4 }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'kg/ha' } }
                        },
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        });
    </script>
@endpush
