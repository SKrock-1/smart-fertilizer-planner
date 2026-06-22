<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFertilizerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nitrogen_pct' => ['required', 'numeric', 'between:0,100'],
            'phosphorus_pct' => ['required', 'numeric', 'between:0,100'],
            'potassium_pct' => ['required', 'numeric', 'between:0,100'],
            'price_per_kg' => ['required', 'numeric', 'min:0'],
            'unsubsidized_price_per_kg' => ['nullable', 'numeric', 'min:0'],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
