<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fertilizer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nitrogen_pct',
        'phosphorus_pct',
        'potassium_pct',
        'price_per_kg',
        'unsubsidized_price_per_kg',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'nitrogen_pct' => 'decimal:2',
        'phosphorus_pct' => 'decimal:2',
        'potassium_pct' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'unsubsidized_price_per_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function planItems(): HasMany
    {
        return $this->hasMany(PlanItem::class);
    }
}
