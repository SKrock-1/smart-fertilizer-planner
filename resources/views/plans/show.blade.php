@extends('layouts.app')

@section('title', $plan->crop->name.' - '.__('app.plan_details'))

@section('content')
    <section class="sfp-plan-header mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
            <div>
                <p class="text-uppercase fw-bold text-white-50 small mb-2">{{ __('app.fertilizer_recommendation') }}</p>
                <h1 class="sfp-plan-title">{{ $plan->crop->name }} - {{ __('app.plan_details') }}</h1>
                <p class="sfp-plan-meta mb-0">
                    {{ $plan->landParcel->parcel_name }} |
                    {{ number_format((float) $plan->landParcel->area_acres, 2) }} {{ __('app.acres') }} |
                    {{ $plan->season_year }}
                </p>
                <div class="sfp-plan-cost">INR {{ number_format((float) $computed['total_cost'], 2) }}</div>
            </div>
            <div class="sfp-print-hide d-flex align-items-start gap-2 flex-wrap">
                <button type="button" class="sfp-btn sfp-btn-outline bg-white" onclick="window.print()">Print</button>
                <a href="{{ route('plans.pdf', $plan) }}" class="sfp-btn sfp-btn-accent">{{ __('app.download_pdf') }}</a>
            </div>
        </div>
    </section>

    @if(isset($computed['subsidy_saved']) && $computed['subsidy_saved'] > 0)
        <div class="sfp-savings-banner mb-4">
            <div>
                <div class="sfp-savings-item-label">{{ __('app.total_cost') }}</div>
                <div class="sfp-savings-item-value">INR {{ number_format((float) $computed['total_cost'], 2) }}</div>
            </div>
            <div>
                <div class="sfp-savings-item-label">{{ __('app.market_cost') }}</div>
                <div class="sfp-savings-item-value">INR {{ number_format((float) $computed['total_unsubsidized_cost'], 2) }}</div>
            </div>
            <div>
                <div class="sfp-savings-item-label">{{ __('app.subsidy_saved') }}</div>
                <div class="sfp-savings-item-value">INR {{ number_format((float) $computed['subsidy_saved'], 2) }}</div>
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        @foreach (['N' => 'Nitrogen', 'P' => 'Phosphorus', 'K' => 'Potassium'] as $key => $label)
            <div class="col-md-4">
                <div class="sfp-card h-100">
                    <div class="sfp-card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="sfp-stat-label">{{ __('app.'.strtolower($label)) }}</div>
                                <div class="sfp-stat-value">{{ $key }}</div>
                            </div>
                            <span class="sfp-badge {{ $deficits[$key] > 0 ? 'sfp-badge-danger' : 'sfp-badge-success' }}">
                                {{ $deficits[$key] > 0 ? __('app.deficient') : __('app.sufficient') }}
                            </span>
                        </div>
                        <dl class="sfp-mini-dl mt-3">
                            <div><dt>{{ __('app.crop_demand') }}</dt><dd>{{ number_format($cropDemand[$key], 2) }} kg</dd></div>
                            <div><dt>{{ __('app.soil_supply') }}</dt><dd>{{ number_format($soilSupply[$key], 2) }} kg</dd></div>
                            <div><dt>{{ __('app.deficit') }}</dt><dd>{{ number_format($deficits[$key], 2) }} kg</dd></div>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="sfp-card mb-4 sfp-chart-print-hide">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">NPK balance</div>
                <div class="sfp-page-subtitle">{{ __('app.crop_demand') }} vs {{ __('app.soil_supply') }}</div>
            </div>
        </div>
        <div class="sfp-card-body sfp-chart-shell">
            <canvas id="npkChart" height="120"></canvas>
        </div>
    </div>

    <div class="sfp-card mb-4">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Soil health card</div>
                <div class="sfp-page-subtitle">Soil parameter analysis based on the linked soil test.</div>
            </div>
            <span class="sfp-badge sfp-badge-info">ICAR standards</span>
        </div>
        <div class="table-responsive">
            <table class="sfp-shc-table">
                <thead>
                    <tr>
                        <th>{{ __('app.parameter') }}</th>
                        <th>{{ __('app.your_value') }}</th>
                        <th>{{ __('app.ideal_range') }}</th>
                        <th>{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $ph = (float) $plan->soilTest->ph_level; @endphp
                    <tr>
                        <td class="fw-bold">{{ __('app.ph_level') }}</td>
                        <td>{{ number_format($ph, 1) }}</td>
                        <td>6.5 - 7.5</td>
                        <td>
                            @if($ph < 6.0)
                                <span class="sfp-status-dot status-bad">{{ __('app.acidic') }}</span>
                            @elseif($ph > 7.8)
                                <span class="sfp-status-dot status-warn">{{ __('app.alkaline') }}</span>
                            @else
                                <span class="sfp-status-dot status-good">{{ __('app.optimal') }}</span>
                            @endif
                        </td>
                    </tr>
                    @php $n = (float) $plan->soilTest->nitrogen_kg_ha; @endphp
                    <tr>
                        <td class="fw-bold">{{ __('app.nitrogen') }} ({{ __('app.kg_ha') }})</td>
                        <td>{{ number_format($n, 1) }}</td>
                        <td>280 - 560</td>
                        <td>
                            @if($n < 280)
                                <span class="sfp-status-dot status-bad">{{ __('app.low') }}</span>
                            @elseif($n <= 560)
                                <span class="sfp-status-dot status-warn">{{ __('app.medium') }}</span>
                            @else
                                <span class="sfp-status-dot status-good">{{ __('app.high') }}</span>
                            @endif
                        </td>
                    </tr>
                    @php $p = (float) $plan->soilTest->phosphorus_kg_ha; @endphp
                    <tr>
                        <td class="fw-bold">{{ __('app.phosphorus') }} ({{ __('app.kg_ha') }})</td>
                        <td>{{ number_format($p, 1) }}</td>
                        <td>10 - 25</td>
                        <td>
                            @if($p < 10)
                                <span class="sfp-status-dot status-bad">{{ __('app.low') }}</span>
                            @elseif($p <= 25)
                                <span class="sfp-status-dot status-warn">{{ __('app.medium') }}</span>
                            @else
                                <span class="sfp-status-dot status-good">{{ __('app.high') }}</span>
                            @endif
                        </td>
                    </tr>
                    @php $k = (float) $plan->soilTest->potassium_kg_ha; @endphp
                    <tr>
                        <td class="fw-bold">{{ __('app.potassium') }} ({{ __('app.kg_ha') }})</td>
                        <td>{{ number_format($k, 1) }}</td>
                        <td>110 - 280</td>
                        <td>
                            @if($k < 110)
                                <span class="sfp-status-dot status-bad">{{ __('app.low') }}</span>
                            @elseif($k <= 280)
                                <span class="sfp-status-dot status-warn">{{ __('app.medium') }}</span>
                            @else
                                <span class="sfp-status-dot status-good">{{ __('app.high') }}</span>
                            @endif
                        </td>
                    </tr>
                    @if($plan->soilTest->organic_carbon_pct !== null)
                        @php $oc = (float) $plan->soilTest->organic_carbon_pct; @endphp
                        <tr>
                            <td class="fw-bold">{{ __('app.organic_carbon') }} (%)</td>
                            <td>{{ number_format($oc, 2) }}</td>
                            <td>0.75 - 1.00+</td>
                            <td>
                                @if($oc < 0.5)
                                    <span class="sfp-status-dot status-bad">{{ __('app.low') }}</span>
                                @elseif($oc < 0.75)
                                    <span class="sfp-status-dot status-warn">{{ __('app.medium') }}</span>
                                @else
                                    <span class="sfp-status-dot status-good">{{ __('app.high') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if($plan->soilTest->zinc_ppm !== null)
                        @php $zn = (float) $plan->soilTest->zinc_ppm; @endphp
                        <tr>
                            <td class="fw-bold">{{ __('app.zinc') }} ({{ __('app.ppm') }})</td>
                            <td>{{ number_format($zn, 2) }}</td>
                            <td>&gt; 0.6</td>
                            <td>
                                @if($zn < 0.6)
                                    <span class="sfp-status-dot status-bad">{{ __('app.deficient') }}</span>
                                @else
                                    <span class="sfp-status-dot status-good">{{ __('app.sufficient') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if($plan->soilTest->sulfur_ppm !== null)
                        @php $s = (float) $plan->soilTest->sulfur_ppm; @endphp
                        <tr>
                            <td class="fw-bold">{{ __('app.sulfur') }} ({{ __('app.ppm') }})</td>
                            <td>{{ number_format($s, 2) }}</td>
                            <td>&gt; 10</td>
                            <td>
                                @if($s < 10)
                                    <span class="sfp-status-dot status-bad">{{ __('app.deficient') }}</span>
                                @else
                                    <span class="sfp-status-dot status-good">{{ __('app.sufficient') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="sfp-card mb-4">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">{{ __('app.fertilizer_recommendation') }}</div>
                <div class="sfp-page-subtitle">Optimized fertilizer combination for your parcel.</div>
            </div>
            <span class="sfp-badge sfp-badge-success">Cost optimized</span>
        </div>
        <div class="table-responsive">
            <table class="sfp-table mb-0">
                <thead>
                    <tr>
                        <th>{{ __('app.fertilizer') }}</th>
                        <th>{{ __('app.quantity') }}</th>
                        <th>{{ __('app.per_acre') }}</th>
                        <th>{{ __('app.stage') }}</th>
                        <th>{{ __('app.method') }}</th>
                        <th class="text-end">{{ __('app.cost') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plan->planItems as $item)
                        <tr>
                            <td class="fw-bold">{{ $item->fertilizer->name }}</td>
                            <td>{{ number_format((float) $item->quantity_kg, 2) }} kg</td>
                            <td>{{ number_format((float) $item->quantity_kg / max((float) $plan->landParcel->area_acres, 0.01), 2) }} kg</td>
                            <td>{{ $item->application_stage }}</td>
                            <td>{{ $item->application_method }}</td>
                            <td class="text-end">INR {{ number_format((float) $item->cost_inr, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold">
                        <td colspan="5">{{ __('app.total_cost') }}</td>
                        <td class="text-end">INR {{ number_format((float) $plan->total_cost_inr, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @if(!empty($computed['corrective_recommendations']))
        <div class="sfp-card mb-4">
            <div class="sfp-card-header">
                <div>
                    <div class="sfp-card-title">{{ __('app.corrective_measures') }}</div>
                    <div class="sfp-page-subtitle">Additional soil amendments based on soil health status.</div>
                </div>
            </div>
            <div class="sfp-card-body">
                <div class="sfp-corrective-grid">
                    @foreach($computed['corrective_recommendations'] as $correction)
                        <div class="sfp-corrective-item type-{{ $correction['type'] }}">
                            <div class="sfp-corrective-head">
                                <span class="sfp-corrective-name">{{ $correction['name'] }}</span>
                                @if($correction['qty_kg'] > 0)
                                    <span class="sfp-corrective-qty">
                                        {{ number_format($correction['qty_kg'], 1) }} kg | INR {{ number_format($correction['cost_inr'], 2) }}
                                    </span>
                                @endif
                            </div>
                            <div class="sfp-corrective-reason">{{ $correction['reason'] }}</div>
                            <div class="sfp-corrective-instructions">{{ $correction['instructions'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="sfp-card h-100">
                <div class="sfp-card-header">
                    <div class="sfp-card-title">{{ __('app.application_schedule') }}</div>
                </div>
                <div class="sfp-card-body">
                    <div class="sfp-timeline-v2">
                        @foreach($computed['recommendations'] as $rec)
                            <div class="sfp-timeline-v2-item">
                                <div class="sfp-timeline-v2-stage">{{ $rec['stage'] }}</div>
                                <div class="sfp-timeline-v2-detail">{{ $rec['fertilizer_name'] }} - {{ number_format($rec['qty_kg'], 2) }} kg</div>
                                <div class="sfp-timeline-v2-method">{{ $rec['method'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="sfp-card h-100">
                <div class="sfp-card-header">
                    <div class="sfp-card-title">Important notes</div>
                </div>
                <div class="sfp-card-body">
                    <ul class="sfp-check-list">
                        <li>Check soil pH before application.</li>
                        <li>Apply fertilizers when soil has adequate moisture.</li>
                        <li>Do not mix Zinc Sulphate directly with DAP.</li>
                        <li>Avoid spraying in high wind above 20 km/h.</li>
                        <li>Keep records of application for next season.</li>
                    </ul>
                    <form method="POST" action="{{ route('plans.destroy', $plan) }}" class="mt-4 sfp-print-hide">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="sfp-btn sfp-btn-danger sfp-delete-btn">{{ __('app.delete_plan') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const npkCanvas = document.getElementById('npkChart');
            if (!npkCanvas || typeof Chart === 'undefined') return;

            new Chart(npkCanvas, {
                type: 'bar',
                data: {
                    labels: ['Nitrogen (N)', 'Phosphorus (P)', 'Potassium (K)'],
                    datasets: [
                        {
                            label: '{{ __('app.crop_demand') }}',
                            data: @json(array_values($cropDemand)),
                            backgroundColor: 'rgba(45,106,79,0.7)',
                            borderRadius: 6
                        },
                        {
                            label: '{{ __('app.soil_supply') }}',
                            data: @json(array_values($soilSupply)),
                            backgroundColor: 'rgba(82,183,136,0.7)',
                            borderRadius: 6
                        },
                        {
                            label: '{{ __('app.deficit') }}',
                            data: @json(array_values($deficits)),
                            backgroundColor: 'rgba(212,160,23,0.7)',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { font: { family: 'Inter', size: 12 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.dataset.label + ': ' + Number(ctx.raw || 0).toFixed(1) + ' kg'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => value + ' kg',
                                font: { family: 'Inter', size: 11 }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
