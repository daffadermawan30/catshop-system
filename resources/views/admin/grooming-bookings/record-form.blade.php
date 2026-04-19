@extends('layouts.admin')

@section('title', 'Catatan Grooming')
@section('header', 'Catatan Hasil Grooming')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.grooming-bookings.show', $groomingBooking) }}"
       class="text-gray-500 text-sm flex items-center gap-1 mb-6">
        ← Kembali ke detail booking
    </a>

    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6 text-sm">
        <p class="font-medium text-orange-800">
            📋 Booking #{{ $groomingBooking->id }} —
            {{ $groomingBooking->cat->name }} ({{ $groomingBooking->package->name }})
        </p>
        <p class="text-orange-600">{{ $groomingBooking->booking_date->format('d M Y, H:i') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.grooming-bookings.store-record', $groomingBooking) }}"
              method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <x-form-input name="weight_at_service" label="Berat saat Grooming (kg)" type="number"
                    :value="old('weight_at_service', $groomingBooking->record?->weight_at_service)"
                    placeholder="Contoh: 3.5" />
                <x-form-input name="products_used" label="Produk yang Dipakai"
                    :value="old('products_used', $groomingBooking->record?->products_used)"
                    placeholder="Shampo, kondisioner, dll." />
            </div>

            <div class="mb-4">
                <label for="condition_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Kondisi Kucing saat Masuk
                </label>
                <textarea id="condition_notes" name="condition_notes" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                    placeholder="Contoh: Bulu kusut, ada kutu, kooperatif..."
                >{{ old('condition_notes', $groomingBooking->record?->condition_notes) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="result_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan Hasil Grooming
                </label>
                <textarea id="result_notes" name="result_notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                    placeholder="Catatan untuk pemilik tentang kondisi kucing setelah grooming..."
                >{{ old('result_notes', $groomingBooking->record?->result_notes) }}</textarea>
            </div>

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Foto Sebelum & Sesudah</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Sebelum</label>
                    @if($groomingBooking->record?->photo_before)
                        <img src="{{ Storage::url($groomingBooking->record->photo_before) }}"
                             class="w-full h-32 object-cover rounded-lg mb-2 border">
                        <p class="text-xs text-gray-500 mb-1">Upload baru untuk mengganti.</p>
                    @endif
                    <input type="file" name="photo_before" accept="image/*"
                        class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Sesudah</label>
                    @if($groomingBooking->record?->photo_after)
                        <img src="{{ Storage::url($groomingBooking->record->photo_after) }}"
                             class="w-full h-32 object-cover rounded-lg mb-2 border">
                        <p class="text-xs text-gray-500 mb-1">Upload baru untuk mengganti.</p>
                    @endif
                    <input type="file" name="photo_after" accept="image/*"
                        class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700">
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-green-700">
                    💾 Simpan Catatan
                </button>
                <a href="{{ route('admin.grooming-bookings.show', $groomingBooking) }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
