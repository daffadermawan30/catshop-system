<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['roomType', 'activeBooking.cat'])
            ->where('is_active', true)
            ->orderBy('room_number')
            ->get();

        // Group kamar berdasarkan tipe untuk tampilan grid
        $roomsByType = $rooms->groupBy('room_type_id');
        $roomTypes   = RoomType::whereIn('id', $roomsByType->keys())->get()->keyBy('id');

        return view('admin.rooms.index', compact('rooms', 'roomsByType', 'roomTypes'));
    }

    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->get();
        return view('admin.rooms.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'room_number'  => ['required', 'string', 'max:20', 'unique:rooms,room_number'],
            'notes'        => ['nullable', 'string', 'max:300'],
        ], [
            'room_number.unique' => 'Nomor kamar sudah digunakan.',
        ]);

        Room::create([
            'room_type_id' => $request->room_type_id,
            'room_number'  => strtoupper($request->room_number),
            'status'       => 'available',
            'notes'        => $request->notes,
            'is_active'    => true,
        ]);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::where('is_active', true)->get();
        return view('admin.rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'room_number'  => ['required', 'string', 'max:20',
                \Illuminate\Validation\Rule::unique('rooms', 'room_number')->ignore($room->id)],
            'status'       => ['required', 'in:available,occupied,maintenance'],
            'notes'        => ['nullable', 'string', 'max:300'],
        ]);

        $room->update($request->only(['room_type_id', 'room_number', 'status', 'notes']));

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        if ($room->activeBooking) {
            return back()->with('error', 'Kamar sedang dihuni, tidak bisa dinonaktifkan.');
        }
        $room->update(['is_active' => false]);
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar dinonaktifkan.');
    }
}
