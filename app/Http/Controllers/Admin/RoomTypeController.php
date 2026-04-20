<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Models\RoomType;

class RoomTypeController extends Controller
{
    public function index()
    {
        // with('rooms') untuk hitung kamar per tipe tanpa N+1 query
        $roomTypes = RoomType::withCount([
            'rooms',
            'rooms as available_count' => fn($q) => $q->where('status', 'available'),
        ])->latest()->get();

        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room-types.create');
    }

    public function store(StoreRoomTypeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        RoomType::create($data);

        return redirect()
            ->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil ditambahkan.');
    }

    public function edit(RoomType $roomType)
    {
        return view('admin.room-types.edit', compact('roomType'));
    }

    public function update(StoreRoomTypeRequest $request, RoomType $roomType)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $roomType->update($data);

        return redirect()
            ->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil diperbarui.');
    }

    public function destroy(RoomType $roomType)
    {
        // Cek apakah ada kamar aktif dengan tipe ini
        if ($roomType->rooms()->where('is_active', true)->exists()) {
            return back()->with('error', 'Tipe kamar tidak bisa dihapus karena masih ada kamar aktif.');
        }
        $roomType->update(['is_active' => false]);
        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar dinonaktifkan.');
    }
}
