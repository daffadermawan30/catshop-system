@extends('layouts.admin')

@section('title', 'Detail Booking #' . $groomingBooking->id)
@section('header', 'Detail Booking Grooming')

@section('content')

<div class="flex justify-between items-center mb-6">
    <a href="{{ route('admin.grooming-bookings.index') }}"
       class="text-gray-500 text-sm">← Kembali</a>

    <div class="flex gap-2">
        {{-- Tombol edit hanya untuk booking yang belum selesai --}}
        @if(!in_array($groomingBooking->status, ['done', 'cancelled']))
        <a href="{{ route('admin.grooming-bookings.edit', $groomingBooking) }}"
           class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
            ✏️ Edit
        </a>
        @endif

        {{-- Tombol input catatan (hanya saat done) --}}
        @if($groomingBooking->status === 'done')
        <a href="{{ route('admin.grooming-bookings.record-form', $groomingBooking) }}"
           class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
            📝 {{ $groomingBooking->record ? 'Lihat/Edit Catatan' : 'Tambah Catatan' }}
        </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Info booking --}}
    <div class="col-span-2 space-y-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Informasi Booking</h3>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Pelanggan</p>
                    <p class="font-medium">
                        <a href="{{ route('admin.customers.show', $groomingBooking->customer) }}"
                           class="text-orange-600 hover:underline">
                            {{ $groomingBooking->customer->name }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Kucing</p>
                    <p class="font-medium">
                        <a href="{{ route('admin.cats.show', $groomingBooking->cat) }}"
                           class="text-orange-600 hover:underline">
                            {{ $groomingBooking->cat->name }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Paket</p>
                    <p class="font-medium">{{ $groomingBooking->package->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Harga</p>
                    <p class="font-bold text-orange-600 text-lg">
                        Rp {{ number_format($groomingBooking->total_price, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Jadwal</p>
                    <p class="font-medium">{{ $groomingBooking->booking_date->format('l, d M Y') }}</p>
                    <p class="text-gray-500">{{ $groomingBooking->booking_date->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    @php
                        $badgeClass = ['pending' => 'bg-yellow-100 text-yellow-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-orange-100 text-orange-700', 'done' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-500'];
                        $statusLabel = ['pending' => 'Menunggu Konfirmasi', 'confirmed' => 'Dikonfirmasi', 'in_progress' => 'Sedang Dikerjakan', 'done' => 'Selesai', 'cancelled' => 'Dibatalkan'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass[$groomingBooking->status] }}">
                        {{ $statusLabel[$groomingBooking->status] }}
                    </span>
                </div>
            </div>

            @if($groomingBooking->customer_notes)
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-500 mb-1">Catatan Pelanggan</p>
                <p class="text-sm text-gray-700">{{ $groomingBooking->customer_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Catatan hasil grooming (jika sudah ada) --}}
        @if($groomingBooking->record)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">📋 Catatan Hasil Grooming</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                @if($groomingBooking->record->condition_notes)
                <div>
                    <p class="text-gray-500">Kondisi Masuk</p>
                    <p>{{ $groomingBooking->record->condition_notes }}</p>
                </div>
                @endif
                @if($groomingBooking->record->weight_at_service)
                <div>
                    <p class="text-gray-500">Berat saat Layanan</p>
                    <p>{{ $groomingBooking->record->weight_at_service }} kg</p>
                </div>
                @endif
                @if($groomingBooking->record->products_used)
                <div>
                    <p class="text-gray-500">Produk yang Dipakai</p>
                    <p>{{ $groomingBooking->record->products_used }}</p>
                </div>
                @endif
                @if($groomingBooking->record->result_notes)
                <div class="col-span-2">
                    <p class="text-gray-500">Catatan Hasil</p>
                    <p>{{ $groomingBooking->record->result_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Foto sebelum & sesudah --}}
            @if($groomingBooking->record->photo_before || $groomingBooking->record->photo_after)
            <div class="grid grid-cols-2 gap-4 mt-4">
                @if($groomingBooking->record->photo_before)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Sebelum</p>
                    <img src="{{ Storage::url($groomingBooking->record->photo_before) }}"
                         alt="Sebelum"
                         class="w-full h-40 object-cover rounded-lg border">
                </div>
                @endif
                @if($groomingBooking->record->photo_after)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Sesudah</p>
                    <img src="{{ Storage::url($groomingBooking->record->photo_after) }}"
                         alt="Sesudah"
                         class="w-full h-40 object-cover rounded-lg border">
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Panel kontrol status --}}
    <div class="col-span-1 space-y-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4">🔄 Update Status</h3>

            @php
                // Transisi status yang boleh dilakukan dari status saat ini
                $nextStatuses = [
                    'pending'     => ['confirmed' => 'Konfirmasi', 'cancelled' => 'Batalkan'],
                    'confirmed'   => ['in_progress' => 'Mulai Grooming', 'cancelled' => 'Batalkan'],
                    'in_progress' => ['done' => 'Tandai Selesai'],
                    'done'        => [],
                    'cancelled'   => [],
                ];
                $buttonColors = [
                    'confirmed'   => 'bg-blue-600 hover:bg-blue-700 text-white',
                    'in_progress' => 'bg-orange-600 hover:bg-orange-700 text-white',
                    'done'        => 'bg-green-600 hover:bg-green-700 text-white',
                    'cancelled'   => 'bg-red-100 hover:bg-red-200 text-red-700',
                ];
            @endphp

            @if(empty($nextStatuses[$groomingBooking->status]))
                <p class="text-sm text-gray-500 text-center py-2">
                    {{ $groomingBooking->status === 'done' ? '✅ Grooming selesai' : '❌ Booking dibatalkan' }}
                </p>
            @else
                <div class="space-y-2">
                    @foreach($nextStatuses[$groomingBooking->status] as $statusVal => $label)
                    <form action="{{ route('admin.grooming-bookings.update-status', $groomingBooking) }}"
                          method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $statusVal }}">
                        <button type="submit"
                            class="w-full py-2 rounded-lg text-sm font-medium transition {{ $buttonColors[$statusVal] }}">
                            {{ $label }}
                        </button>
                    </form>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info kucing --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-3">🐱 Info Kucing</h3>
            <div class="text-sm space-y-1">
                <p><span class="text-gray-500">Ras:</span> {{ $groomingBooking->cat->breed ?? '-' }}</p>
                <p><span class="text-gray-500">Berat:</span> {{ $groomingBooking->cat->weight ?? '-' }} kg</p>
                @if($groomingBooking->cat->allergies)
                <div class="mt-2 p-2 bg-red-50 rounded text-red-700 text-xs">
                    ⚠️ {{ $groomingBooking->cat->allergies }}
                </div>
                @endif
                @if($groomingBooking->cat->special_notes)
                <div class="mt-2 p-2 bg-yellow-50 rounded text-yellow-700 text-xs">
                    📝 {{ $groomingBooking->cat->special_notes }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
