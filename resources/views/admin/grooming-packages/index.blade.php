@extends('layouts.admin')

@section('title', 'Paket Grooming')
@section('header', 'Paket Grooming')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $packages->count() }} paket terdaftar</p>
    <a href="{{ route('admin.grooming-packages.create') }}"
       class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
        + Tambah Paket
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($packages as $package)
    <div class="bg-white rounded-xl shadow-sm p-5 {{ !$package->is_active ? 'opacity-60' : '' }}">
        <div class="flex justify-between items-start mb-3">
            <h3 class="font-semibold text-gray-800">{{ $package->name }}</h3>
            @if(!$package->is_active)
                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Nonaktif</span>
            @else
                <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">Aktif</span>
            @endif
        </div>

        @if($package->description)
        <p class="text-sm text-gray-500 mb-3">{{ $package->description }}</p>
        @endif

        <div class="flex items-center justify-between text-sm mb-4">
            <span class="text-orange-600 font-bold text-lg">{{ $package->formatted_price }}</span>
            <span class="text-gray-400">⏱ {{ $package->duration_minutes }} menit</span>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.grooming-packages.edit', $package) }}"
               class="flex-1 text-center text-xs bg-orange-50 text-orange-600 py-2 rounded-lg hover:bg-orange-100 transition">
                Edit
            </a>
            <form action="{{ route('admin.grooming-packages.destroy', $package) }}" method="POST"
                  onsubmit="return confirm('Nonaktifkan paket {{ $package->name }}?')">
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
        <p class="text-4xl mb-2">✂️</p>
        <p>Belum ada paket grooming.</p>
    </div>
    @endforelse
</div>

@endsection
