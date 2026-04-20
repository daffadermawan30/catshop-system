@extends('layouts.admin')
@section('title', 'Struk ' . $sale->invoice_number)
@section('header', 'Detail Transaksi')

@section('content')
<div class="max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.sales.index') }}"
           class="text-gray-500 text-sm flex items-center gap-1">← Riwayat Penjualan</a>
        <div class="flex gap-2">
            <button onclick="window.print()"
                class="bg-white border text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                🖨️ Cetak Struk
            </button>
            @if($sale->status === 'completed')
            <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST"
                  onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="bg-red-50 text-red-600 px-4 py-2 rounded-lg text-sm hover:bg-red-100">
                    Batalkan
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Struk --}}
    <div id="receipt" class="bg-white rounded-xl shadow-sm p-6">
        {{-- Header struk --}}
        <div class="text-center border-b pb-4 mb-4">
            <h1 class="text-xl font-bold text-orange-600">🐱 CatShop</h1>
            <p class="text-xs text-gray-500 mt-1">Pet Shop Khusus Kucing</p>
        </div>

        <div class="flex justify-between text-sm mb-4">
            <div>
                <p class="text-gray-500">No. Invoice</p>
                <p class="font-mono font-bold">{{ $sale->invoice_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-500">Tanggal</p>
                <p>{{ $sale->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        @if($sale->customer)
        <div class="text-sm mb-4 p-3 bg-orange-50 rounded-lg">
            <p class="text-gray-500">Pelanggan</p>
            <p class="font-medium">{{ $sale->customer->name }}</p>
            <p class="text-xs text-gray-400">{{ $sale->customer->phone }}</p>
        </div>
        @endif

        {{-- Item Pembelian --}}
        <table class="w-full text-sm mb-4">
            <thead>
                <tr class="border-b text-gray-500">
                    <th class="py-2 text-left font-medium">Produk</th>
                    <th class="py-2 text-right font-medium">Qty</th>
                    <th class="py-2 text-right font-medium">Harga</th>
                    <th class="py-2 text-right font-medium">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($sale->items as $item)
                <tr>
                    <td class="py-2 text-gray-800">{{ $item->product->name }}</td>
                    <td class="py-2 text-right text-gray-600">{{ $item->quantity }}</td>
                    <td class="py-2 text-right text-gray-600">
                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                        @if($item->discount > 0)
                            <br><span class="text-xs text-orange-500">-Rp {{ number_format($item->discount, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td class="py-2 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Ringkasan Total --}}
        <div class="border-t pt-3 space-y-1 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($sale->discount_amount > 0)
            <div class="flex justify-between text-orange-600">
                <span>Diskon</span>
                <span>-Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between font-bold text-lg pt-1 border-t">
                <span>Total</span>
                <span class="text-orange-600">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Dibayar ({{ strtoupper($sale->payment_method) }})</span>
                <span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>
            @if($sale->change_amount > 0)
            <div class="flex justify-between text-green-600 font-medium">
                <span>Kembalian</span>
                <span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        <div class="text-center text-xs text-gray-400 mt-4 border-t pt-3">
            <p>Terima kasih telah berbelanja di CatShop 🐱</p>
            <p>Kasir: {{ $sale->cashier->name }}</p>
            @if($sale->status === 'cancelled')
            <p class="text-red-500 font-bold mt-1">⚠️ TRANSAKSI DIBATALKAN</p>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body * { visibility: hidden; }
    #receipt, #receipt * { visibility: visible; }
    #receipt { position: absolute; left: 0; top: 0; width: 80mm; font-size: 11px; }
}
</style>
@endpush
@endsection
