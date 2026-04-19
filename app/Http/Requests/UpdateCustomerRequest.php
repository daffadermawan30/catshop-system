<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID customer dari route parameter
        $customerId = $this->route('customer')->id;
        // Ambil user_id dari customer tersebut
        $userId = $this->route('customer')->user_id;

        return [
            'name'            => ['required', 'string', 'max:100'],
            // Email unik KECUALI milik user ini sendiri (ignore saat update)
            'email'           => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            // Password opsional saat edit (hanya diubah jika diisi)
            'password'        => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string', 'max:500'],
            'gender'          => ['nullable', 'in:male,female'],
            'identity_number' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan pelanggan lain.',
        ];
    }
}
