@extends('layouts.admin')
@section('title', 'Produk')
@section('header', 'Manajemen Produk')

@section('content')
{{-- Alert stok menipis --}}
@if($lowStockCount > 0)
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4 flex items-center gap-3">
    <span class="text-yellow-600 text-xl">⚠️</span>
    <div>
        <p class="text-sm font-medium text-yellow-800">
            {{ $lowStockCount }} produk stok menipis!
        </p>
        <a href="{{ route('admin.products.index', ['low_stock' => 1]) }}"
           class="text-xs text-yellow-700 underline">Lihat produk stok menipis</a>
    </div>
</div>
@endif

<div class="flex flex-wrap gap-3 mb-6 justify-between items-center">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama, SKU, barcode..."
            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 w-56">
        <select name="category" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-100 border px-3 py-2 rounded-lg text-sm">Filter</button>
        @if(request()->hasAny(['search', 'category', 'low_stock']))
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 my-auto hover:text-gray-700">✕ Reset</a>
        @endif
    </form>

    <div class="flex gap-2">
        <a href="{{ route('admin.stock-movements.create') }}"
           class="bg-white border text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
            📦 Input Stok
        </a>
        <a href="{{ route('admin.products.create') }}"
           class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
            + Tambah Produk
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Produk</th>
                <th class="px-4 py-3 text-left font-medium">Kategori</th>
                <th class="px-4 py-3 text-left font-medium">Harga Jual</th>
                <th class="px-4 py-3 text-left font-medium">Stok</th>
                <th class="px-4 py-3 text-left font-medium">SKU</th>
                <th class="px-4 py-3 text-left font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        @if($product->photo)
                            <img src="{{ Storage::url($product->photo) }}"
                                 alt="{{ $product->name }}"
                                 class="w-10 h-10 rounded-lg object-cover border">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-xl">
                                🐱
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-800">{{ $product->name }}</p>
                            @if($product->barcode)
                            <p class="text-xs text-gray-400 font-mono">{{ $product->barcode }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    {{ $product->category?->name ?? '-' }}
                </td>
                <td class="px-4 py-3 font-medium text-gray-800">
                    {{ $product->formatted_price }}
                </td>
                <td class="px-4 py-3">
                    <span class="font-medium {{ $product->isLowStock() ? 'text-red-600' : 'text-gray-800' }}">
                        {{ $product->stock }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $product->unit }}</span>
                    @if($product->isLowStock())
                        <span class="block text-xs text-red-500">⚠️ Stok menipis!</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $product->sku }}</td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('admin.products.show', $product) }}"
                       class="text-gray-600 hover:underline text-xs">Detail</a>
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Nonaktifkan produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Nonaktif</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <p class="text-4xl mb-2">📦</p>
                    <p>Belum ada produk.</p>
                    <a href="{{ route('admin.products.create') }}"
                       class="text-orange-600 hover:underline text-sm mt-1 inline-block">+ Tambah produk pertama</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($products->hasPages())
    <div class="px-4 py-3 border-t">{{ $products->links() }}</div>
    @endif
</div>
@endsection
