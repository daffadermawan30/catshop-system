<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Halaman POS / Kasir
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get(['id', 'name', 'phone']);
        return view('admin.sales.create', compact('customers'));
    }

    /**
     * Simpan transaksi penjualan
     * Menggunakan DB::transaction agar jika salah satu step gagal, semua rollback
     */
    public function store(StoreSaleRequest $request)
    {
        try {
            $sale = DB::transaction(function () use ($request) {

                // Hitung subtotal per item dan total keseluruhan
                $subtotal = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    $product  = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $qty      = (int) $item['quantity'];
                    $price    = (float) $item['unit_price'];
                    $disc     = (float) ($item['discount'] ?? 0);
                    $itemSub  = ($price - $disc) * $qty;
                    $subtotal += $itemSub;

                    // Cek stok cukup
                    if ($product->stock < $qty) {
                        throw new \Exception("Stok {$product->name} tidak cukup. Tersisa: {$product->stock}");
                    }

                    $itemsData[] = [
                        'product'   => $product,
                        'quantity'  => $qty,
                        'unit_price' => $price,
                        'discount'  => $disc,
                        'subtotal'  => $itemSub,
                    ];
                }

                $discountAmount = (float) ($request->discount_amount ?? 0);
                $total          = $subtotal - $discountAmount;
                $paid           = (float) $request->paid_amount;
                $change         = max(0, $paid - $total);

                // Buat record transaksi
                $sale = Sale::create([
                    'invoice_number'  => Sale::generateInvoiceNumber(),
                    'customer_id'     => $request->customer_id ?: null,
                    'user_id'         => Auth::id(),
                    'subtotal'        => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total'           => $total,
                    'paid_amount'     => $paid,
                    'change_amount'   => $change,
                    'payment_method'  => $request->payment_method,
                    'status'          => 'completed',
                    'notes'           => $request->notes,
                ]);

                // Simpan item dan kurangi stok
                foreach ($itemsData as $itemData) {
                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $itemData['product']->id,
                        'quantity'   => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'discount'   => $itemData['discount'],
                        'subtotal'   => $itemData['subtotal'],
                    ]);

                    $stockBefore = $itemData['product']->stock;
                    $stockAfter  = $stockBefore - $itemData['quantity'];

                    // Kurangi stok produk
                    $itemData['product']->decrement('stock', $itemData['quantity']);

                    // Catat pergerakan stok keluar
                    StockMovement::create([
                        'product_id'   => $itemData['product']->id,
                        'user_id'      => Auth::id(),
                        'sale_id'      => $sale->id,
                        'type'         => 'out',
                        'quantity'     => $itemData['quantity'],
                        'stock_before' => $stockBefore,
                        'stock_after'  => $stockAfter,
                        'notes'        => "Penjualan #{$sale->invoice_number}",
                    ]);
                }

                return $sale;
            });

            return response()->json([
                'success'        => true,
                'invoice_number' => $sale->invoice_number,
                'sale_id'        => $sale->id,
                'change'         => $sale->change_amount,
                'redirect'       => route('admin.sales.show', $sale),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Riwayat transaksi
     */
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'cashier'])->latest();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $sales = $query->paginate(20)->withQueryString();

        // Ringkasan penjualan hari ini untuk stat bar
        $todayTotal = Sale::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');

        $todayCount = Sale::where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();

        return view('admin.sales.index', compact('sales', 'todayTotal', 'todayCount'));
    }

    /**
     * Struk / detail transaksi
     */
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'cashier', 'items.product']);
        return view('admin.sales.show', compact('sale'));
    }

    /**
     * Batalkan transaksi dan kembalikan stok
     */
    public function destroy(Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Transaksi sudah dibatalkan.');
        }

        DB::transaction(function () use ($sale) {
            $sale->update(['status' => 'cancelled']);

            // Kembalikan stok untuk setiap item
            foreach ($sale->items as $item) {
                $product     = Product::lockForUpdate()->findOrFail($item->product_id);
                $stockBefore = $product->stock;
                $stockAfter  = $stockBefore + $item->quantity;

                $product->increment('stock', $item->quantity);

                StockMovement::create([
                    'product_id'   => $product->id,
                    'user_id'      => Auth::id(),
                    'sale_id'      => $sale->id,
                    'type'         => 'in',
                    'quantity'     => $item->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after'  => $stockAfter,
                    'notes'        => "Pembatalan transaksi #{$sale->invoice_number}",
                ]);
            }
        });

        return redirect()->route('admin.sales.index')
            ->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan.');
    }
}
