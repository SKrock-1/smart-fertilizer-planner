<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LandParcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parcel_name',
        'area_acres',
        'district',
        'state',
        'latitude',
        'longitude',
        'soil_type',
        'notes',
    ];

    protected $casts = [
        'area_acres' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function soilTests(): HasMany
    {
        return $this->hasMany(SoilTest::class, 'parcel_id');
    }

    public function fertilizerPlans(): HasMany
    {
        return $this->hasMany(FertilizerPlan::class, 'parcel_id');
    }

    public function latestSoilTest(): HasOne
    {
        return $this->hasOne(SoilTest::class, 'parcel_id')->latestOfMany('test_date');
    }
}
