<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroomingPackageRequest;
use App\Models\GroomingPackage;

class GroomingPackageController extends Controller
{
    public function index()
    {
        $packages = GroomingPackage::latest()->get();
        return view('admin.grooming-packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.grooming-packages.create');
    }

    public function store(StoreGroomingPackageRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        GroomingPackage::create($data);

        return redirect()
            ->route('admin.grooming-packages.index')
            ->with('success', 'Paket grooming berhasil ditambahkan.');
    }

    public function edit(GroomingPackage $groomingPackage)
    {
        return view('admin.grooming-packages.edit', compact('groomingPackage'));
    }

    public function update(StoreGroomingPackageRequest $request, GroomingPackage $groomingPackage)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $groomingPackage->update($data);

        return redirect()
            ->route('admin.grooming-packages.index')
            ->with('success', 'Paket grooming berhasil diperbarui.');
    }

    public function destroy(GroomingPackage $groomingPackage)
    {
        // Cek apakah paket ini sedang dipakai di booking aktif
        $activeBookings = $groomingPackage->groomingBookings()
            ->whereNotIn('status', ['done', 'cancelled'])
            ->count();

        if ($activeBookings > 0) {
            return back()->with('error', 'Paket tidak bisa dihapus karena masih ada booking aktif.');
        }

        // Nonaktifkan saja daripada hapus (data historis tetap terjaga)
        $groomingPackage->update(['is_active' => false]);

        return redirect()
            ->route('admin.grooming-packages.index')
            ->with('success', 'Paket grooming dinonaktifkan.');
    }
}
