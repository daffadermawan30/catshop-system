<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroomingBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'    => ['required', 'exists:customers,id'],
            'cat_id'         => ['required', 'exists:cats,id'],
            'package_id'     => ['required', 'exists:grooming_packages,id'],
            // Booking minimal H+1 agar tidak bisa booking untuk hari ini secara langsung
            'booking_date'   => ['required', 'date', 'after:now'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'  => 'Pelanggan wajib dipilih.',
            'cat_id.required'       => 'Kucing wajib dipilih.',
            'package_id.required'   => 'Paket grooming wajib dipilih.',
            'booking_date.required' => 'Tanggal booking wajib diisi.',
            'booking_date.after'    => 'Jadwal booking harus setelah waktu sekarang.',
        ];
    }
}
