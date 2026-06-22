<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFertilizerPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'farmer';
    }

    public function rules(): array
    {
        return [
            'parcel_id' => ['required', 'exists:land_parcels,id'],
            'crop_id' => ['required', 'exists:crops,id'],
            'season_year' => ['required', 'string', 'max:20', 'regex:/^(Kharif|Rabi|Zaid)-\d{4}$/'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
