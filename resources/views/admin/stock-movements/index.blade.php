@extends('layouts.admin')
@section('title', 'Riwayat Stok')
@section('header', 'Riwayat Pergerakan Stok')

@section('content')
<div class="flex justify-between items-center mb-6">
    <form method="GET" class="flex gap-2">
        <select name="product_id"
            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400">
            <option value="">Semua Produk</option>
            @foreach($products as $p)
            <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                {{ $p->name }}
            </option>
            @endforeach
        </select>
        <select name="type" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400">
            <option value="">Semua Tipe</option>
            <option value="in"         {{ request('type') === 'in' ? 'selected' : '' }}>📦 Masuk</option>
            <option value="out"        {{ request('type') === 'out' ? 'selected' : '' }}>📤 Keluar</option>
            <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>🔧 Penyesuaian</option>
        </select>
        <button type="submit" class="bg-gray-100 border px-3 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <a href="{{ route('admin.stock-movements.create') }}"
       class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
        + Input Stok
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Waktu</th>
                <th class="px-4 py-3 text-left font-medium">Produk</th>
                <th class="px-4 py-3 text-left font-medium">Tipe</th>
                <th class="px-4 py-3 text-right font-medium">Jumlah</th>
                <th class="px-4 py-3 text-right font-medium">Sebelum</th>
                <th class="px-4 py-3 text-right font-medium">Sesudah</th>
                <th class="px-4 py-3 text-left font-medium">Oleh</th>
                <th class="px-4 py-3 text-left font-medium">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($movements as $mov)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                    {{ $mov->created_at->format('d M Y H:i') }}
                </td>
                <td class="px-4 py-3 font-medium text-gray-800">
                    <a href="{{ route('admin.products.show', $mov->product) }}"
                       class="hover:text-orange-600">{{ $mov->product->name }}</a>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $mov->type === 'in' ? 'bg-green-100 text-green-700' :
                           ($mov->type === 'out' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ $mov->type_label }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right font-medium
                           {{ $mov->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $mov->type === 'in' ? '+' : '-' }}{{ $mov->quantity }}
                </td>
                <td class="px-4 py-3 text-right text-gray-500">{{ $mov->stock_before }}</td>
                <td class="px-4 py-3 text-right font-medium">{{ $mov->stock_after }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $mov->user->name }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $mov->notes ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-10 text-gray-400">
                    Belum ada riwayat pergerakan stok.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($movements->hasPages())
    <div class="px-4 py-3 border-t">{{ $movements->links() }}</div>
    @endif
</div>
@endsection
