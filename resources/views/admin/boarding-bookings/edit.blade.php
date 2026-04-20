@extends('layouts.admin')
@section('title', 'Edit Booking Penitipan')
@section('header', 'Edit Booking Penitipan')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.boarding-bookings.show', $boardingBooking) }}"
       class="text-gray-500 text-sm flex items-center gap-1 mb-6">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.boarding-bookings.update', $boardingBooking) }}" method="POST">
            @csrf @method('PUT')

            {{-- Customer (tidak bisa diubah jika sudah confirmed) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pelanggan <span class="text-red-500">*</span>
                </label>
                <select name="customer_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ old('customer_id', $boardingBooking->customer_id) == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kucing <span class="text-red-500">*</span>
                </label>
                <select name="cat_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                    @foreach($cats as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('cat_id', $boardingBooking->cat_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->breed ?? 'mix' }})
                    </option>
                    @endforeach
                </select>
                @error('cat_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kamar <span class="text-red-500">*</span>
                </label>
                <select name="room_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                    @foreach($roomTypes as $type)
                    <optgroup label="{{ $type->name }} — {{ $type->formatted_price }}/malam">
                        @foreach($type->rooms as $room)
                        <option value="{{ $room->id }}"
                            {{ old('room_id', $boardingBooking->room_id) == $room->id ? 'selected' : '' }}
                            {{-- Izinkan kamar saat ini walaupun occupied --}}
                            {{ ($room->status !== 'available' && $room->id !== $boardingBooking->room_id) ? 'disabled' : '' }}>
                            {{ $room->room_number }}
                            {{ ($room->status !== 'available' && $room->id !== $boardingBooking->room_id) ? '(Tidak Tersedia)' : '' }}
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                @error('room_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input name="check_in_date" label="Tanggal Check-in" type="date"
                    :value="old('check_in_date', $boardingBooking->check_in_date->format('Y-m-d'))"
                    :required="true" />
                <x-form-input name="check_out_date" label="Tanggal Check-out" type="date"
                    :value="old('check_out_date', $boardingBooking->check_out_date->format('Y-m-d'))"
                    :required="true" />
            </div>

            <div class="mb-4">
                <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea id="customer_notes" name="customer_notes" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                >{{ old('customer_notes', $boardingBooking->customer_notes) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.boarding-bookings.show', $boardingBooking) }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
