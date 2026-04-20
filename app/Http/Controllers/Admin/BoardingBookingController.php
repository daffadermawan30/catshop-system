<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoardingBookingRequest;
use App\Http\Requests\StoreBoardingJournalRequest;
use App\Models\BoardingBooking;
use App\Models\BoardingJournal;
use App\Models\Cat;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BoardingBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = BoardingBooking::with(['customer', 'cat', 'room.roomType'])
            ->latest('check_in_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            // Filter booking yang aktif pada tanggal tertentu
            $query->where('check_in_date', '<=', $request->date)
                  ->where('check_out_date', '>=', $request->date);
        }

        $bookings = $query->paginate(15);

        $statusCounts = BoardingBooking::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Hitung kamar tersedia saat ini untuk info di halaman
        $availableRooms = Room::where('status', 'available')
            ->where('is_active', true)
            ->count();

        return view('admin.boarding-bookings.index', compact('bookings', 'statusCounts', 'availableRooms'));
    }

    public function create(Request $request)
    {
        $customers   = Customer::orderBy('name')->get();
        $roomTypes   = RoomType::with([
            // Load hanya kamar yang aktif
            'rooms' => fn($q) => $q->where('is_active', true)->orderBy('room_number'),
        ])->where('is_active', true)->get();

        $selectedCustomer = null;
        $cats = collect();

        if ($request->filled('customer_id')) {
            $selectedCustomer = Customer::find($request->customer_id);
            $cats = $selectedCustomer?->cats()->where('is_active', true)->get() ?? collect();
        }

        return view('admin.boarding-bookings.create', compact(
            'customers',
            'roomTypes',
            'selectedCustomer',
            'cats'
        ));
    }

    public function store(StoreBoardingBookingRequest $request)
    {
        $room = Room::findOrFail($request->room_id);

        // Validasi: cek apakah kamar tersedia di rentang tanggal yang dipilih
        if (! $room->isAvailableFor($request->check_in_date, $request->check_out_date)) {
            return back()
                ->withInput()
                ->withErrors(['room_id' => 'Kamar tidak tersedia pada tanggal yang dipilih. Silakan pilih kamar lain.']);
        }

        // Hitung durasi dan total harga
        $checkIn  = \Carbon\Carbon::parse($request->check_in_date);
        $checkOut = \Carbon\Carbon::parse($request->check_out_date);
        $duration = max(1, $checkIn->diffInDays($checkOut));
        $total    = $duration * $room->roomType->price_per_day;

        BoardingBooking::create([
            'customer_id'    => $request->customer_id,
            'cat_id'         => $request->cat_id,
            'room_id'        => $request->room_id,
            'check_in_date'  => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status'         => 'confirmed', // Admin input = langsung confirmed
            'duration_days'  => $duration,
            'price_per_day'  => $room->roomType->price_per_day,
            'total_price'    => $total,
            'customer_notes' => $request->customer_notes,
        ]);

        return redirect()
            ->route('admin.boarding-bookings.index')
            ->with('success', 'Booking penitipan berhasil dibuat.');
    }

    public function show(BoardingBooking $boardingBooking)
    {
        $boardingBooking->load([
            'customer', 'cat', 'room.roomType',
            'journals.author',
        ]);

        return view('admin.boarding-bookings.show', compact('boardingBooking'));
    }

    public function edit(BoardingBooking $boardingBooking)
    {
        if (in_array($boardingBooking->status, ['checked_in', 'checked_out', 'cancelled'])) {
            return back()->with('error', 'Booking tidak bisa diedit pada status ini.');
        }

        $customers = Customer::orderBy('name')->get();
        $roomTypes = RoomType::with([
            'rooms' => fn($q) => $q->where('is_active', true)->orderBy('room_number'),
        ])->where('is_active', true)->get();

        $cats = $boardingBooking->customer->cats()->where('is_active', true)->get();

        return view('admin.boarding-bookings.edit', compact('boardingBooking', 'customers', 'roomTypes', 'cats'));
    }

    public function update(StoreBoardingBookingRequest $request, BoardingBooking $boardingBooking)
    {
        if (in_array($boardingBooking->status, ['checked_in', 'checked_out', 'cancelled'])) {
            return back()->with('error', 'Booking tidak bisa diubah.');
        }

        $room = Room::findOrFail($request->room_id);

        // Validasi ketersediaan kamar, exclude booking ini sendiri
        if (! $room->isAvailableFor($request->check_in_date, $request->check_out_date, $boardingBooking->id)) {
            return back()
                ->withInput()
                ->withErrors(['room_id' => 'Kamar tidak tersedia pada tanggal yang dipilih.']);
        }

        $checkIn  = \Carbon\Carbon::parse($request->check_in_date);
        $checkOut = \Carbon\Carbon::parse($request->check_out_date);
        $duration = max(1, $checkIn->diffInDays($checkOut));
        $total    = $duration * $room->roomType->price_per_day;

        $boardingBooking->update([
            'customer_id'    => $request->customer_id,
            'cat_id'         => $request->cat_id,
            'room_id'        => $request->room_id,
            'check_in_date'  => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'duration_days'  => $duration,
            'price_per_day'  => $room->roomType->price_per_day,
            'total_price'    => $total,
            'customer_notes' => $request->customer_notes,
        ]);

        return redirect()
            ->route('admin.boarding-bookings.show', $boardingBooking)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(BoardingBooking $boardingBooking)
    {
        if ($boardingBooking->status === 'checked_in') {
            return back()->with('error', 'Kucing sedang dititipkan, tidak bisa dibatalkan.');
        }
        $boardingBooking->update(['status' => 'cancelled']);
        return redirect()->route('admin.boarding-bookings.index')
            ->with('success', 'Booking dibatalkan.');
    }

    /**
     * Update status booking (check-in, check-out, dll.)
     */
    public function updateStatus(Request $request, BoardingBooking $boardingBooking)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
        ]);

        // Aturan transisi yang dibolehkan
        $allowedTransitions = [
            'pending'     => ['confirmed', 'cancelled'],
            'confirmed'   => ['checked_in', 'cancelled'],
            'checked_in'  => ['checked_out'],
            'checked_out' => [],
            'cancelled'   => [],
        ];

        $current = $boardingBooking->status;
        $new     = $request->status;

        if (! in_array($new, $allowedTransitions[$current])) {
            return back()->with('error', "Status tidak bisa diubah dari '{$current}' ke '{$new}'.");
        }

        $updateData = ['status' => $new];

        if ($new === 'checked_in') {
            // Catat waktu aktual check-in dan ubah status kamar jadi occupied
            $updateData['actual_check_in'] = now();
            $boardingBooking->room->update(['status' => 'occupied']);

        } elseif ($new === 'checked_out') {
            // Catat waktu aktual check-out dan kembalikan status kamar ke available
            $updateData['actual_check_out'] = now();
            $boardingBooking->room->update(['status' => 'available']);
        }

        $boardingBooking->update($updateData);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Form tambah jurnal harian
     */
    public function journalForm(BoardingBooking $boardingBooking)
    {
        if ($boardingBooking->status !== 'checked_in') {
            return back()->with('error', 'Jurnal hanya bisa diisi saat kucing sedang dititipkan.');
        }

        $boardingBooking->load(['cat', 'journals']);

        // Default tanggal jurnal = hari ini
        $defaultDate = now()->toDateString();

        // Ambil jurnal hari ini jika sudah ada (untuk edit)
        $existingJournal = $boardingBooking->journals
            ->firstWhere('journal_date', $defaultDate);

        return view('admin.boarding-bookings.journal-form', compact(
            'boardingBooking',
            'defaultDate',
            'existingJournal'
        ));
    }

    /**
     * Simpan jurnal harian
     */
    public function storeJournal(StoreBoardingJournalRequest $request, BoardingBooking $boardingBooking)
    {
        $data = $request->validated();
        $data['boarding_booking_id'] = $boardingBooking->id;
        $data['created_by']          = Auth::id();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('boarding-journals', 'public');
        }

        // updateOrCreate: update jika sudah ada jurnal tanggal ini, buat baru jika belum
        BoardingJournal::updateOrCreate(
            [
                'boarding_booking_id' => $boardingBooking->id,
                'journal_date'        => $data['journal_date'],
            ],
            $data
        );

        return redirect()
            ->route('admin.boarding-bookings.show', $boardingBooking)
            ->with('success', 'Jurnal harian berhasil disimpan.');
    }

    /**
     * API: data kalender untuk FullCalendar
     */
    public function calendarEvents(Request $request)
    {
        $start = $request->get('start', now()->startOfMonth());
        $end   = $request->get('end', now()->endOfMonth());

        $bookings = BoardingBooking::with(['customer', 'cat', 'room.roomType'])
            ->whereNotIn('status', ['cancelled'])
            ->where('check_in_date', '<=', $end)
            ->where('check_out_date', '>=', $start)
            ->get();

        $statusColors = [
            'pending'    => '#f59e0b',
            'confirmed'  => '#3b82f6',
            'checked_in' => '#f97316',
            'checked_out' => '#22c55e',
        ];

        $events = $bookings->map(fn($booking) => [
            'id'    => 'b' . $booking->id,
            'title' => $booking->cat->name . ' · ' . $booking->room->room_number,
            'start' => $booking->check_in_date->toDateString(),
            // FullCalendar end date bersifat exclusive, tambah 1 hari
            'end'   => $booking->check_out_date->addDay()->toDateString(),
            'color' => $statusColors[$booking->status] ?? '#6b7280',
            'url'   => route('admin.boarding-bookings.show', $booking),
            'extendedProps' => [
                'customer' => $booking->customer->name,
                'room'     => $booking->room->room_number . ' (' . $booking->room->roomType->name . ')',
                'duration' => $booking->duration_days . ' hari',
                'price'    => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
            ],
        ]);

        return response()->json($events);
    }
}
