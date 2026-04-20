@extends('layouts.admin')
@section('title', $product->name)
@section('header', $product->name)

@section('content')
<div class="grid grid-cols-3 gap-6">
    {{-- Info Produk --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        @if($product->photo)
        <img src="{{ Storage::url($product->photo) }}"
             alt="{{ $product->name }}"
             class="w-full h-40 object-cover rounded-lg border mb-4">
        @endif
        <h2 class="font-semibold text-gray-800 text-lg mb-1">{{ $product->name }}</h2>
        <p class="text-xs text-gray-400 mb-3">{{ $product->category?->name ?? 'Tanpa Kategori' }}</p>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Harga Jual</span>
                <span class="font-bold text-orange-600">{{ $product->formatted_price }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Harga Beli</span>
                <span>Rp {{ number_format($product->buy_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Stok</span>
                <span class="font-bold {{ $product->isLowStock() ? 'text-red-600' : 'text-green-600' }}">
                    {{ $product->stock }} {{ $product->unit }}
                    @if($product->isLowStock()) ⚠️ @endif
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Stok Min</span>
                <span>{{ $product->stock_min }} {{ $product->unit }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">SKU</span>
                <span class="font-mono text-xs">{{ $product->sku }}</span>
            </div>
            @if($product->barcode)
            <div class="flex justify-between">
                <span class="text-gray-500">Barcode</span>
                <span class="font-mono text-xs">{{ $product->barcode }}</span>
            </div>
            @endif
        </div>

        <div class="flex gap-2 mt-4">
            <a href="{{ route('admin.products.edit', $product) }}"
               class="flex-1 text-center bg-orange-50 text-orange-600 py-2 rounded-lg text-xs hover:bg-orange-100">
                ✏️ Edit
            </a>
            <a href="{{ route('admin.stock-movements.create', ['product_id' => $product->id]) }}"
               class="flex-1 text-center bg-green-50 text-green-600 py-2 rounded-lg text-xs hover:bg-green-100">
                📦 Input Stok
            </a>
        </div>
    </div>

    {{-- Riwayat Pergerakan Stok --}}
    <div class="col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b">
            <h3 class="font-semibold text-gray-700">Riwayat Pergerakan Stok</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">Tanggal</th>
                    <th class="px-4 py-2 text-left font-medium">Tipe</th>
                    <th class="px-4 py-2 text-right font-medium">Jumlah</th>
                    <th class="px-4 py-2 text-right font-medium">Sebelum</th>
                    <th class="px-4 py-2 text-right font-medium">Sesudah</th>
                    <th class="px-4 py-2 text-left font-medium">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($movements as $mov)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-500 text-xs">
                        {{ $mov->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-4 py-2">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $mov->type === 'in' ? 'bg-green-100 text-green-700' :
                               ($mov->type === 'out' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $mov->type_label }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-right font-medium
                               {{ $mov->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $mov->type === 'in' ? '+' : '-' }}{{ $mov->quantity }}
                    </td>
                    <td class="px-4 py-2 text-right text-gray-500">{{ $mov->stock_before }}</td>
                    <td class="px-4 py-2 text-right font-medium">{{ $mov->stock_after }}</td>
                    <td class="px-4 py-2 text-gray-500 text-xs">{{ $mov->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-400">Belum ada riwayat stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($movements->hasPages())
        <div class="px-4 py-3 border-t">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection
