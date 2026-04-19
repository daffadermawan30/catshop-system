<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatRequest;
use App\Http\Requests\UpdateCatRequest;
use App\Models\Cat;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatController extends Controller
{
    /**
     * index() — Daftar semua kucing
     * URL: GET /admin/cats
     */
    public function index(Request $request)
    {
        if ($request->format === 'json') {
            $cats = Cat::where('is_active', true)
                ->when(
                    $request->customer_id,
                    fn($q) => $q->where('customer_id', $request->customer_id)
                )
                ->get(['id', 'name', 'breed']);

            return response()->json($cats);
        }

        $cats = Cat::with('customer')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('admin.cats.index', compact('cats'));
    }

    /**
     * create() — Form tambah kucing baru
     * URL: GET /admin/cats/create
     */
    public function create()
    {
        // Ambil daftar pelanggan untuk dropdown
        $customers = Customer::orderBy('name')->get();
        return view('admin.cats.create', compact('customers'));
    }

    /**
     * store() — Simpan kucing baru
     * URL: POST /admin/cats
     */
    public function store(StoreCatRequest $request)
    {
        $data = $request->validated();

        // Proses upload foto jika ada
        if ($request->hasFile('photo')) {
            // store('cats', 'public') = simpan di storage/app/public/cats/
            // Laravel otomatis generate nama file unik
            $data['photo'] = $request->file('photo')->store('cats', 'public');
        }

        // Pastikan is_sterilized ada nilainya (checkbox)
        $data['is_sterilized'] = $request->boolean('is_sterilized');

        Cat::create($data);

        return redirect()
            ->route('admin.cats.index')
            ->with('success', 'Data kucing berhasil ditambahkan.');
    }

    /**
     * show() — Halaman detail kucing + seluruh riwayat
     * URL: GET /admin/cats/{cat}
     */
    public function show(Cat $cat)
    {
        $cat->load([
            'customer',
            'groomingBookings' => function ($query) {
                $query->with('package')->latest();
            },
            'boardingBookings' => function ($query) {
                $query->with('room.roomType')->latest();
            },
        ]);

        return view('admin.cats.show', compact('cat'));
    }

    /**
     * edit() — Form edit kucing
     * URL: GET /admin/cats/{cat}/edit
     */
    public function edit(Cat $cat)
    {
        $customers = Customer::orderBy('name')->get();
        return view('admin.cats.edit', compact('cat', 'customers'));
    }

    /**
     * update() — Simpan perubahan data kucing
     * URL: PUT /admin/cats/{cat}
     */
    public function update(UpdateCatRequest $request, Cat $cat)
    {
        $data = $request->validated();
        $data['is_sterilized'] = $request->boolean('is_sterilized');

        // Proses ganti foto jika ada upload baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama dari storage agar tidak menumpuk
            if ($cat->photo) {
                Storage::disk('public')->delete($cat->photo);
            }
            $data['photo'] = $request->file('photo')->store('cats', 'public');
        } else {
            // Jika tidak ada upload baru, tetap pakai foto lama
            unset($data['photo']);
        }

        $cat->update($data);

        return redirect()
            ->route('admin.cats.show', $cat)
            ->with('success', 'Data kucing berhasil diperbarui.');
    }

    /**
     * destroy() — Nonaktifkan kucing (soft delete)
     * URL: DELETE /admin/cats/{cat}
     */
    public function destroy(Cat $cat)
    {
        // Kita tidak hapus permanen karena data historis tetap dibutuhkan
        // Cukup tandai is_active = false
        $cat->update(['is_active' => false]);

        return redirect()
            ->route('admin.cats.index')
            ->with('success', 'Data kucing berhasil dinonaktifkan.');
    }
}
