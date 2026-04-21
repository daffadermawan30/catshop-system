@extends('layouts.admin')
@section('title', 'Laporan')
@section('header', '📊 Laporan & Statistik')

@section('content')

{{-- Filter Bulan --}}
<div class="flex flex-wrap gap-3 items-center justify-between mb-6">
    <form method="GET" class="flex items-center gap-2">
        <label class="text-sm text-gray-600 font-medium">Bulan:</label>
        <input type="month" name="month" value="{{ $month }}"
            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400">
        <button type="submit"
            class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
            Tampilkan
        </button>
    </form>

    {{-- Export Buttons --}}
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('admin.reports.export.sales', ['month' => $month]) }}"
           class="flex items-center gap-1 bg-green-600 text-white px-3 py-2 rounded-lg text-xs hover:bg-green-700">
            📥 Excel Penjualan
        </a>
        <a href="{{ route('admin.reports.export.grooming', ['month' => $month]) }}"
           class="flex items-center gap-1 bg-blue-600 text-white px-3 py-2 rounded-lg text-xs hover:bg-blue-700">
            📥 Excel Grooming
        </a>
        <a href="{{ route('admin.reports.export.pdf', ['month' => $month]) }}"
           class="flex items-center gap-1 bg-red-600 text-white px-3 py-2 rounded-lg text-xs hover:bg-red-700">
            📄 Export PDF
        </a>
    </div>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['💰', 'Total Pendapatan', 'Rp ' . number_format($totalAllRevenue, 0, ',', '.'), 'orange'],
        ['🛒', 'Transaksi Kasir', $totalSalesCount . ' transaksi', 'green'],
        ['✂️', 'Sesi Grooming', $totalGroomingCount . ' sesi', 'blue'],
        ['🏠', 'Pendapatan Penitipan', 'Rp ' . number_format($boardingRevenue, 0, ',', '.'), 'purple'],
    ] as [$icon, $label, $value, $color])
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-{{ $color }}-400">
        <p class="text-xs text-gray-500 mb-1">{{ $icon }} {{ $label }}</p>
        <p class="text-xl font-bold text-gray-800">{{ $value }}</p>
    </div>
    @endforeach
</div>

<div class="grid md:grid-cols-2 gap-6 mb-6">
    {{-- Grafik Penjualan Harian --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-gray-700 mb-4">📈 Penjualan Harian (Kasir)</h3>
        @if($salesData->isEmpty())
            <p class="text-center text-gray-400 py-8">Belum ada data bulan ini.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left">Tanggal</th>
                        <th class="px-3 py-2 text-right">Transaksi</th>
                        <th class="px-3 py-2 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($salesData as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2">
                            {{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}
                        </td>
                        <td class="px-3 py-2 text-right">{{ $row->count }}</td>
                        <td class="px-3 py-2 text-right font-medium text-orange-600">
                            Rp {{ number_format($row->revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-orange-50 font-bold">
                    <tr>
                        <td class="px-3 py-2 text-orange-700">Total</td>
                        <td class="px-3 py-2 text-right text-orange-700">{{ $totalSalesCount }}</td>
                        <td class="px-3 py-2 text-right text-orange-700">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>

    {{-- Laporan Grooming --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-gray-700 mb-4">✂️ Grooming Selesai</h3>
        @if($groomingData->isEmpty())
            <p class="text-center text-gray-400 py-8">Belum ada sesi grooming bulan ini.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left">Tanggal</th>
                        <th class="px-3 py-2 text-right">Sesi</th>
                        <th class="px-3 py-2 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($groomingData as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2">
                            {{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}
                        </td>
                        <td class="px-3 py-2 text-right">{{ $row->count }}</td>
                        <td class="px-3 py-2 text-right font-medium text-blue-600">
                            Rp {{ number_format($row->revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-blue-50 font-bold">
                    <tr>
                        <td class="px-3 py-2 text-blue-700">Total</td>
                        <td class="px-3 py-2 text-right text-blue-700">{{ $totalGroomingCount }}</td>
                        <td class="px-3 py-2 text-right text-blue-700">
                            Rp {{ number_format($totalGroomingRevenue, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    {{-- Occupancy Kamar --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-gray-700 mb-4">🏠 Occupancy Kamar (Saat Ini)</h3>
        @if($roomTypes->isEmpty())
            <p class="text-center text-gray-400 py-8">Belum ada tipe kamar.</p>
        @else
        <div class="space-y-4">
            @foreach($roomTypes as $type)
            @php
                $occupancyPct = $type->rooms_count > 0
                    ? round(($type->occupied_count / $type->rooms_count) * 100)
                    : 0;
            @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-700">{{ $type->name }}</span>
                    <span class="text-gray-500">
                        {{ $type->occupied_count }}/{{ $type->rooms_count }} kamar terisi
                        <span class="font-bold text-orange-600 ml-1">{{ $occupancyPct }}%</span>
                    </span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="bg-orange-500 h-2.5 rounded-full transition-all"
                         style="width: {{ $occupancyPct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Produk Terlaris --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-gray-700 mb-4">🏆 Produk Terlaris (All-time)</h3>
        @if($topProducts->isEmpty())
            <p class="text-center text-gray-400 py-8">Belum ada data penjualan produk.</p>
        @else
        <div class="space-y-3">
            @foreach($topProducts as $i => $item)
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                    {{ $i === 0 ? 'bg-yellow-400 text-yellow-900' : 'bg-gray-100 text-gray-600' }}">
                    {{ $i + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">
                        {{ $item->product?->name ?? 'Produk Dihapus' }}
                    </p>
                    <p class="text-xs text-gray-400">Terjual {{ $item->total_qty }} pcs</p>
                </div>
                <span class="text-sm font-bold text-orange-600 whitespace-nowrap">
                    Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection
