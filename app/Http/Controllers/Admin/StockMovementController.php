<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->paginate(20)->withQueryString();
        $products  = Product::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.stock-movements.index', compact('movements', 'products'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.stock-movements.create', compact('products'));
    }

    public function store(StoreStockMovementRequest $request)
    {
        DB::transaction(function () use ($request) {
            $product = Product::lockForUpdate()->findOrFail($request->product_id);

            $stockBefore = $product->stock;

            // Hitung stok baru berdasarkan tipe pergerakan
            $newStock = match ($request->type) {
                'in'         => $stockBefore + $request->quantity,
                'out'        => $stockBefore - $request->quantity,
                'adjustment' => $request->quantity, // Adjustment = set ke nilai tertentu
                default      => $stockBefore,
            };

            // Stok tidak boleh negatif
            if ($newStock < 0) {
                throw new \Exception("Stok tidak cukup. Stok saat ini: {$stockBefore}");
            }

            $product->update(['stock' => $newStock]);

            StockMovement::create([
                'product_id'   => $product->id,
                'user_id'      => Auth::id(),
                'type'         => $request->type,
                'quantity'     => $request->quantity,
                'stock_before' => $stockBefore,
                'stock_after'  => $newStock,
                'notes'        => $request->notes,
            ]);
        });

        return redirect()->route('admin.stock-movements.index')
            ->with('success', 'Pergerakan stok berhasil dicatat.');
    }
}
