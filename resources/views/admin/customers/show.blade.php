@extends('layouts.admin')

@section('title', $customer->name)
@section('header', 'Detail Pelanggan')

@section('content')

<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.customers.index') }}"
       class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
        ← Kembali
    </a>
    <a href="{{ route('admin.customers.edit', $customer) }}"
       class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
        ✏️ Edit Data
    </a>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Kolom kiri: Info pelanggan --}}
    <div class="col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-4">
                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-3">
                    👤
                </div>
                <h2 class="font-bold text-gray-800 text-lg">{{ $customer->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $customer->user->email }}</p>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">No. HP</span>
                    <span class="text-gray-800">{{ $customer->phone ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jenis Kelamin</span>
                    <span class="text-gray-800">
                        {{ $customer->gender === 'male' ? 'Laki-laki' : ($customer->gender === 'female' ? 'Perempuan' : '-') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Terdaftar</span>
                    <span class="text-gray-800">{{ $customer->created_at->format('d M Y') }}</span>
                </div>
            </div>

            @if($customer->address)
            <div class="mt-3 pt-3 border-t text-sm">
                <p class="text-gray-500 mb-1">Alamat</p>
                <p class="text-gray-800">{{ $customer->address }}</p>
            </div>
            @endif
        </div>

        {{-- Statistik ringkasan --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-3">Ringkasan</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">🐱 Jumlah Kucing</span>
                    <span class="font-medium">{{ $customer->cats->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">✂️ Total Grooming</span>
                    <span class="font-medium">{{ $customer->groomingBookings->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">🏠 Total Penitipan</span>
                    <span class="font-medium">{{ $customer->boardingBookings->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom kanan: Daftar kucing --}}
    <div class="col-span-2 space-y-6">

        {{-- Daftar kucing milik pelanggan ini --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700">🐱 Kucing</h3>
                {{-- Tombol tambah kucing baru untuk pelanggan ini --}}
                <a href="{{ route('admin.cats.create', ['customer_id' => $customer->id]) }}"
                   class="text-orange-600 text-sm hover:underline">
                    + Tambah Kucing
                </a>
            </div>

            @if($customer->cats->isEmpty())
                <p class="text-gray-400 text-sm text-center py-4">
                    Belum ada kucing terdaftar.
                </p>
            @else
                <div class="grid grid-cols-2 gap-4">
                    @foreach($customer->cats as $cat)
                    <a href="{{ route('admin.cats.show', $cat) }}"
                       class="flex items-center gap-3 p-3 border rounded-lg hover:bg-orange-50 hover:border-orange-200 transition">
                        {{-- Foto kucing atau placeholder --}}
                        @if($cat->photo)
                            <img src="{{ Storage::url($cat->photo) }}"
                                 alt="{{ $cat->name }}"
                                 class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-xl">
                                🐱
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-800 text-sm">{{ $cat->name }}</p>
                            <p class="text-gray-500 text-xs">
                                {{ $cat->breed ?? 'Ras tidak diketahui' }}
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Riwayat grooming terbaru --}}
        @if($customer->groomingBookings->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">✂️ Riwayat Grooming Terbaru</h3>
            <div class="space-y-2">
                @foreach($customer->groomingBookings->take(5) as $booking)
                <div class="flex items-center justify-between text-sm py-2 border-b last:border-0">
                    <div>
                        <span class="font-medium">{{ $booking->cat->name }}</span>
                        <span class="text-gray-500"> — {{ $booking->package->name }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-gray-500 text-xs">
                            {{ $booking->booking_date->format('d M Y') }}
                        </span>
                        @php
                            $colors = ['done' => 'bg-green-100 text-green-700', 'pending' => 'bg-yellow-100 text-yellow-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'cancelled' => 'bg-red-100 text-red-700'];
                        @endphp
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $colors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
