@extends('layouts.admin')
@section('title', 'Kamar Penitipan')
@section('header', 'Manajemen Kamar')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div class="flex gap-4 text-sm">
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-green-400 inline-block"></span>
            <span class="text-gray-600">Tersedia</span>
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-orange-400 inline-block"></span>
            <span class="text-gray-600">Dihuni</span>
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-gray-400 inline-block"></span>
            <span class="text-gray-600">Maintenance</span>
        </span>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.room-types.index') }}"
           class="bg-white border text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
            📋 Tipe Kamar
        </a>
        <a href="{{ route('admin.rooms.create') }}"
           class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
            + Tambah Kamar
        </a>
    </div>
</div>

{{-- Grid kamar dikelompokkan per tipe --}}
@foreach($roomsByType as $typeId => $typeRooms)
@php $type = $roomTypes[$typeId] ?? null; @endphp

<div class="mb-8">
    <div class="flex items-center gap-3 mb-3">
        <h3 class="font-semibold text-gray-700">{{ $type?->name ?? 'Tipe Tidak Diketahui' }}</h3>
        <span class="text-sm text-gray-500">{{ $type?->formatted_price }}/malam</span>
        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
            {{ $typeRooms->where('status', 'available')->count() }} / {{ $typeRooms->count() }} tersedia
        </span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
        @foreach($typeRooms as $room)
        @php
            $statusStyle = [
                'available'   => 'border-green-300 bg-green-50',
                'occupied'    => 'border-orange-300 bg-orange-50',
                'maintenance' => 'border-gray-300 bg-gray-50',
            ][$room->status];
            $dotColor = [
                'available'   => 'bg-green-400',
                'occupied'    => 'bg-orange-400',
                'maintenance' => 'bg-gray-400',
            ][$room->status];
        @endphp
        <div class="border-2 rounded-xl p-3 {{ $statusStyle }} relative">
            <div class="flex justify-between items-start mb-2">
                <span class="font-bold text-gray-800 text-sm">{{ $room->room_number }}</span>
                <span class="w-2.5 h-2.5 rounded-full {{ $dotColor }} mt-0.5"></span>
            </div>

            @if($room->activeBooking)
            <p class="text-xs text-orange-700 truncate">
                🐱 {{ $room->activeBooking->cat->name }}
            </p>
            @else
            <p class="text-xs text-gray-400">
                {{ $room->status === 'maintenance' ? 'Maintenance' : 'Kosong' }}
            </p>
            @endif

            <div class="flex gap-1 mt-2">
                <a href="{{ route('admin.rooms.edit', $room) }}"
                   class="text-xs text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                      onsubmit="return confirm('Nonaktifkan kamar {{ $room->room_number }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach

@if($rooms->isEmpty())
<div class="text-center py-12 text-gray-400">
    <p class="text-4xl mb-2">🏠</p>
    <p>Belum ada kamar terdaftar.</p>
    <a href="{{ route('admin.rooms.create') }}"
       class="text-orange-600 hover:underline text-sm mt-2 inline-block">+ Tambah kamar pertama</a>
</div>
@endif

@endsection
