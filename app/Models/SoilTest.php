<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoilTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_id',
        'test_date',
        'ph_level',
        'nitrogen_kg_ha',
        'phosphorus_kg_ha',
        'potassium_kg_ha',
        'organic_carbon_pct',
        'zinc_ppm',
        'sulfur_ppm',
        'lab_report_path',
    ];

    protected $casts = [
        'test_date' => 'date',
        'ph_level' => 'decimal:2',
        'nitrogen_kg_ha' => 'decimal:2',
        'phosphorus_kg_ha' => 'decimal:2',
        'potassium_kg_ha' => 'decimal:2',
        'organic_carbon_pct' => 'decimal:2',
        'zinc_ppm' => 'decimal:2',
        'sulfur_ppm' => 'decimal:2',
    ];

    public function landParcel(): BelongsTo
    {
        return $this->belongsTo(LandParcel::class, 'parcel_id');
    }

    public function fertilizerPlans(): HasMany
    {
        return $this->hasMany(FertilizerPlan::class);
    }
}
