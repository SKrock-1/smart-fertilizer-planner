<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLandParcelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'farmer';
    }

    public function rules(): array
    {
        return [
            'parcel_name' => ['required', 'string', 'max:255'],
            'area_acres' => ['required', 'numeric', 'min:0.01', 'max:10000'],
            'district' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'soil_type' => ['nullable', 'in:loamy,sandy,clay,silt,black_cotton'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
