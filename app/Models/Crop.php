<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'variety',
        'season',
        'rdf_nitrogen',
        'rdf_phosphorus',
        'rdf_potassium',
        'duration_days',
        'description',
        'is_active',
    ];

    protected $casts = [
        'rdf_nitrogen' => 'decimal:2',
        'rdf_phosphorus' => 'decimal:2',
        'rdf_potassium' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function fertilizerPlans(): HasMany
    {
        return $this->hasMany(FertilizerPlan::class);
    }
}
