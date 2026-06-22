<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fertilizer Plan {{ $plan->id }}</title>
    <style>
        @page { margin: 34px 34px 54px; }
        body {
            color: #172019;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.45;
        }
        h1, h2, h3, p { margin: 0; }
        h1 { color: #1B4332; font-size: 24px; margin-bottom: 4px; }
        h2 { color: #1B4332; font-size: 15px; margin: 22px 0 8px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #D8E8D2; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #EDF2E9; color: #1B4332; font-size: 10px; text-transform: uppercase; }
        .header { border-bottom: 3px solid #2D6A4F; margin-bottom: 18px; padding-bottom: 14px; }
        .brand { color: #2D6A4F; font-size: 13px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
        .muted { color: #5c6f58; }
        .summary { background: #F7F9F5; border: 1px solid #D8E8D2; margin-top: 14px; padding: 12px; }
        .summary-grid { width: 100%; }
        .summary-grid td { border: 0; padding: 3px 8px 3px 0; }
        .cost { color: #A67C00; font-size: 18px; font-weight: bold; }
        .right { text-align: right; }
        .total-row td { background: #F7F9F5; font-weight: bold; }
        .footer {
            bottom: -34px;
            color: #5c6f58;
            font-size: 10px;
            left: 0;
            position: fixed;
            right: 0;
            text-align: center;
        }
        .notes { margin-top: 12px; padding-left: 18px; }
    </style>
</head>
<body>
    <div class="header">
        <p class="brand">FertiPlan - Smart Fertilizer Planner</p>
        <h1>{{ $plan->crop->name }} Fertilizer Plan</h1>
        <p class="muted">Generated on {{ now()->format('d M Y') }}</p>
    </div>

    <div class="summary">
        <table class="summary-grid">
            <tr>
                <td><strong>Farmer:</strong> {{ $plan->landParcel->user->name }}</td>
                <td><strong>Parcel:</strong> {{ $plan->landParcel->parcel_name }}</td>
            </tr>
            <tr>
                <td><strong>Area:</strong> {{ number_format((float) $plan->landParcel->area_acres, 2) }} acres ({{ number_format($areaHa, 4) }} ha)</td>
                <td><strong>Season:</strong> {{ $plan->season_year }}</td>
            </tr>
            <tr>
                <td><strong>Crop:</strong> {{ $plan->crop->name }}{{ $plan->crop->variety ? ' - '.$plan->crop->variety : '' }}</td>
                <td><strong>Soil test:</strong> {{ $plan->soilTest->test_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Total estimated cost:</strong> <span class="cost">INR {{ number_format((float) $plan->total_cost_inr, 2) }}</span></td>
            </tr>
        </table>
    </div>

    <h2>NPK Deficit Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Nutrient</th>
                <th>Crop Demand (kg)</th>
                <th>Soil Supply (kg)</th>
                <th>Net Deficit (kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach (['N' => 'Nitrogen', 'P' => 'Phosphorus', 'K' => 'Potassium'] as $key => $label)
                <tr>
                    <td><strong>{{ $label }} ({{ $key }})</strong></td>
                    <td>{{ number_format($cropDemand[$key], 2) }}</td>
                    <td>{{ number_format($soilSupply[$key], 2) }}</td>
                    <td>{{ number_format($deficits[$key] ?? max(0, $cropDemand[$key] - $soilSupply[$key]), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Fertilizer Recommendations</h2>
    <table>
        <thead>
            <tr>
                <th>Fertilizer</th>
                <th>Quantity (kg)</th>
                <th>Per Acre</th>
                <th>Application Stage</th>
                <th>Method</th>
                <th class="right">Cost (INR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plan->planItems as $item)
                <tr>
                    <td><strong>{{ $item->fertilizer->name }}</strong></td>
                    <td>{{ number_format((float) $item->quantity_kg, 2) }}</td>
                    <td>{{ number_format((float) $item->quantity_kg / max((float) $plan->landParcel->area_acres, 0.01), 2) }}</td>
                    <td>{{ $item->application_stage }}</td>
                    <td>{{ $item->application_method }}</td>
                    <td class="right">{{ number_format((float) $item->cost_inr, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5">Total Cost</td>
                <td class="right">{{ number_format((float) $plan->total_cost_inr, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Important Notes</h2>
    <ul class="notes">
        <li>Apply fertilizers when the soil has adequate moisture.</li>
        <li>Keep application records for next season planning.</li>
        <li>Recheck soil pH and micronutrients when crop response is poor.</li>
    </ul>

    <div class="footer">Generated by FertiPlan - Smart Fertilizer Planner</div>
</body>
</html>
