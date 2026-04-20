@extends('layouts.admin')
@section('title', 'Edit Tipe Kamar')
@section('header', 'Edit Tipe Kamar')

@section('content')

<div class="max-w-xl">
    <a href="{{ route('admin.room-types.index') }}"
       class="text-gray-500 text-sm flex items-center gap-1 mb-6">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.room-types.update', $roomType) }}" method="POST">
            @csrf @method('PUT')

            <x-form-input
                name="name"
                label="Nama Tipe Kamar"
                :value="old('name', $roomType->name)"
                :required="true"
            />

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi
                </label>
                <textarea id="description" name="description" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('description', $roomType->description) }}</textarea>
            </div>

            <x-form-input
                name="price_per_day"
                label="Harga per Malam (Rp)"
                type="number"
                :value="old('price_per_day', $roomType->price_per_day)"
                :required="true"
            />

            <x-form-input
                name="facilities"
                label="Fasilitas"
                :value="old('facilities', $roomType->facilities)"
            />

            <div class="mb-4 flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $roomType->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 text-orange-600 rounded">
                <label for="is_active" class="text-sm text-gray-700">Tipe kamar aktif</label>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.room-types.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
