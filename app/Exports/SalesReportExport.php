<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(protected string $month) {}

    public function collection()
    {
        [$year, $mon] = explode('-', $this->month);

        return Sale::where('status', 'completed')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $mon)
            ->with(['customer', 'cashier'])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Invoice', 'Tanggal', 'Pelanggan',
            'Kasir', 'Metode Bayar', 'Subtotal (Rp)',
            'Diskon (Rp)', 'Total (Rp)', 'Status',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->created_at->format('d/m/Y H:i'),
            $sale->customer?->name ?? 'Pelanggan Umum',
            $sale->cashier->name,
            strtoupper($sale->payment_method),
            $sale->subtotal,
            $sale->discount_amount,
            $sale->total,
            $sale->status === 'completed' ? 'Selesai' : 'Batal',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EA580C']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }
}
