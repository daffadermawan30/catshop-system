<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroomingRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'condition_notes'     => ['nullable', 'string', 'max:500'],
            'products_used'       => ['nullable', 'string', 'max:200'],
            'weight_at_service'   => ['nullable', 'numeric', 'min:0.1', 'max:30'],
            'result_notes'        => ['nullable', 'string', 'max:1000'],
            'photo_before'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'photo_after'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
