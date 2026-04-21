<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }

        .header { background: #ea580c; color: white; padding: 16px 20px; margin-bottom: 16px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p  { font-size: 10px; opacity: 0.85; margin-top: 2px; }

        .section { margin: 0 20px 16px; }
        .section-title {
            font-size: 12px; font-weight: bold;
            border-bottom: 2px solid #ea580c;
            padding-bottom: 4px; margin-bottom: 8px;
            color: #ea580c;
        }

        .kpi-grid { display: flex; gap: 8px; margin: 0 20px 16px; }
        .kpi-box {
            flex: 1; border: 1px solid #fed7aa; border-radius: 6px;
            padding: 10px; text-align: center; background: #fff7ed;
        }
        .kpi-label { font-size: 8px; color: #666; margin-bottom: 2px; }
        .kpi-value { font-size: 13px; font-weight: bold; color: #ea580c; }

        table { width: 100%; border-collapse: collapse; font-size: 9px; }
        th { background: #ea580c; color: white; padding: 5px 6px; text-align: left; }
        td { padding: 4px 6px; border-bottom: 1px solid #f3f4f6; }
        tr:nth-child(even) td { background: #fef9f5; }
        tfoot td { background: #fff7ed; font-weight: bold; color: #ea580c; }

        .footer {
            margin-top: 20px; padding: 10px 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px; color: #999; text-align: center;
        }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <h1>🐱 CatShop — Laporan Bulanan</h1>
    <p>Periode: {{ $monthLabel }} | Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>

{{-- KPI --}}
<div class="kpi-grid">
    <div class="kpi-box">
        <div class="kpi-label">Total Pendapatan</div>
        <div class="kpi-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="kpi-box">
        <div class="kpi-label">Transaksi Kasir</div>
        <div class="kpi-value">{{ $salesData->count() }}</div>
    </div>
    <div class="kpi-box">
        <div class="kpi-label">Sesi Grooming</div>
        <div class="kpi-value">{{ $groomingData->count() }}</div>
    </div>
    <div class="kpi-box">
        <div class="kpi-label">Check-out Penitipan</div>
        <div class="kpi-value">{{ $boardingData->count() }}</div>
    </div>
</div>

{{-- Tabel Penjualan --}}
@if($salesData->count() > 0)
<div class="section">
    <div class="section-title">Transaksi Penjualan (Kasir)</div>
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Metode</th>
                <th style="text-align:right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesData as $sale)
            <tr>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                <td>{{ $sale->customer?->name ?? 'Umum' }}</td>
                <td>{{ strtoupper($sale->payment_method) }}</td>
                <td style="text-align:right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td style="text-align:right">Rp {{ number_format($salesData->sum('total'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

{{-- Tabel Grooming --}}
@if($groomingData->count() > 0)
<div class="section">
    <div class="section-title">Sesi Grooming Selesai</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Kucing</th>
                <th>Paket</th>
                <th style="text-align:right">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groomingData as $g)
            <tr>
                <td>{{ $g->scheduled_date->format('d/m/Y') }}</td>
                <td>{{ $g->customer->name }}</td>
                <td>{{ $g->cat->name }}</td>
                <td>{{ $g->package->name }}</td>
                <td style="text-align:right">Rp {{ number_format($g->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td style="text-align:right">Rp {{ number_format($groomingData->sum('total_price'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

{{-- Tabel Penitipan --}}
@if($boardingData->count() > 0)
<div class="section">
    <div class="section-title">Penitipan — Check-Out</div>
    <table>
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Kucing</th>
                <th>Kamar</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th style="text-align:right">Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boardingData as $b)
            <tr>
                <td>{{ $b->customer->name }}</td>
                <td>{{ $b->cat->name }}</td>
                <td>{{ $b->roomType->name }}</td>
                <td>{{ $b->check_in_date->format('d/m') }}</td>
                <td>{{ $b->check_out_date->format('d/m') }}</td>
                <td style="text-align:right">Rp {{ number_format($b->total_cost, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td style="text-align:right">Rp {{ number_format($boardingData->sum('total_cost'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

<div class="footer">
    Laporan ini digenerate otomatis oleh sistem CatShop •
    {{ config('catshop.name') }} • {{ config('catshop.address') }}
</div>

</body>
</html>
