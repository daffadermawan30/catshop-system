@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('header', 'Tambah Produk Baru')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.products.index') }}"
       class="text-gray-500 text-sm mb-6 inline-flex items-center gap-1">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-form-input name="name" label="Nama Produk"
                        :value="old('name')" :required="true"
                        placeholder="Contoh: Royal Canin Kitten 400g" />
                </div>

                <div class="col-span-2 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                        <option value="">-- Tanpa Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <x-form-input name="buy_price" label="Harga Beli (Rp)" type="number"
                    :value="old('buy_price')" :required="true" placeholder="0" />
                <x-form-input name="sell_price" label="Harga Jual (Rp)" type="number"
                    :value="old('sell_price')" :required="true" placeholder="0" />

                <x-form-input name="stock" label="Stok Awal" type="number"
                    :value="old('stock', 0)" :required="true" />
                <x-form-input name="stock_min" label="Stok Minimum Alert" type="number"
                    :value="old('stock_min', 5)" :required="true"
                    help="Akan muncul peringatan jika stok ≤ angka ini" />

                <x-form-input name="unit" label="Satuan" :value="old('unit', 'pcs')"
                    :required="true" placeholder="pcs, kg, gr, liter, ml, pak, dus" />
                <x-form-input name="sku" label="SKU (opsional)"
                    :value="old('sku')" placeholder="Auto-generate jika kosong" />

                <x-form-input name="barcode" label="Barcode (opsional)"
                    :value="old('barcode')" placeholder="Scan atau input manual" />

                <div class="col-span-2 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full text-xs text-gray-500
                               file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                               file:bg-orange-50 file:text-orange-700 file:hover:bg-orange-100">
                    @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2 mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="col-span-2 mb-4 flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                        class="w-4 h-4 text-orange-600 rounded">
                    <label for="is_active" class="text-sm text-gray-700">Produk aktif & bisa dijual</label>
                </div>
            </div>

            <div class="flex gap-3 mt-2">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700">
                    Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
