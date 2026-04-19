@extends('layouts.admin')

@section('title', 'Data Kucing')
@section('header', 'Data Kucing')

@section('content')

<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.cats.create') }}"
       class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
        + Tambah Kucing
    </a>
</div>

{{-- Grid kartu kucing --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($cats as $cat)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
        {{-- Foto kucing --}}
        <div class="h-36 bg-orange-50 flex items-center justify-center overflow-hidden">
            @if($cat->photo)
                <img src="{{ Storage::url($cat->photo) }}"
                     alt="{{ $cat->name }}"
                     class="w-full h-full object-cover">
            @else
                <span class="text-5xl">🐱</span>
            @endif
        </div>

        <div class="p-4">
            <h3 class="font-semibold text-gray-800">{{ $cat->name }}</h3>
            <p class="text-xs text-gray-500 mb-1">{{ $cat->breed ?? 'Ras tidak diketahui' }}</p>
            <p class="text-xs text-orange-600 mb-3">
                Pemilik: {{ $cat->customer->name }}
            </p>

            {{-- Badge vaksin --}}
            @if($cat->next_vaccination_date)
                @php
                    $vaccineStatus = $cat->next_vaccination_date->isPast() ? 'overdue' : 'ok';
                @endphp
                <div class="mb-3">
                    @if($vaccineStatus === 'overdue')
                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">
                            ⚠️ Vaksin terlambat
                        </span>
                    @else
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">
                            ✅ Vaksin OK
                        </span>
                    @endif
                </div>
            @endif

            <div class="flex gap-2">
                <a href="{{ route('admin.cats.show', $cat) }}"
                   class="flex-1 text-center text-xs bg-blue-50 text-blue-600 py-1.5 rounded-lg hover:bg-blue-100 transition">
                    Detail
                </a>
                <a href="{{ route('admin.cats.edit', $cat) }}"
                   class="flex-1 text-center text-xs bg-orange-50 text-orange-600 py-1.5 rounded-lg hover:bg-orange-100 transition">
                    Edit
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-4 text-center py-16 text-gray-400">
        <p class="text-5xl mb-3">🐱</p>
        <p>Belum ada data kucing.</p>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($cats->hasPages())
<div class="mt-6">{{ $cats->links() }}</div>
@endif

@endsection
