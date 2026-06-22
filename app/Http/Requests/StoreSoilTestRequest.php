<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSoilTestRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->route('parcel')) {
            $this->merge([
                'parcel_id' => $this->route('parcel')->id,
            ]);
        }
    }

    public function authorize(): bool
    {
        return $this->user()?->role === 'farmer';
    }

    public function rules(): array
    {
        return [
            'parcel_id' => ['required', 'exists:land_parcels,id'],
            'test_date' => ['required', 'date', 'before_or_equal:today'],
            'ph_level' => ['required', 'numeric', 'between:0,14'],
            'nitrogen_kg_ha' => ['required', 'numeric', 'min:0', 'max:2000'],
            'phosphorus_kg_ha' => ['required', 'numeric', 'min:0', 'max:500'],
            'potassium_kg_ha' => ['required', 'numeric', 'min:0', 'max:2000'],
            'organic_carbon_pct' => ['nullable', 'numeric', 'between:0,20'],
            'zinc_ppm' => ['nullable', 'numeric', 'min:0'],
            'sulfur_ppm' => ['nullable', 'numeric', 'min:0'],
            'lab_report' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
