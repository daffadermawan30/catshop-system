<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroomingPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:100'],
            'description'      => ['nullable', 'string', 'max:500'],
            'price'            => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'is_active'        => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'Nama paket wajib diisi.',
            'price.required'            => 'Harga wajib diisi.',
            'duration_minutes.required' => 'Estimasi durasi wajib diisi.',
        ];
    }
}
