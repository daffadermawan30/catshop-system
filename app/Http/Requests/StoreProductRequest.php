<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ignoreId = $this->route('product')?->id;

        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:150'],
            'sku'         => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($ignoreId)],
            'barcode'     => ['nullable', 'string', 'max:50', Rule::unique('products', 'barcode')->ignore($ignoreId)],
            'description' => ['nullable', 'string', 'max:500'],
            'photo'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'buy_price'   => ['required', 'numeric', 'min:0'],
            'sell_price'  => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'stock_min'   => ['required', 'integer', 'min:0'],
            'unit'        => ['required', 'string', 'max:20'],
            'is_active'   => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Nama produk wajib diisi.',
            'sell_price.required' => 'Harga jual wajib diisi.',
            'buy_price.required'  => 'Harga beli wajib diisi.',
        ];
    }
}
