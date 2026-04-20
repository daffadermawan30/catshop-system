@extends('layouts.admin')
@section('title', 'Jurnal Harian')
@section('header', 'Jurnal Harian Penitipan')

@section('content')

<div class="max-w-xl">
    <a href="{{ route('admin.boarding-bookings.show', $boardingBooking) }}"
       class="text-gray-500 text-sm flex items-center gap-1 mb-6">← Kembali ke detail penitipan</a>

    {{-- Info konteks --}}
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6 text-sm">
        <p class="font-medium text-orange-800">
            🐱 {{ $boardingBooking->cat->name }}
        </p>
        <p class="text-orange-600">
            Kamar {{ $boardingBooking->room->room_number }} ·
            {{ $boardingBooking->check_in_date->format('d M') }} –
            {{ $boardingBooking->check_out_date->format('d M Y') }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.boarding-bookings.store-journal', $boardingBooking) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            {{-- Tanggal jurnal --}}
            <x-form-input
                name="journal_date"
                label="Tanggal Jurnal"
                type="date"
                :value="old('journal_date', $defaultDate)"
                :required="true"
                help="Satu jurnal per tanggal. Jika sudah ada, entri lama akan diperbarui."
            />

            {{-- Kondisi kucing --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kondisi Kucing <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach(['good' => '😊 Baik', 'normal' => '😐 Normal', 'stressed' => '😰 Stres', 'sick' => '🤒 Sakit'] as $val => $lbl)
                    <label class="cursor-pointer">
                        <input type="radio" name="condition" value="{{ $val }}" class="sr-only peer"
                            {{ old('condition', $existingJournal?->condition ?? 'normal') === $val ? 'checked' : '' }}>
                        <div class="text-center p-2 border-2 rounded-lg text-sm transition
                                    peer-checked:border-orange-500 peer-checked:bg-orange-50 border-gray-200 hover:border-orange-300">
                            {{ $lbl }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('condition') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Catatan makan --}}
            <div class="mb-4">
                <label for="eating_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    🍽️ Catatan Makan
                </label>
                <textarea id="eating_notes" name="eating_notes" rows="2"
                    placeholder="Contoh: Makan pagi 1/2 porsi, makan siang habis, makan malam tidak mau..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                >{{ old('eating_notes', $existingJournal?->eating_notes) }}</textarea>
            </div>

            {{-- Catatan aktivitas --}}
            <div class="mb-4">
                <label for="activity_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    🎾 Catatan Aktivitas
                </label>
                <textarea id="activity_notes" name="activity_notes" rows="2"
                    placeholder="Contoh: Aktif bermain, tidur sepanjang hari, keluar kandang 30 menit..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                >{{ old('activity_notes', $existingJournal?->activity_notes) }}</textarea>
            </div>

            {{-- Catatan kesehatan --}}
            <div class="mb-4">
                <label for="health_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    🩺 Catatan Kesehatan
                </label>
                <textarea id="health_notes" name="health_notes" rows="2"
                    placeholder="Contoh: Kondisi normal, ada bersin 2 kali, perlu perhatian..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                >{{ old('health_notes', $existingJournal?->health_notes) }}</textarea>
            </div>

            {{-- Upload foto harian --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">📸 Foto Hari Ini</label>
                @if($existingJournal?->photo)
                    <img src="{{ Storage::url($existingJournal->photo) }}"
                         alt="Foto jurnal"
                         class="w-full h-36 object-cover rounded-lg mb-2 border">
                    <p class="text-xs text-gray-500 mb-1">Upload baru untuk mengganti foto.</p>
                @endif
                <input type="file" name="photo" accept="image/*"
                    class="w-full text-xs text-gray-500
                           file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                           file:bg-orange-50 file:text-orange-700 file:hover:bg-orange-100">
                @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
                    💾 Simpan Jurnal
                </button>
                <a href="{{ route('admin.boarding-bookings.show', $boardingBooking) }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
