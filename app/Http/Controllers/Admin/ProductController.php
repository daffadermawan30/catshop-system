<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('low_stock')) {
            // Filter produk dengan stok <= stok minimum
            $query->whereColumn('stock', '<=', 'stock_min');
        }

        $products   = $query->where('is_active', true)->latest()->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        $lowStockCount = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'stock_min')
            ->count();

        return view('admin.products.index', compact('products', 'categories', 'lowStockCount'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }

        // Auto-generate SKU jika kosong: PRD-timestamp
        if (empty($data['sku'])) {
            $data['sku'] = 'PRD-' . strtoupper(substr(uniqid(), -6));
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        $movements = $product->stockMovements()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('admin.products.show', compact('product', 'movements'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            // Hapus foto lama sebelum upload baru
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $data['photo'] = $request->file('photo')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk dinonaktifkan.');
    }

    /**
     * API: cari produk untuk kasir (JSON)
     * Dipanggil dari halaman POS saat ketik nama/barcode
     */
    public function search(Request $request)
    {
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($q) use ($request) {
                $search = $request->get('q', '');
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', $search)
                  ->orWhere('sku', 'like', "%{$search}%");
            })
            ->with('category')
            ->limit(10)
            ->get(['id', 'name', 'sell_price', 'stock', 'unit', 'photo', 'barcode']);

        return response()->json($products);
    }
}
