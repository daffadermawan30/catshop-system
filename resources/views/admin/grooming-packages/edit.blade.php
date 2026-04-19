@extends('layouts.admin')

@section('title', 'Edit Paket Grooming')
@section('header', 'Edit Paket Grooming')

@section('content')

<div class="max-w-xl">
    <a href="{{ route('admin.grooming-packages.index') }}"
       class="text-gray-500 text-sm flex items-center gap-1 mb-6">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.grooming-packages.update', $groomingPackage) }}" method="POST">
            @csrf @method('PUT')

            <x-form-input name="name" label="Nama Paket"
                :value="old('name', $groomingPackage->name)" :required="true" />

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('description', $groomingPackage->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input name="price" label="Harga (Rp)" type="number"
                    :value="old('price', $groomingPackage->price)" :required="true" />
                <x-form-input name="duration_minutes" label="Durasi (menit)" type="number"
                    :value="old('duration_minutes', $groomingPackage->duration_minutes)" :required="true" />
            </div>

            <div class="mb-4 flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $groomingPackage->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 text-orange-600 rounded">
                <label for="is_active" class="text-sm text-gray-700">Paket aktif</label>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.grooming-packages.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
