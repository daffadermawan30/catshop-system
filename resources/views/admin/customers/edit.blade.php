@extends('layouts.admin')

@section('title', 'Edit Pelanggan')
@section('header', 'Edit Data Pelanggan')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.customers.show', $customer) }}"
       class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1 mb-6">
        ← Kembali ke detail pelanggan
    </a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        {{--
            @method('PUT') memberitahu Laravel bahwa ini adalah request UPDATE
            karena HTML form hanya support GET dan POST, kita pakai spoofing method
        --}}
        <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Informasi Akun</h3>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input
                    name="name"
                    label="Nama Lengkap"
                    :value="old('name', $customer->name)"
                    :required="true"
                />
                <x-form-input
                    name="email"
                    label="Email"
                    type="email"
                    :value="old('email', $customer->user->email)"
                    :required="true"
                />
                <x-form-input
                    name="password"
                    label="Password Baru"
                    type="password"
                    placeholder="Kosongkan jika tidak ingin mengubah"
                    help="Isi hanya jika ingin mengubah password"
                />
                <x-form-input
                    name="password_confirmation"
                    label="Konfirmasi Password Baru"
                    type="password"
                    placeholder="Ulangi password baru"
                />
            </div>

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-6">Informasi Pribadi</h3>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input
                    name="phone"
                    label="No. Telepon/HP"
                    :value="old('phone', $customer->phone)"
                />
                <x-form-select
                    name="gender"
                    label="Jenis Kelamin"
                    :selected="old('gender', $customer->gender)"
                    :options="['male' => 'Laki-laki', 'female' => 'Perempuan']"
                />
                <x-form-input
                    name="identity_number"
                    label="No. KTP"
                    :value="old('identity_number', $customer->identity_number)"
                />
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('address', $customer->address) }}</textarea>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.customers.show', $customer) }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
