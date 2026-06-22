<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'fertilizer_id',
        'quantity_kg',
        'application_stage',
        'application_method',
        'cost_inr',
    ];

    protected $casts = [
        'quantity_kg' => 'decimal:2',
        'cost_inr' => 'decimal:2',
    ];

    public function fertilizerPlan(): BelongsTo
    {
        return $this->belongsTo(FertilizerPlan::class, 'plan_id');
    }

    public function fertilizer(): BelongsTo
    {
        return $this->belongsTo(Fertilizer::class);
    }
}
