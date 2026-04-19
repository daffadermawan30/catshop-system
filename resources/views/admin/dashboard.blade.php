@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')

{{-- Kartu statistik ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Total Pelanggan --}}
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="text-4xl">👥</div>
        <div>
            <p class="text-sm text-gray-500">Total Pelanggan</p>
            <p class="text-2xl font-bold text-gray-800">{{ $total_customers }}</p>
        </div>
    </div>

    {{-- Total Kucing --}}
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="text-4xl">🐱</div>
        <div>
            <p class="text-sm text-gray-500">Total Kucing</p>
            <p class="text-2xl font-bold text-gray-800">{{ $total_cats }}</p>
        </div>
    </div>

    {{-- Grooming Hari Ini --}}
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="text-4xl">✂️</div>
        <div>
            <p class="text-sm text-gray-500">Grooming Hari Ini</p>
            <p class="text-2xl font-bold text-orange-600">{{ $grooming_today }}</p>
        </div>
    </div>

    {{-- Kucing Dititip --}}
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="text-4xl">🏠</div>
        <div>
            <p class="text-sm text-gray-500">Sedang Dititip</p>
            <p class="text-2xl font-bold text-blue-600">{{ $boarding_active }}</p>
        </div>
    </div>

</div>

{{-- Tabel booking terbaru --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-semibold text-gray-700 mb-4">Booking Grooming Terbaru</h3>

    @if($recent_grooming->isEmpty())
        <div class="text-center py-8 text-gray-400">
            <p class="text-4xl mb-2">📋</p>
            <p>Belum ada booking grooming.</p>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-3">Pelanggan</th>
                    <th class="pb-3">Kucing</th>
                    <th class="pb-3">Paket</th>
                    <th class="pb-3">Jadwal</th>
                    <th class="pb-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($recent_grooming as $booking)
                <tr>
                    <td class="py-3">{{ $booking->customer->name }}</td>
                    <td class="py-3">{{ $booking->cat->name }}</td>
                    <td class="py-3">{{ $booking->package->name }}</td>
                    <td class="py-3">{{ $booking->booking_date->format('d M Y, H:i') }}</td>
                    <td class="py-3">
                        {{-- Badge warna berdasarkan status --}}
                        @php
                            $colors = [
                                'pending'     => 'bg-yellow-100 text-yellow-700',
                                'confirmed'   => 'bg-blue-100 text-blue-700',
                                'in_progress' => 'bg-orange-100 text-orange-700',
                                'done'        => 'bg-green-100 text-green-700',
                                'cancelled'   => 'bg-red-100 text-red-700',
                            ];
                            $labels = [
                                'pending'     => 'Menunggu',
                                'confirmed'   => 'Dikonfirmasi',
                                'in_progress' => 'Dikerjakan',
                                'done'        => 'Selesai',
                                'cancelled'   => 'Dibatalkan',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs {{ $colors[$booking->status] }}">
                            {{ $labels[$booking->status] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
