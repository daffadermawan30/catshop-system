@extends('layouts.admin')
@section('title', 'Tambah Kategori')
@section('header', 'Tambah Kategori')

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.categories.index') }}"
       class="text-gray-500 text-sm mb-6 inline-flex items-center gap-1">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <x-form-input name="name" label="Nama Kategori" :value="old('name')"
                placeholder="Contoh: Makanan Kucing, Pasir Kucing, Aksesoris" :required="true" />

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                >{{ old('description') }}</textarea>
            </div>

            <div class="mb-4 flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked
                    class="w-4 h-4 text-orange-600 rounded">
                <label for="is_active" class="text-sm text-gray-700">Kategori aktif</label>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700">
                    Simpan
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
