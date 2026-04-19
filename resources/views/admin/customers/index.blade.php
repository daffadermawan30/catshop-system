@extends('layouts.admin')

@section('title', 'Pelanggan')
@section('header', 'Manajemen Pelanggan')

@section('content')

{{-- Baris tombol tambah + search --}}
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.customers.create') }}"
       class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700 transition flex items-center gap-2">
        <span>+</span> Tambah Pelanggan
    </a>

    {{-- Form search sederhana --}}
    <form method="GET" class="flex gap-2">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama atau email..."
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-orange-400"
        >
        <button type="submit"
            class="bg-gray-100 border border-gray-300 px-3 py-2 rounded-lg text-sm hover:bg-gray-200">
            Cari
        </button>
    </form>
</div>

{{-- Tabel pelanggan --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-6 py-3 text-left font-medium">No</th>
                <th class="px-6 py-3 text-left font-medium">Nama</th>
                <th class="px-6 py-3 text-left font-medium">Email</th>
                <th class="px-6 py-3 text-left font-medium">No. HP</th>
                <th class="px-6 py-3 text-left font-medium">Jumlah Kucing</th>
                <th class="px-6 py-3 text-left font-medium">Terdaftar</th>
                <th class="px-6 py-3 text-left font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($customers as $customer)
            <tr class="hover:bg-gray-50 transition">
                {{-- Nomor urut yang tetap benar saat pagination --}}
                <td class="px-6 py-4 text-gray-500">
                    {{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}
                </td>
                <td class="px-6 py-4 font-medium text-gray-800">
                    {{ $customer->name }}
                </td>
                <td class="px-6 py-4 text-gray-600">
                    {{ $customer->user->email ?? '-' }}
                </td>
                <td class="px-6 py-4 text-gray-600">
                    {{ $customer->phone ?? '-' }}
                </td>
                <td class="px-6 py-4">
                    <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded-full">
                        {{ $customer->cats->count() }} kucing
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-500 text-xs">
                    {{ $customer->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        {{-- Tombol detail --}}
                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="text-blue-600 hover:underline text-xs">Detail</a>
                        {{-- Tombol edit --}}
                        <a href="{{ route('admin.customers.edit', $customer) }}"
                           class="text-orange-600 hover:underline text-xs">Edit</a>
                        {{-- Tombol hapus dengan konfirmasi --}}
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                              onsubmit="return confirm('Hapus pelanggan {{ $customer->name }}? Semua data kucingnya juga akan terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-xs">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            {{-- Empty state jika tidak ada data --}}
            <tr>
                <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                    <p class="text-4xl mb-2">👥</p>
                    <p>Belum ada pelanggan terdaftar.</p>
                    <a href="{{ route('admin.customers.create') }}"
                       class="text-orange-600 hover:underline text-sm mt-2 inline-block">
                        + Tambah pelanggan pertama
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination links --}}
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $customers->links() }}
    </div>
    @endif
</div>

@endsection
