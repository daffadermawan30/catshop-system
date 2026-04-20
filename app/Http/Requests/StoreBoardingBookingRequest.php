<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'     => ['required', 'exists:customers,id'],
            'cat_id'          => ['required', 'exists:cats,id'],
            'room_id'         => ['required', 'exists:rooms,id'],
            'check_in_date'   => ['required', 'date'],
            // check_out harus setelah check_in
            'check_out_date'  => ['required', 'date', 'after:check_in_date'],
            'customer_notes'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'    => 'Pelanggan wajib dipilih.',
            'cat_id.required'         => 'Kucing wajib dipilih.',
            'room_id.required'        => 'Kamar wajib dipilih.',
            'check_in_date.required'  => 'Tanggal check-in wajib diisi.',
            'check_out_date.required' => 'Tanggal check-out wajib diisi.',
            'check_out_date.after'    => 'Check-out harus setelah check-in.',
        ];
    }
}
