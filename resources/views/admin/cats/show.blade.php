@extends('layouts.admin')

@section('title', $cat->name)
@section('header', 'Detail Kucing')

@section('content')

<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.cats.index') }}"
       class="text-gray-500 hover:text-gray-700 text-sm">← Kembali</a>
    <div class="flex gap-2">
        <a href="{{ route('admin.cats.edit', $cat) }}"
           class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
            ✏️ Edit
        </a>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Kartu profil kucing --}}
    <div class="col-span-1 space-y-4">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            {{-- Foto --}}
            <div class="h-48 bg-orange-50 flex items-center justify-center overflow-hidden">
                @if($cat->photo)
                    <img src="{{ Storage::url($cat->photo) }}"
                         alt="{{ $cat->name }}"
                         class="w-full h-full object-cover">
                @else
                    <span class="text-7xl">🐱</span>
                @endif
            </div>

            <div class="p-5">
                <h2 class="text-xl font-bold text-gray-800">{{ $cat->name }}</h2>
                <p class="text-gray-500 text-sm mb-4">{{ $cat->breed ?? 'Ras tidak diketahui' }}</p>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pemilik</span>
                        <a href="{{ route('admin.customers.show', $cat->customer) }}"
                           class="text-orange-600 hover:underline">
                            {{ $cat->customer->name }}
                        </a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jenis Kelamin</span>
                        <span>{{ $cat->gender === 'male' ? '♂ Jantan' : ($cat->gender === 'female' ? '♀ Betina' : '-') }}</span>
                    </div>
                    @if($cat->date_of_birth)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Umur</span>
                        <span>{{ $cat->date_of_birth->diffInYears(now()) }} tahun</span>
                    </div>
                    @endif
                    @if($cat->weight)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Berat</span>
                        <span>{{ $cat->weight }} kg</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Steril</span>
                        <span>{{ $cat->is_sterilized ? '✅ Ya' : '❌ Belum' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi kesehatan --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-3">🏥 Kesehatan</h3>

            @if($cat->next_vaccination_date)
            <div class="mb-3 p-3 rounded-lg {{ $cat->next_vaccination_date->isPast() ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
                <p class="text-xs font-medium {{ $cat->next_vaccination_date->isPast() ? 'text-red-700' : 'text-green-700' }}">
                    {{ $cat->next_vaccination_date->isPast() ? '⚠️ Vaksin Terlambat!' : '💉 Jadwal Vaksin Berikutnya' }}
                </p>
                <p class="text-sm font-bold mt-1">
                    {{ $cat->next_vaccination_date->format('d M Y') }}
                </p>
            </div>
            @endif

            @if($cat->allergies)
            <div class="mb-3">
                <p class="text-xs text-gray-500 mb-1">⚠️ Alergi</p>
                <p class="text-sm text-red-700 bg-red-50 p-2 rounded">{{ $cat->allergies }}</p>
            </div>
            @endif

            @if($cat->special_notes)
            <div>
                <p class="text-xs text-gray-500 mb-1">📝 Catatan Khusus</p>
                <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded">{{ $cat->special_notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Kolom kanan: Riwayat layanan --}}
    <div class="col-span-2 space-y-6">

        {{-- Riwayat grooming --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">✂️ Riwayat Grooming</h3>
            @if($cat->groomingBookings->isEmpty())
                <p class="text-gray-400 text-sm text-center py-6">Belum ada riwayat grooming.</p>
            @else
                <div class="space-y-2">
                    @foreach($cat->groomingBookings as $booking)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg text-sm">
                        <div>
                            <p class="font-medium">{{ $booking->package->name }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->booking_date->format('d M Y, H:i') }}</p>
                        </div>
                        @php
                            $statusColor = ['done' => 'text-green-600', 'pending' => 'text-yellow-600', 'cancelled' => 'text-red-600', 'confirmed' => 'text-blue-600'];
                            $statusLabel = ['done' => 'Selesai', 'pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'cancelled' => 'Dibatalkan'];
                        @endphp
                        <span class="text-xs font-medium {{ $statusColor[$booking->status] ?? 'text-gray-600' }}">
                            {{ $statusLabel[$booking->status] ?? $booking->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Riwayat penitipan --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">🏠 Riwayat Penitipan</h3>
            @if($cat->boardingBookings->isEmpty())
                <p class="text-gray-400 text-sm text-center py-6">Belum ada riwayat penitipan.</p>
            @else
                <div class="space-y-2">
                    @foreach($cat->boardingBookings as $boarding)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg text-sm">
                        <div>
                            <p class="font-medium">{{ $boarding->room->roomType->name }} — Kamar {{ $boarding->room->room_number }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $boarding->checkin_date->format('d M Y') }} →
                                {{ $boarding->checkout_date->format('d M Y') }}
                                ({{ $boarding->duration }} malam)
                            </p>
                        </div>
                        <span class="text-xs font-medium text-blue-600">
                            {{ ucfirst($boarding->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
