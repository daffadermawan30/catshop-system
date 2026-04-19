@extends('layouts.admin')

@section('title', 'Tambah Pelanggan')
@section('header', 'Tambah Pelanggan Baru')

@section('content')

<div class="max-w-2xl">
    {{-- Tombol kembali --}}
    <a href="{{ route('admin.customers.index') }}"
       class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1 mb-6">
        ← Kembali ke daftar pelanggan
    </a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        {{--
            action = URL tujuan form (route store)
            method POST + @csrf = keamanan Laravel, wajib ada di setiap form
        --}}
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Informasi Akun</h3>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input
                    name="name"
                    label="Nama Lengkap"
                    :value="old('name')"
                    placeholder="Contoh: Budi Santoso"
                    :required="true"
                />
                <x-form-input
                    name="email"
                    label="Email"
                    type="email"
                    :value="old('email')"
                    placeholder="email@contoh.com"
                    :required="true"
                />
                <x-form-input
                    name="password"
                    label="Password"
                    type="password"
                    placeholder="Min. 8 karakter"
                    :required="true"
                />
                <x-form-input
                    name="password_confirmation"
                    label="Konfirmasi Password"
                    type="password"
                    placeholder="Ulangi password"
                    :required="true"
                />
            </div>

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-6">Informasi Pribadi</h3>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input
                    name="phone"
                    label="No. Telepon/HP"
                    :value="old('phone')"
                    placeholder="08xxxxxxxxxx"
                />
                <x-form-select
                    name="gender"
                    label="Jenis Kelamin"
                    :selected="old('gender')"
                    :options="['male' => 'Laki-laki', 'female' => 'Perempuan']"
                />
                <x-form-input
                    name="identity_number"
                    label="No. KTP (opsional)"
                    :value="old('identity_number')"
                    placeholder="16 digit nomor KTP"
                />
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    placeholder="Alamat lengkap pelanggan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
                    Simpan Pelanggan
                </button>
                <a href="{{ route('admin.customers.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
