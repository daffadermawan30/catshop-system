@extends('layouts.admin')

@section('title', 'Booking Grooming')
@section('header', 'Manajemen Booking Grooming')

@section('content')

{{-- Tombol aksi atas --}}
<div class="flex justify-between items-center mb-6">
    <div class="flex gap-2">
        <a href="{{ route('admin.grooming-bookings.create') }}"
           class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
            + Booking Baru
        </a>
        <a href="{{ route('admin.grooming-calendar') }}"
           class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            📅 Lihat Kalender
        </a>
    </div>

    {{-- Filter tanggal --}}
    <form method="GET" class="flex gap-2 items-center">
        <input type="date" name="date" value="{{ request('date') }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400">
        <button type="submit" class="bg-gray-100 border px-3 py-2 rounded-lg text-sm">Filter</button>
        @if(request('date'))
            <a href="{{ route('admin.grooming-bookings.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">✕ Reset</a>
        @endif
    </form>
</div>

{{-- Tab filter status --}}
@php
    $statuses = [
        ''            => 'Semua',
        'pending'     => 'Menunggu',
        'confirmed'   => 'Dikonfirmasi',
        'in_progress' => 'Dikerjakan',
        'done'        => 'Selesai',
        'cancelled'   => 'Dibatalkan',
    ];
    $statusColors = [
        'pending'     => 'text-yellow-600',
        'confirmed'   => 'text-blue-600',
        'in_progress' => 'text-orange-600',
        'done'        => 'text-green-600',
        'cancelled'   => 'text-red-500',
    ];
@endphp

<div class="flex gap-2 mb-4 flex-wrap">
    @foreach($statuses as $val => $label)
    <a href="{{ route('admin.grooming-bookings.index', array_merge(request()->query(), ['status' => $val])) }}"
       class="px-3 py-1.5 rounded-lg text-sm border transition
              {{ request('status') === $val ? 'bg-orange-600 text-white border-orange-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
        {{ $label }}
        @if($val && isset($statusCounts[$val]))
            <span class="ml-1 text-xs {{ request('status') === $val ? 'text-orange-200' : $statusColors[$val] ?? '' }}">
                ({{ $statusCounts[$val] }})
            </span>
        @endif
    </a>
    @endforeach
</div>

{{-- Tabel booking --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Pelanggan / Kucing</th>
                <th class="px-4 py-3 text-left font-medium">Paket</th>
                <th class="px-4 py-3 text-left font-medium">Jadwal</th>
                <th class="px-4 py-3 text-left font-medium">Harga</th>
                <th class="px-4 py-3 text-left font-medium">Status</th>
                <th class="px-4 py-3 text-left font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $booking->customer->name }}</p>
                    <p class="text-xs text-orange-600">🐱 {{ $booking->cat->name }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $booking->package->name }}</td>
                <td class="px-4 py-3">
                    <p class="text-gray-800">{{ $booking->booking_date->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->booking_date->format('H:i') }}</p>
                </td>
                <td class="px-4 py-3 text-gray-700 font-medium">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3">
                    @php
                        $badgeClass = [
                            'pending'     => 'bg-yellow-100 text-yellow-700',
                            'confirmed'   => 'bg-blue-100 text-blue-700',
                            'in_progress' => 'bg-orange-100 text-orange-700',
                            'done'        => 'bg-green-100 text-green-700',
                            'cancelled'   => 'bg-red-100 text-red-500',
                        ];
                        $statusLabel = [
                            'pending'     => 'Menunggu',
                            'confirmed'   => 'Dikonfirmasi',
                            'in_progress' => 'Dikerjakan',
                            'done'        => 'Selesai',
                            'cancelled'   => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="text-xs px-2 py-1 rounded-full {{ $badgeClass[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabel[$booking->status] ?? $booking->status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.grooming-bookings.show', $booking) }}"
                       class="text-blue-600 hover:underline text-xs">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <p class="text-4xl mb-2">✂️</p>
                    <p>Belum ada booking grooming.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($bookings->hasPages())
    <div class="px-4 py-3 border-t">{{ $bookings->links() }}</div>
    @endif
</div>

@endsection
