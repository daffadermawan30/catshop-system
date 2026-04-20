@extends('layouts.admin')
@section('title', 'Penitipan #' . $boardingBooking->id)
@section('header', 'Detail Penitipan')

@section('content')

<div class="flex justify-between items-center mb-6">
    <a href="{{ route('admin.boarding-bookings.index') }}" class="text-gray-500 text-sm">← Kembali</a>
    <div class="flex gap-2">
        @if(!in_array($boardingBooking->status, ['checked_in','checked_out','cancelled']))
        <a href="{{ route('admin.boarding-bookings.edit', $boardingBooking) }}"
           class="bg-white border text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">✏️ Edit</a>
        @endif
        @if($boardingBooking->status === 'checked_in')
        <a href="{{ route('admin.boarding-bookings.journal-form', $boardingBooking) }}"
           class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
            📓 Tambah Jurnal Hari Ini
        </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Kolom kiri: Info booking --}}
    <div class="col-span-2 space-y-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Informasi Penitipan</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Pelanggan</p>
                    <a href="{{ route('admin.customers.show', $boardingBooking->customer) }}"
                       class="font-medium text-orange-600 hover:underline">
                        {{ $boardingBooking->customer->name }}
                    </a>
                </div>
                <div>
                    <p class="text-gray-500">Kucing</p>
                    <a href="{{ route('admin.cats.show', $boardingBooking->cat) }}"
                       class="font-medium text-orange-600 hover:underline">
                        {{ $boardingBooking->cat->name }}
                    </a>
                </div>
                <div>
                    <p class="text-gray-500">Kamar</p>
                    <p class="font-medium">{{ $boardingBooking->room->room_number }}
                        <span class="text-gray-400">({{ $boardingBooking->room->roomType->name }})</span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Harga/Malam</p>
                    <p class="font-medium">Rp {{ number_format($boardingBooking->price_per_day, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Check-in (Rencana)</p>
                    <p class="font-medium">{{ $boardingBooking->check_in_date->format('d M Y') }}</p>
                    @if($boardingBooking->actual_check_in)
                    <p class="text-xs text-green-600">Aktual: {{ $boardingBooking->actual_check_in->format('d M Y H:i') }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-500">Check-out (Rencana)</p>
                    <p class="font-medium">{{ $boardingBooking->check_out_date->format('d M Y') }}</p>
                    @if($boardingBooking->actual_check_out)
                    <p class="text-xs text-green-600">Aktual: {{ $boardingBooking->actual_check_out->format('d M Y H:i') }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-500">Durasi</p>
                    <p class="font-medium">{{ $boardingBooking->duration_days }} hari</p>
                </div>
                <div>
                    <p class="text-gray-500">Total</p>
                    <p class="font-bold text-orange-600 text-lg">
                        Rp {{ number_format($boardingBooking->total_price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @if($boardingBooking->customer_notes)
            <div class="mt-4 p-3 bg-gray-50 rounded-lg text-sm">
                <p class="text-gray-500 text-xs mb-1">Catatan Pelanggan</p>
                <p>{{ $boardingBooking->customer_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Jurnal Harian --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700">📓 Jurnal Harian</h3>
                <span class="text-xs text-gray-500">{{ $boardingBooking->journals->count() }} entri</span>
            </div>

            @if($boardingBooking->journals->isEmpty())
            <p class="text-gray-400 text-sm text-center py-6">
                {{ $boardingBooking->status === 'checked_in' ? 'Belum ada jurnal hari ini.' : 'Jurnal belum tersedia.' }}
            </p>
            @else
            <div class="space-y-4">
                @foreach($boardingBooking->journals as $journal)
                <div class="border rounded-xl p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-medium text-gray-800">
                                {{ $journal->journal_date->format('l, d M Y') }}
                            </p>
                            <p class="text-sm">{{ $journal->condition_label }}</p>
                        </div>
                        @if($journal->author)
                        <p class="text-xs text-gray-400">oleh {{ $journal->author->name }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm mt-3">
                        @if($journal->eating_notes)
                        <div>
                            <p class="text-xs text-gray-500">🍽️ Makan</p>
                            <p class="text-gray-700">{{ $journal->eating_notes }}</p>
                        </div>
                        @endif
                        @if($journal->activity_notes)
                        <div>
                            <p class="text-xs text-gray-500">🎾 Aktivitas</p>
                            <p class="text-gray-700">{{ $journal->activity_notes }}</p>
                        </div>
                        @endif
                        @if($journal->health_notes)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500">🩺 Catatan Kesehatan</p>
                            <p class="text-gray-700">{{ $journal->health_notes }}</p>
                        </div>
                        @endif
                    </div>

                    @if($journal->photo)
                    <img src="{{ Storage::url($journal->photo) }}"
                         alt="Foto jurnal"
                         class="mt-3 h-40 w-full object-cover rounded-lg border">
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Kolom kanan: Kontrol status --}}
    <div class="col-span-1 space-y-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4">🔄 Update Status</h3>
            @php
                $nextStatuses = [
                    'pending'     => ['confirmed' => 'Konfirmasi Booking', 'cancelled' => 'Batalkan'],
                    'confirmed'   => ['checked_in' => '🏠 Check-in Sekarang', 'cancelled' => 'Batalkan'],
                    'checked_in'  => ['checked_out' => '✅ Check-out Sekarang'],
                    'checked_out' => [],
                    'cancelled'   => [],
                ];
                $btnColors = [
                    'confirmed'   => 'bg-blue-600 hover:bg-blue-700 text-white',
                    'checked_in'  => 'bg-orange-600 hover:bg-orange-700 text-white',
                    'checked_out' => 'bg-green-600 hover:bg-green-700 text-white',
                    'cancelled'   => 'bg-red-100 hover:bg-red-200 text-red-700',
                ];
            @endphp

            @if(empty($nextStatuses[$boardingBooking->status]))
                <p class="text-sm text-gray-500 text-center py-2">
                    {{ $boardingBooking->status === 'checked_out' ? '✅ Penitipan selesai' : '❌ Dibatalkan' }}
                </p>
            @else
                <div class="space-y-2">
                    @foreach($nextStatuses[$boardingBooking->status] as $val => $lbl)
                    <form action="{{ route('admin.boarding-bookings.update-status', $boardingBooking) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $val }}">
                        <button type="submit"
                            class="w-full py-2 rounded-lg text-sm font-medium transition {{ $btnColors[$val] }}">
                            {{ $lbl }}
                        </button>
                    </form>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info kucing --}}
        <div class="bg-white rounded-xl shadow-sm p-5 text-sm">
            <h3 class="font-semibold text-gray-700 mb-3">🐱 {{ $boardingBooking->cat->name }}</h3>
            <div class="space-y-1 text-sm">
                <p><span class="text-gray-500">Ras:</span> {{ $boardingBooking->cat->breed ?? '-' }}</p>
                <p><span class="text-gray-500">Berat:</span> {{ $boardingBooking->cat->weight ?? '-' }} kg</p>
                @if($boardingBooking->cat->allergies)
                <div class="mt-2 p-2 bg-red-50 rounded text-red-700 text-xs">
                    ⚠️ Alergi: {{ $boardingBooking->cat->allergies }}
                </div>
                @endif
                @if($boardingBooking->cat->special_notes)
                <div class="mt-2 p-2 bg-yellow-50 rounded text-yellow-700 text-xs">
                    📝 {{ $boardingBooking->cat->special_notes }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
