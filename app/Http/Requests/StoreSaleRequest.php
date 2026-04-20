<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'        => ['nullable', 'exists:customers,id'],
            'payment_method'     => ['required', 'in:cash,transfer,qris,debit,credit'],
            'paid_amount'        => ['required', 'numeric', 'min:0'],
            'discount_amount'    => ['nullable', 'numeric', 'min:0'],
            'notes'              => ['nullable', 'string', 'max:300'],

            // Validasi array items (minimal 1 produk harus ada)
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount'   => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'              => 'Keranjang belanja kosong.',
            'items.min'                   => 'Minimal 1 produk harus ditambahkan.',
            'items.*.product_id.required' => 'Produk tidak valid.',
            'items.*.quantity.min'        => 'Jumlah minimal 1.',
        ];
    }
}
