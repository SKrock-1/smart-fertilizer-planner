<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FertilizerPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_id',
        'soil_test_id',
        'crop_id',
        'season_year',
        'total_cost_inr',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_cost_inr' => 'decimal:2',
    ];

    public function landParcel(): BelongsTo
    {
        return $this->belongsTo(LandParcel::class, 'parcel_id');
    }

    public function soilTest(): BelongsTo
    {
        return $this->belongsTo(SoilTest::class);
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function planItems(): HasMany
    {
        return $this->hasMany(PlanItem::class, 'plan_id');
    }
}
