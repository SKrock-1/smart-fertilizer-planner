<?php

namespace App\Services;

use App\Models\Crop;
use App\Models\Fertilizer;
use App\Models\SoilTest;

class FertilizerRecommendationService
{
    public function compute(SoilTest $soilTest, Crop $crop, float $areaAcres): array
    {
        // 1. Convert area: 1 acre = 0.4047 hectares
        $areaHa = $areaAcres * 0.4047;

        // 2. Total RDF needed for the entire parcel (kg)
        $totalRDF_N = $crop->rdf_nitrogen * $areaHa;
        $totalRDF_P = $crop->rdf_phosphorus * $areaHa;
        $totalRDF_K = $crop->rdf_potassium * $areaHa;

        // 3. Nutrients already in soil for the parcel (kg)
        // Use standard availability factors for soil nutrients (ICAR standard)
        $soilN = $soilTest->nitrogen_kg_ha * $areaHa * 0.30;
        $soilP = $soilTest->phosphorus_kg_ha * $areaHa * 0.25;
        $soilK = $soilTest->potassium_kg_ha * $areaHa * 0.10;

        // 4. Net nutrient deficit (kg) - floor at 0
        $deficitN = max(0, $totalRDF_N - $soilN);
        $deficitP = max(0, $totalRDF_P - $soilP);
        $deficitK = max(0, $totalRDF_K - $soilK);

        // 5. Fetch active fertilizers from database
        $activeFertilizers = Fertilizer::where('is_active', true)->get();

        // 6. Run the Least-Cost Optimizer for NPK
        $optResult = $this->optimizeNPK($deficitN, $deficitP, $deficitK, $activeFertilizers, $areaAcres);
        $recommendations = $optResult['items'];
        $baseTotalCost = $optResult['total_cost'];
        $details = $optResult['details'];

        // 7. Extended Agronomic & Corrective Recommendations
        $correctiveRecommendations = [];
        $extraCost = 0.0;
        $extraUnsubsidizedCost = 0.0;

        // pH correction
        $ph = (float) $soilTest->ph_level;
        if ($ph < 6.0) {
            // Acidic soil: Recommend Agricultural Lime
            $limeQtyHa = 1000; // default 1 tonne/ha
            if ($ph < 5.0) $limeQtyHa = 2000;
            elseif ($ph > 5.5) $limeQtyHa = 500;
            $limeQty = round($limeQtyHa * $areaHa, 2);
            $limeCost = round($limeQty * 4.0, 2); // Assume average Lime cost is ₹4/kg
            $extraCost += $limeCost;
            $extraUnsubsidizedCost += $limeCost; // Lime is not heavily subsidized
            $correctiveRecommendations[] = [
                'type' => 'soil_conditioner',
                'name' => 'Agricultural Lime',
                'qty_kg' => $limeQty,
                'cost_inr' => $limeCost,
                'reason' => 'Your soil is acidic (pH ' . $ph . '). Lime application neutralizes acidity and improves nutrient availability.',
                'instructions' => 'Broadcast uniformly and mix into the top 15cm of soil 2-3 weeks before sowing.'
            ];
        } elseif ($ph > 7.8) {
            // Alkaline soil: Recommend Gypsum
            $gypsumQtyHa = 1500; // default 1.5 tonnes/ha
            if ($ph > 8.5) $gypsumQtyHa = 3000;
            $gypsumQty = round($gypsumQtyHa * $areaHa, 2);
            $gypsumCost = round($gypsumQty * 5.0, 2); // Gypsum cost ₹5/kg
            $extraCost += $gypsumCost;
            $extraUnsubsidizedCost += $gypsumCost;
            $correctiveRecommendations[] = [
                'type' => 'soil_conditioner',
                'name' => 'Agricultural Gypsum',
                'qty_kg' => $gypsumQty,
                'cost_inr' => $gypsumCost,
                'reason' => 'Your soil is alkaline/sodic (pH ' . $ph . '). Gypsum displaces sodium and improves water infiltration.',
                'instructions' => 'Apply on soil surface, mix lightly, and flood the field with water to leach out displaced salts.'
            ];
        }

        // Organic Carbon correction
        $oc = $soilTest->organic_carbon_pct !== null ? (float) $soilTest->organic_carbon_pct : null;
        if ($oc !== null && $oc < 0.75) {
            $fymQtyHa = $oc < 0.5 ? 10000 : 5000; // 10 or 5 tonnes/ha
            $fymQty = round($fymQtyHa * $areaHa, 2);
            $fymCost = round($fymQty * 1.50, 2); // FYM cost ₹1.5/kg
            $extraCost += $fymCost;
            $extraUnsubsidizedCost += $fymCost;
            $correctiveRecommendations[] = [
                'type' => 'organic_manure',
                'name' => 'Farm Yard Manure (FYM) / Compost',
                'qty_kg' => $fymQty,
                'cost_inr' => $fymCost,
                'reason' => 'Soil Organic Carbon is ' . ($oc < 0.5 ? 'Low' : 'Medium') . ' (' . $oc . '%). FYM builds microbial activity and water retention.',
                'instructions' => 'Incorporate into the soil during final land preparation (ploughing).'
            ];
        }

        // Zinc correction (micronutrient)
        $zinc = $soilTest->zinc_ppm !== null ? (float) $soilTest->zinc_ppm : null;
        $zincSulphate = $activeFertilizers->firstWhere('name', 'Zinc Sulphate');
        if ($zinc !== null && $zinc < 0.6 && $zincSulphate) {
            $zincQtyHa = 25; // 25 kg/ha standard dose
            $zincQty = round($zincQtyHa * $areaHa, 2);
            $zincCost = round($zincQty * (float) $zincSulphate->price_per_kg, 2);
            $zincUnsubsidizedCost = round($zincQty * (float) $zincSulphate->unsubsidized_price_per_kg, 2);
            
            // Add as active fertilizer recommendation
            $recommendations[] = [
                'fertilizer_name' => 'Zinc Sulphate',
                'fertilizer_id' => $zincSulphate->id,
                'qty_kg' => $zincQty,
                'qty_per_acre' => round($zincQty / $areaAcres, 2),
                'stage' => 'Base Dose (At Sowing)',
                'method' => 'Soil application mixed with dry soil',
                'cost_inr' => $zincCost,
            ];
            $baseTotalCost += $zincCost;
            
            $correctiveRecommendations[] = [
                'type' => 'micronutrient',
                'name' => 'Zinc Sulphate',
                'qty_kg' => $zincQty,
                'cost_inr' => $zincCost,
                'reason' => 'Zinc level is deficient (' . $zinc . ' ppm, threshold < 0.6 ppm). Zinc is crucial for enzymatic activities and plant height.',
                'instructions' => 'Apply at sowing. Do NOT mix directly with DAP/phosphatic fertilizers as they form insoluble zinc phosphate.'
            ];
        }

        // Sulfur correction
        $sulfur = $soilTest->sulfur_ppm !== null ? (float) $soilTest->sulfur_ppm : null;
        if ($sulfur !== null && $sulfur < 10) {
            // Check if any recommended fertilizer already contains sulfur (SSP has ~12% S, Ammonium Sulphate has ~24% S)
            $hasS_Source = false;
            foreach ($recommendations as $rec) {
                if ($rec['fertilizer_name'] === 'SSP' || $rec['fertilizer_name'] === 'Ammonium Sulphate') {
                    $hasS_Source = true;
                }
            }

            if (!$hasS_Source) {
                $sQtyHa = 20; // 20 kg/ha
                $sQty = round($sQtyHa * $areaHa, 2);
                $correctiveRecommendations[] = [
                    'type' => 'micronutrient',
                    'name' => 'Bentonite Sulfur',
                    'qty_kg' => $sQty,
                    'cost_inr' => round($sQty * 35.0, 2), // Sulfur cost ₹35/kg
                    'reason' => 'Sulfur level is low (' . $sulfur . ' ppm, threshold < 10 ppm). Sulfur is vital for oil and protein synthesis.',
                    'instructions' => 'Apply as base dose at the time of sowing.'
                ];
            } else {
                $correctiveRecommendations[] = [
                    'type' => 'micronutrient_info',
                    'name' => 'Sulfur Correction',
                    'qty_kg' => 0,
                    'cost_inr' => 0.0,
                    'reason' => 'Sulfur is low (' . $sulfur . ' ppm), but will be naturally corrected by your SSP / Ammonium Sulphate fertilizer recommendation.',
                    'instructions' => 'No additional sulfur application needed.'
                ];
            }
        }

        // 8. Calculate Subsidy Savings
        $totalSubsidizedCost = 0.0;
        $totalUnsubsidizedCost = 0.0;

        foreach ($recommendations as $rec) {
            $fert = $activeFertilizers->firstWhere('id', $rec['fertilizer_id']);
            if ($fert) {
                $qty = (float) $rec['qty_kg'];
                $totalSubsidizedCost += $qty * (float) $fert->price_per_kg;
                $totalUnsubsidizedCost += $qty * (float) $fert->unsubsidized_price_per_kg;
            }
        }

        // Add extra soil conditioners to unsubsidized costs
        $totalSubsidizedCost += $extraCost;
        $totalUnsubsidizedCost += $extraUnsubsidizedCost;

        $subsidySaved = max(0, $totalUnsubsidizedCost - $totalSubsidizedCost);
        $displayTotalCost = collect($recommendations)->sum('cost_inr') + $extraCost;

        return [
            'recommendations' => $recommendations,
            'total_cost' => round($displayTotalCost, 2),
            'total_unsubsidized_cost' => round($totalUnsubsidizedCost, 2),
            'subsidy_saved' => round($subsidySaved, 2),
            'deficits' => ['N' => round($deficitN, 2), 'P' => round($deficitP, 2), 'K' => round($deficitK, 2)],
            'soil_supply' => ['N' => round($soilN, 2), 'P' => round($soilP, 2), 'K' => round($soilK, 2)],
            'crop_demand' => ['N' => round($totalRDF_N, 2), 'P' => round($totalRDF_P, 2), 'K' => round($totalRDF_K, 2)],
            'area_ha' => round($areaHa, 4),
            'corrective_recommendations' => $correctiveRecommendations,
        ];
    }

    private function optimizeNPK(float $deficitN, float $deficitP, float $deficitK, $fertilizers, float $areaAcres): array
    {
        // Filter fertilizers by primary nutrients
        $pSources = $fertilizers->filter(fn($f) => $f->phosphorus_pct > 0);
        $kSources = $fertilizers->filter(fn($f) => $f->potassium_pct > 0);
        $nSources = $fertilizers->filter(fn($f) => $f->nitrogen_pct > 0);

        // Add a null option to allow omission
        $pOptions = $pSources->all();
        $pOptions[] = null;

        $kOptions = $kSources->all();
        $kOptions[] = null;

        $nOptions = $nSources->all();
        $nOptions[] = null;

        $bestCombination = null;
        $minCost = INF;

        foreach ($pOptions as $pFert) {
            foreach ($kOptions as $kFert) {
                foreach ($nOptions as $nFert) {
                    $qtyP = 0.0;
                    $qtyK = 0.0;
                    $qtyN = 0.0;

                    $remN = $deficitN;
                    $remP = $deficitP;
                    $remK = $deficitK;

                    // 1. Solve Phosphorus first
                    if ($remP > 0 && $pFert) {
                        $pPct = (float) $pFert->phosphorus_pct / 100;
                        $qtyP = $remP / $pPct;

                        $remP = 0;
                        $remN -= $qtyP * ((float) $pFert->nitrogen_pct / 100);
                        $remK -= $qtyP * ((float) $pFert->potassium_pct / 100);
                    }

                    // 2. Solve Potassium second
                    if ($remK > 0 && $kFert) {
                        if ($pFert && $kFert && $pFert->id === $kFert->id) {
                            continue; // Avoid picking the same compound fertilizer for separate steps
                        }
                        $kPct = (float) $kFert->potassium_pct / 100;
                        $qtyK = $remK / $kPct;

                        $remK = 0;
                        $remN -= $qtyK * ((float) $kFert->nitrogen_pct / 100);
                        $remP -= $qtyK * ((float) $kFert->phosphorus_pct / 100);
                    }

                    // 3. Solve Nitrogen third
                    if ($remN > 0 && $nFert) {
                        if (($pFert && $nFert && $pFert->id === $nFert->id) || ($kFert && $nFert && $kFert->id === $nFert->id)) {
                            continue;
                        }
                        $nPct = (float) $nFert->nitrogen_pct / 100;
                        $qtyN = $remN / $nPct;
                        $remN = 0;
                    }

                    // Verify that all deficits are met
                    if ($remN <= 0.05 && $remP <= 0.05 && $remK <= 0.05) {
                        $cost = 0.0;
                        $items = [];

                        if ($qtyP > 0 && $pFert) {
                            $cost += $qtyP * (float) $pFert->price_per_kg;
                            $items[] = [
                                'fertilizer_name' => $pFert->name,
                                'fertilizer_id' => $pFert->id,
                                'qty_kg' => round($qtyP, 2),
                                'qty_per_acre' => round($qtyP / $areaAcres, 2),
                                'stage' => 'Base Dose (At Sowing)',
                                'method' => 'Band placement / broadcasting',
                                'cost_inr' => round($qtyP * (float) $pFert->price_per_kg, 2),
                            ];
                        }

                        if ($qtyK > 0 && $kFert) {
                            $cost += $qtyK * (float) $kFert->price_per_kg;
                            $items[] = [
                                'fertilizer_name' => $kFert->name,
                                'fertilizer_id' => $kFert->id,
                                'qty_kg' => round($qtyK, 2),
                                'qty_per_acre' => round($qtyK / $areaAcres, 2),
                                'stage' => 'Base Dose (At Sowing)',
                                'method' => 'Broadcasting before ploughing',
                                'cost_inr' => round($qtyK * (float) $kFert->price_per_kg, 2),
                            ];
                        }

                        if ($qtyN > 0 && $nFert) {
                            $cost += $qtyN * (float) $nFert->price_per_kg;

                            if ($nFert->name === 'Urea') {
                                $halfUrea = round($qtyN / 2, 2);
                                $quarterUrea = round($qtyN / 4, 2);
                                $items[] = [
                                    'fertilizer_name' => $nFert->name,
                                    'fertilizer_id' => $nFert->id,
                                    'qty_kg' => $halfUrea,
                                    'qty_per_acre' => round($halfUrea / $areaAcres, 2),
                                    'stage' => 'Base Dose (At Sowing)',
                                    'method' => 'Broadcasting + incorporation',
                                    'cost_inr' => round($halfUrea * (float) $nFert->price_per_kg, 2),
                                ];
                                $items[] = [
                                    'fertilizer_name' => $nFert->name,
                                    'fertilizer_id' => $nFert->id,
                                    'qty_kg' => $quarterUrea,
                                    'qty_per_acre' => round($quarterUrea / $areaAcres, 2),
                                    'stage' => 'Top Dressing 1 (30 days after sowing)',
                                    'method' => 'Side dressing near root zone',
                                    'cost_inr' => round($quarterUrea * (float) $nFert->price_per_kg, 2),
                                ];
                                $items[] = [
                                    'fertilizer_name' => $nFert->name,
                                    'fertilizer_id' => $nFert->id,
                                    'qty_kg' => $quarterUrea,
                                    'qty_per_acre' => round($quarterUrea / $areaAcres, 2),
                                    'stage' => 'Top Dressing 2 (60 days after sowing)',
                                    'method' => 'Side dressing near root zone',
                                    'cost_inr' => round($quarterUrea * (float) $nFert->price_per_kg, 2),
                                ];
                            } else {
                                $items[] = [
                                    'fertilizer_name' => $nFert->name,
                                    'fertilizer_id' => $nFert->id,
                                    'qty_kg' => round($qtyN, 2),
                                    'qty_per_acre' => round($qtyN / $areaAcres, 2),
                                    'stage' => 'Base Dose (At Sowing)',
                                    'method' => 'Broadcasting + incorporation',
                                    'cost_inr' => round($qtyN * (float) $nFert->price_per_kg, 2),
                                ];
                            }
                        }

                        if ($cost < $minCost) {
                            $minCost = $cost;
                            $bestCombination = [
                                'items' => $items,
                                'total_cost' => $cost,
                                'details' => [
                                    'p_fert' => $pFert,
                                    'p_qty' => $qtyP,
                                    'k_fert' => $kFert,
                                    'k_qty' => $qtyK,
                                    'n_fert' => $nFert,
                                    'n_qty' => $qtyN,
                                ]
                            ];
                        }
                    }
                }
            }
        }

        return $bestCombination ?: [
            'items' => [],
            'total_cost' => 0.0,
            'details' => []
        ];
    }
}
