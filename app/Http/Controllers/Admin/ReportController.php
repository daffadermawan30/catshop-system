<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GroomingReportExport;
use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Models\BoardingBooking;
use App\Models\GroomingBooking;
use App\Models\RoomType;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Halaman utama laporan dengan ringkasan semua modul
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        // ---- Laporan Penjualan ----
        $salesData = Sale::where('status', 'completed')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $mon)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue   = $salesData->sum('revenue');
        $totalSalesCount = $salesData->sum('count');

        // ---- Laporan Grooming ----
        $groomingData = GroomingBooking::where('status', 'completed')
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $mon)
            ->selectRaw('DATE(scheduled_date) as date, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalGroomingRevenue = $groomingData->sum('revenue');
        $totalGroomingCount   = $groomingData->sum('count');

        // ---- Laporan Penitipan / Occupancy ----
        $roomTypes = RoomType::withCount([
            'rooms',
            'rooms as occupied_count' => fn($q)
                => $q->whereHas(
                    'boardingBookings',
                    fn($b)
                    => $b->where('status', 'checked_in')
                ),
        ])->get();

        $boardingRevenue = BoardingBooking::where('status', 'checked_out')
            ->whereYear('check_out_date', $year)
            ->whereMonth('check_out_date', $mon)
            ->sum('total_cost');

        // ---- Gabungan Pendapatan ----
        $totalAllRevenue = $totalRevenue + $totalGroomingRevenue + $boardingRevenue;

        // ---- Produk Terlaris ----
        $topProducts = \App\Models\SaleItem::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'month',
            'salesData',
            'groomingData',
            'totalRevenue',
            'totalSalesCount',
            'totalGroomingRevenue',
            'totalGroomingCount',
            'boardingRevenue',
            'totalAllRevenue',
            'roomTypes',
            'topProducts',
        ));
    }

    /**
     * Export laporan penjualan ke Excel
     */
    public function exportSalesExcel(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        $filename = "laporan-penjualan-{$month}.xlsx";
        return Excel::download(new SalesReportExport($month), $filename);
    }

    /**
     * Export laporan grooming ke Excel
     */
    public function exportGroomingExcel(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        $filename = "laporan-grooming-{$month}.xlsx";
        return Excel::download(new GroomingReportExport($month), $filename);
    }

    /**
     * Export laporan bulanan ke PDF
     */
    public function exportPdf(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $salesData = Sale::where('status', 'completed')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $mon)
            ->with(['customer', 'items.product'])
            ->latest()
            ->get();

        $groomingData = GroomingBooking::where('status', 'completed')
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $mon)
            ->with(['customer', 'cat', 'package'])
            ->latest()
            ->get();

        $boardingData = BoardingBooking::where('status', 'checked_out')
            ->whereYear('check_out_date', $year)
            ->whereMonth('check_out_date', $mon)
            ->with(['customer', 'cat', 'roomType'])
            ->latest()
            ->get();

        $totalRevenue  = $salesData->sum('total')
            + $groomingData->sum('total_price')
            + $boardingData->sum('total_cost');

        $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y');

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'month',
            'monthLabel',
            'salesData',
            'groomingData',
            'boardingData',
            'totalRevenue',
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-catshop-{$month}.pdf");
    }
}
