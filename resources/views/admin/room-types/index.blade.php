@extends('layouts.admin')
@section('title', 'Tipe Kamar')
@section('header', 'Tipe Kamar Penitipan')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $roomTypes->count() }} tipe kamar terdaftar</p>
    <a href="{{ route('admin.room-types.create') }}"
       class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
        + Tambah Tipe Kamar
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($roomTypes as $type)
    <div class="bg-white rounded-xl shadow-sm p-5 {{ !$type->is_active ? 'opacity-60' : '' }}">

        <div class="flex justify-between items-start mb-3">
            <h3 class="font-semibold text-gray-800 text-lg">{{ $type->name }}</h3>
            @if($type->is_active)
                <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">Aktif</span>
            @else
                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Nonaktif</span>
            @endif
        </div>

        @if($type->description)
        <p class="text-sm text-gray-500 mb-3">{{ $type->description }}</p>
        @endif

        @if($type->facilities)
        <p class="text-xs text-gray-400 mb-3">🛋️ {{ $type->facilities }}</p>
        @endif

        <div class="flex items-center justify-between mb-4">
            <span class="text-orange-600 font-bold text-lg">{{ $type->formatted_price }}</span>
            <span class="text-gray-500 text-sm">per malam</span>
        </div>

        {{-- Stat kamar --}}
        <div class="flex gap-3 text-xs mb-4">
            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                Total: {{ $type->rooms_count }} kamar
            </span>
            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full">
                Tersedia: {{ $type->available_count }}
            </span>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.room-types.edit', $type) }}"
               class="flex-1 text-center text-xs bg-orange-50 text-orange-600 py-2 rounded-lg hover:bg-orange-100 transition">
                ✏️ Edit
            </a>
            <form action="{{ route('admin.room-types.destroy', $type) }}" method="POST"
                  onsubmit="return confirm('Nonaktifkan tipe kamar {{ $type->name }}?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="text-xs bg-red-50 text-red-500 py-2 px-3 rounded-lg hover:bg-red-100 transition">
                    Nonaktifkan
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">
        <p class="text-4xl mb-2">🏠</p>
        <p class="mb-2">Belum ada tipe kamar.</p>
        <a href="{{ route('admin.room-types.create') }}"
           class="text-orange-600 hover:underline text-sm">+ Tambah tipe kamar pertama</a>
    </div>
    @endforelse
</div>

@endsection
