<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'           => ['required', 'exists:customers,id'],
            'name'                  => ['required', 'string', 'max:100'],
            'breed'                 => ['nullable', 'string', 'max:100'],
            'gender'                => ['nullable', 'in:male,female'],
            'date_of_birth'         => ['nullable', 'date', 'before:today'],
            'weight'                => ['nullable', 'numeric', 'min:0.1', 'max:30'],
            'fur_color'             => ['nullable', 'string', 'max:100'],
            'is_sterilized'         => ['boolean'],
            // nullable saat update — hanya diupload jika ingin ganti foto
            'photo'                 => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'allergies'             => ['nullable', 'string', 'max:500'],
            'special_notes'         => ['nullable', 'string', 'max:1000'],
            'last_vaccination_date' => ['nullable', 'date'],
            'next_vaccination_date' => ['nullable', 'date'],
        ];
    }
}
