@extends('layouts.app')

@section('title', 'Fertilizer Plan History')

@section('content')
    <div class="sfp-page-header">
        <div>
            <h1 class="sfp-page-title">Fertilizer Plan History</h1>
            <p class="sfp-page-subtitle">Season-wise record of fertilizer recommendations and spend.</p>
        </div>
        <a href="{{ route('plans.create') }}" class="sfp-btn sfp-btn-primary">Generate plan</a>
    </div>

    <div class="sfp-card mb-4">
        <div class="sfp-card-header">
            <div>
                <div class="sfp-card-title">Cost trend</div>
                <div class="sfp-page-subtitle">Total plan cost per season.</div>
            </div>
        </div>
        <div class="sfp-card-body sfp-chart-shell">
            @if ($historySeasonLabels->isNotEmpty())
                <canvas id="historyCostChart" height="130"></canvas>
            @else
                <div class="sfp-empty-state">
                    <strong>No history yet.</strong>
                    <span>Generated plans will appear here as a seasonal cost trend.</span>
                </div>
            @endif
        </div>
    </div>

    @forelse ($plansBySeason as $season => $plans)
        <section class="sfp-history-season">
            <div class="sfp-history-season-head">
                <h2>{{ $season }}</h2>
                <span>{{ $plans->count() }} {{ Str::plural('plan', $plans->count()) }}</span>
            </div>
            <div class="sfp-card">
                <div class="table-responsive">
                    <table class="sfp-table mb-0">
                        <thead>
                            <tr>
                                <th>Parcel</th>
                                <th>Crop</th>
                                <th>Date</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plans as $plan)
                                <tr>
                                    <td class="fw-bold">{{ $plan->landParcel->parcel_name }}</td>
                                    <td>{{ $plan->crop->name }}</td>
                                    <td>{{ $plan->created_at->format('d M Y') }}</td>
                                    <td>&#8377;{{ number_format((float) $plan->total_cost_inr, 2) }}</td>
                                    <td>
                                        <span class="sfp-badge {{ $plan->status === 'finalized' ? 'sfp-badge-success' : 'sfp-badge-warning' }}">
                                            {{ ucfirst($plan->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('plans.show', $plan) }}" class="sfp-btn sfp-btn-outline sfp-btn-sm">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @empty
        <div class="sfp-card">
            <div class="sfp-card-body text-center">
                <div class="sfp-empty-state">
                    <strong>No plan history yet.</strong>
                    <span>Create a fertilizer plan to begin tracking seasonal inputs.</span>
                    <a href="{{ route('plans.create') }}" class="sfp-btn sfp-btn-primary mt-2">Create first plan</a>
                </div>
            </div>
        </div>
    @endforelse
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const historyCanvas = document.getElementById('historyCostChart');

            if (!historyCanvas || typeof Chart === 'undefined') {
                return;
            }

            new Chart(historyCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($historySeasonLabels),
                    datasets: [{
                        label: 'Cost per season',
                        data: @json($historySeasonCosts),
                        borderColor: '#D4A017',
                        backgroundColor: 'rgba(212,160,23,0.16)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#A67C00',
                        pointRadius: 5,
                        tension: 0.35,
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
        });
    </script>
@endpush
