@extends('layouts.admin')
@section('title', 'Riwayat Penjualan')
@section('header', 'Riwayat Penjualan')

@section('content')
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-orange-400">
        <p class="text-xs text-gray-500">Penjualan Hari Ini</p>
        <p class="text-2xl font-bold text-orange-600">
            Rp {{ number_format($todayTotal, 0, ',', '.') }}
        </p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-green-400">
        <p class="text-xs text-gray-500">Transaksi Hari Ini</p>
        <p class="text-2xl font-bold text-green-600">{{ $todayCount }} transaksi</p>
    </div>
</div>

<div class="flex justify-between items-center mb-4">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="No. Invoice..."
            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 w-44">
        <input type="date" name="date" value="{{ request('date') }}"
            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400">
        <select name="payment_method" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400">
            <option value="">Semua Metode</option>
            <option value="cash"     {{ request('payment_method') === 'cash' ? 'selected' : '' }}>💵 Cash</option>
            <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>🏦 Transfer</option>
            <option value="qris"     {{ request('payment_method') === 'qris' ? 'selected' : '' }}>📱 QRIS</option>
            <option value="debit"    {{ request('payment_method') === 'debit' ? 'selected' : '' }}>💳 Debit</option>
        </select>
        <button type="submit" class="bg-gray-100 border px-3 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <a href="{{ route('admin.sales.pos') }}"
       class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
        🛒 Buka Kasir
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Invoice</th>
                <th class="px-4 py-3 text-left font-medium">Pelanggan</th>
                <th class="px-4 py-3 text-left font-medium">Kasir</th>
                <th class="px-4 py-3 text-right font-medium">Total</th>
                <th class="px-4 py-3 text-left font-medium">Pembayaran</th>
                <th class="px-4 py-3 text-left font-medium">Status</th>
                <th class="px-4 py-3 text-left font-medium">Waktu</th>
                <th class="px-4 py-3 text-left font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($sales as $sale)
            @php
                $pmLabel = ['cash' => '💵 Cash', 'transfer' => '🏦 Transfer', 'qris' => '📱 QRIS', 'debit' => '💳 Debit', 'credit' => '💳 Kredit'];
            @endphp
            <tr class="hover:bg-gray-50 {{ $sale->status === 'cancelled' ? 'opacity-60' : '' }}">
                <td class="px-4 py-3 font-mono text-xs font-medium text-gray-700">
                    {{ $sale->invoice_number }}
                </td>
                <td class="px-4 py-3 text-gray-600">
                    {{ $sale->customer?->name ?? 'Pelanggan Umum' }}
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $sale->cashier->name }}</td>
                <td class="px-4 py-3 text-right font-bold text-gray-800">
                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">
                    {{ $pmLabel[$sale->payment_method] ?? $sale->payment_method }}
                </td>
                <td class="px-4 py-3">
                    @if($sale->status === 'completed')
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">✅ Selesai</span>
                    @else
                        <span class="text-xs bg-red-100 text-red-500 px-2 py-0.5 rounded-full">❌ Batal</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">
                    {{ $sale->created_at->format('d M Y H:i') }}
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.sales.show', $sale) }}"
                       class="text-blue-600 hover:underline text-xs">Lihat</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-12 text-gray-400">
                    <p class="text-4xl mb-2">🧾</p>
                    <p>Belum ada transaksi.</p>
                    <a href="{{ route('admin.sales.pos') }}"
                       class="text-orange-600 hover:underline text-sm mt-1 inline-block">Mulai transaksi pertama</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($sales->hasPages())
    <div class="px-4 py-3 border-t">{{ $sales->links() }}</div>
    @endif
</div>
@endsection
