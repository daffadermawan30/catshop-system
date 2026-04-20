@extends('layouts.admin')
@section('title', 'Booking Penitipan')
@section('header', 'Manajemen Penitipan Kucing')

@section('content')

{{-- Stat bar kamar tersedia --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-green-400">
        <p class="text-xs text-gray-500">Kamar Tersedia</p>
        <p class="text-2xl font-bold text-green-600">{{ $availableRooms }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-orange-400">
        <p class="text-xs text-gray-500">Sedang Dititipkan</p>
        <p class="text-2xl font-bold text-orange-600">{{ $statusCounts['checked_in'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-blue-400">
        <p class="text-xs text-gray-500">Booking Dikonfirmasi</p>
        <p class="text-2xl font-bold text-blue-600">{{ $statusCounts['confirmed'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-yellow-400">
        <p class="text-xs text-gray-500">Menunggu Konfirmasi</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $statusCounts['pending'] ?? 0 }}</p>
    </div>
</div>

<div class="flex justify-between items-center mb-4">
    <div class="flex gap-2">
        <a href="{{ route('admin.boarding-bookings.create') }}"
           class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
            + Booking Baru
        </a>
        <a href="{{ route('admin.boarding-calendar') }}"
           class="bg-white border text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
            📅 Kalender Kamar
        </a>
        <a href="{{ route('admin.rooms.index') }}"
           class="bg-white border text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
            🏠 Status Kamar
        </a>
    </div>

    <form method="GET" class="flex gap-2">
        <input type="date" name="date" value="{{ request('date') }}"
            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400">
        <button type="submit" class="bg-gray-100 border px-3 py-2 rounded-lg text-sm">Filter</button>
        @if(request('date'))
        <a href="{{ route('admin.boarding-bookings.index') }}" class="text-sm text-gray-500 hover:text-gray-700 my-auto">✕</a>
        @endif
    </form>
</div>

{{-- Tab filter status --}}
@php
    $statuses = ['' => 'Semua', 'pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'checked_in' => 'Dititipkan', 'checked_out' => 'Selesai', 'cancelled' => 'Dibatalkan'];
@endphp
<div class="flex gap-2 mb-4 flex-wrap">
    @foreach($statuses as $val => $label)
    <a href="{{ route('admin.boarding-bookings.index', array_merge(request()->query(), ['status' => $val])) }}"
       class="px-3 py-1.5 rounded-lg text-sm border transition
              {{ request('status') === $val ? 'bg-orange-600 text-white border-orange-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
        {{ $label }}
        @if($val && isset($statusCounts[$val]))
            <span class="ml-1 text-xs {{ request('status') === $val ? 'opacity-70' : 'text-gray-400' }}">
                ({{ $statusCounts[$val] }})
            </span>
        @endif
    </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Pelanggan / Kucing</th>
                <th class="px-4 py-3 text-left font-medium">Kamar</th>
                <th class="px-4 py-3 text-left font-medium">Check-in</th>
                <th class="px-4 py-3 text-left font-medium">Check-out</th>
                <th class="px-4 py-3 text-left font-medium">Durasi</th>
                <th class="px-4 py-3 text-left font-medium">Total</th>
                <th class="px-4 py-3 text-left font-medium">Status</th>
                <th class="px-4 py-3 text-left font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($bookings as $booking)
            @php
                $badge = ['pending' => 'bg-yellow-100 text-yellow-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'checked_in' => 'bg-orange-100 text-orange-700', 'checked_out' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-500'];
                $label = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'checked_in' => 'Dititipkan', 'checked_out' => 'Selesai', 'cancelled' => 'Dibatalkan'];
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $booking->customer->name }}</p>
                    <p class="text-xs text-orange-600">🐱 {{ $booking->cat->name }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600">
                    <p class="font-medium">{{ $booking->room->room_number }}</p>
                    <p class="text-xs text-gray-400">{{ $booking->room->roomType->name }}</p>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $booking->check_in_date->format('d M Y') }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $booking->check_out_date->format('d M Y') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $booking->duration_days }} hari</td>
                <td class="px-4 py-3 font-medium text-gray-800">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-1 rounded-full {{ $badge[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $label[$booking->status] ?? $booking->status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.boarding-bookings.show', $booking) }}"
                       class="text-blue-600 hover:underline text-xs">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-12 text-gray-400">
                    <p class="text-4xl mb-2">🏠</p>
                    <p>Belum ada booking penitipan.</p>
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
