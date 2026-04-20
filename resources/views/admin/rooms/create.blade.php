@extends('layouts.admin')
@section('title', 'Tambah Kamar')
@section('header', 'Tambah Kamar Baru')

@section('content')

<div class="max-w-xl">
    <a href="{{ route('admin.rooms.index') }}"
       class="text-gray-500 text-sm flex items-center gap-1 mb-6">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Tipe Kamar <span class="text-red-500">*</span>
                </label>
                <select id="room_type_id" name="room_type_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400
                           {{ $errors->has('room_type_id') ? 'border-red-400' : '' }}">
                    <option value="">-- Pilih Tipe Kamar --</option>
                    @foreach($roomTypes as $type)
                    <option value="{{ $type->id }}" {{ old('room_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }} — {{ $type->formatted_price }}/malam
                    </option>
                    @endforeach
                </select>
                @error('room_type_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-form-input
                name="room_number"
                label="Nomor Kamar"
                :value="old('room_number')"
                placeholder="Contoh: STD-01, VIP-03"
                :required="true"
                help="Nomor kamar harus unik. Akan otomatis diubah ke huruf kapital."
            />

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan (opsional)
                </label>
                <textarea id="notes" name="notes" rows="2"
                    placeholder="Catatan khusus untuk kamar ini..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
                    Tambah Kamar
                </button>
                <a href="{{ route('admin.rooms.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
