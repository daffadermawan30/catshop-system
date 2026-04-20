<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Saat edit, ignore slug milik category itu sendiri
        $ignoreId = $this->route('category')?->id;

        return [
            'name'        => ['required', 'string', 'max:100'],
            'slug'        => ['nullable', 'string', 'max:120', Rule::unique('categories', 'slug')->ignore($ignoreId)],
            'description' => ['nullable', 'string', 'max:300'],
            'is_active'   => ['boolean'],
        ];
    }
}
