<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroomingBookingRequest;
use App\Http\Requests\StoreGroomingRecordRequest;
use App\Models\Cat;
use App\Models\Customer;
use App\Models\GroomingBooking;
use App\Models\GroomingPackage;
use App\Models\GroomingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroomingBookingController extends Controller
{
    /**
     * Daftar semua booking grooming dengan filter status
     */
    public function index(Request $request)
    {
        $query = GroomingBooking::with(['customer', 'cat', 'package'])
            ->latest('booking_date');

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        $bookings = $query->paginate(15);

        // Hitung jumlah per status untuk badge tab
        $statusCounts = GroomingBooking::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.grooming-bookings.index', compact('bookings', 'statusCounts'));
    }

    /**
     * Form buat booking baru (oleh admin, atas nama pelanggan)
     */
    public function create(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $packages  = GroomingPackage::where('is_active', true)->get();

        // Jika ada customer_id dari query string (misalnya dari halaman detail customer)
        $selectedCustomer = null;
        $cats = collect();

        if ($request->filled('customer_id')) {
            $selectedCustomer = Customer::find($request->customer_id);
            $cats = $selectedCustomer?->cats()->where('is_active', true)->get() ?? collect();
        }

        return view('admin.grooming-bookings.create', compact(
            'customers',
            'packages',
            'selectedCustomer',
            'cats'
        ));
    }

    /**
     * Simpan booking baru
     */
    public function store(StoreGroomingBookingRequest $request)
    {
        $package = GroomingPackage::findOrFail($request->package_id);

        GroomingBooking::create([
            'customer_id'    => $request->customer_id,
            'cat_id'         => $request->cat_id,
            'package_id'     => $request->package_id,
            'booking_date'   => $request->booking_date,
            'status'         => 'confirmed', // Admin yang input = langsung confirmed
            'customer_notes' => $request->customer_notes,
            'total_price'    => $package->price,
        ]);

        return redirect()
            ->route('admin.grooming-bookings.index')
            ->with('success', 'Booking grooming berhasil dibuat.');
    }

    /**
     * Detail satu booking
     */
    public function show(GroomingBooking $groomingBooking)
    {
        $groomingBooking->load(['customer', 'cat', 'package', 'record']);
        return view('admin.grooming-bookings.show', compact('groomingBooking'));
    }

    /**
     * Form edit booking
     */
    public function edit(GroomingBooking $groomingBooking)
    {
        // Booking yang sudah selesai atau dibatalkan tidak bisa diedit
        if (in_array($groomingBooking->status, ['done', 'cancelled'])) {
            return back()->with('error', 'Booking yang sudah selesai/dibatalkan tidak dapat diedit.');
        }

        $customers = Customer::orderBy('name')->get();
        $packages  = GroomingPackage::where('is_active', true)->get();
        $cats = $groomingBooking->customer->cats()->where('is_active', true)->get();

        return view('admin.grooming-bookings.edit', compact(
            'groomingBooking',
            'customers',
            'packages',
            'cats'
        ));
    }

    /**
     * Simpan perubahan booking
     */
    public function update(StoreGroomingBookingRequest $request, GroomingBooking $groomingBooking)
    {
        if (in_array($groomingBooking->status, ['done', 'cancelled'])) {
            return back()->with('error', 'Booking tidak bisa diubah.');
        }

        $package = GroomingPackage::findOrFail($request->package_id);

        $groomingBooking->update([
            'customer_id'    => $request->customer_id,
            'cat_id'         => $request->cat_id,
            'package_id'     => $request->package_id,
            'booking_date'   => $request->booking_date,
            'customer_notes' => $request->customer_notes,
            'total_price'    => $package->price,
        ]);

        return redirect()
            ->route('admin.grooming-bookings.show', $groomingBooking)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Hapus / batalkan booking
     */
    public function destroy(GroomingBooking $groomingBooking)
    {
        if ($groomingBooking->status === 'done') {
            return back()->with('error', 'Booking yang sudah selesai tidak dapat dihapus.');
        }

        $groomingBooking->update(['status' => 'cancelled']);

        return redirect()
            ->route('admin.grooming-bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Update status booking (PATCH)
     * Alur status: pending → confirmed → in_progress → done
     */
    public function updateStatus(Request $request, GroomingBooking $groomingBooking)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,in_progress,done,cancelled'],
        ]);

        // Aturan transisi status yang boleh dilakukan
        // Mencegah status diubah sembarangan, misalnya dari done kembali ke pending
        $allowedTransitions = [
            'pending'     => ['confirmed', 'cancelled'],
            'confirmed'   => ['in_progress', 'cancelled'],
            'in_progress' => ['done'],
            'done'        => [],        // Tidak bisa diubah
            'cancelled'   => [],        // Tidak bisa diubah
        ];

        $current = $groomingBooking->status;
        $new     = $request->status;

        if (! in_array($new, $allowedTransitions[$current])) {
            return back()->with('error', "Status tidak bisa diubah dari '{$current}' menjadi '{$new}'.");
        }

        $groomingBooking->update(['status' => $new]);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Form input catatan hasil grooming (setelah status = done)
     */
    public function recordForm(GroomingBooking $groomingBooking)
    {
        if ($groomingBooking->status !== 'done') {
            return back()->with('error', 'Catatan hanya bisa diisi setelah grooming selesai.');
        }

        $groomingBooking->load(['customer', 'cat', 'package', 'record']);
        return view('admin.grooming-bookings.record-form', compact('groomingBooking'));
    }

    /**
     * Simpan catatan hasil grooming
     */
    public function storeRecord(StoreGroomingRecordRequest $request, GroomingBooking $groomingBooking)
    {
        $data = $request->validated();
        $data['booking_id'] = $groomingBooking->id;

        // Upload foto sebelum
        if ($request->hasFile('photo_before')) {
            $data['photo_before'] = $request->file('photo_before')->store('grooming', 'public');
        }

        // Upload foto sesudah
        if ($request->hasFile('photo_after')) {
            $data['photo_after'] = $request->file('photo_after')->store('grooming', 'public');
        }

        // Update atau buat baru (kalau sudah ada recordnya, timpa)
        GroomingRecord::updateOrCreate(
            ['booking_id' => $groomingBooking->id],
            $data
        );

        return redirect()
            ->route('admin.grooming-bookings.show', $groomingBooking)
            ->with('success', 'Catatan grooming berhasil disimpan.');
    }

    /**
     * Halaman kalender jadwal grooming
     */
    public function calendar()
    {
        return view('admin.grooming-bookings.calendar');
    }

    /**
     * API endpoint: Kembalikan data booking sebagai JSON untuk FullCalendar
     * FullCalendar akan fetch URL ini lewat JavaScript
     */
    public function calendarEvents(Request $request)
    {
        // FullCalendar mengirim parameter start dan end (range yang sedang ditampilkan)
        $start = $request->get('start', now()->startOfMonth());
        $end   = $request->get('end', now()->endOfMonth());

        $bookings = GroomingBooking::with(['customer', 'cat', 'package'])
            ->whereBetween('booking_date', [$start, $end])
            ->whereNotIn('status', ['cancelled'])
            ->get();

        // Format data sesuai yang dibutuhkan FullCalendar
        // Setiap event harus punya: title, start, dan opsional: color, url
        $events = $bookings->map(function ($booking) {
            // Warna berbeda untuk setiap status
            $colors = [
                'pending'     => '#f59e0b', // kuning
                'confirmed'   => '#3b82f6', // biru
                'in_progress' => '#f97316', // oranye
                'done'        => '#22c55e', // hijau
            ];

            return [
                'id'    => $booking->id,
                'title' => $booking->cat->name . ' — ' . $booking->package->name,
                // ISO 8601 format yang dibutuhkan FullCalendar
                'start' => $booking->booking_date->toIso8601String(),
                'color' => $colors[$booking->status] ?? '#6b7280',
                // URL yang dibuka saat event di-klik
                'url'   => route('admin.grooming-bookings.show', $booking),
                // Data tambahan yang bisa diakses via JavaScript
                'extendedProps' => [
                    'customer' => $booking->customer->name,
                    'status'   => $booking->status,
                    'price'    => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
                ],
            ];
        });

        // Kembalikan sebagai JSON
        return response()->json($events);
    }
}
