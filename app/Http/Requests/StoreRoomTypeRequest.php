<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:500'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'facilities'    => ['nullable', 'string', 'max:300'],
            'is_active'     => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Nama tipe kamar wajib diisi.',
            'price_per_day.required' => 'Harga per malam wajib diisi.',
        ];
    }
}
