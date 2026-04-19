<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CatController;
use App\Http\Controllers\Admin\GroomingPackageController;
use App\Http\Controllers\Admin\GroomingBookingController;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Routes Admin (hanya bisa diakses jika login sebagai admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('customers', CustomerController::class);
        Route::resource('cats', CatController::class);

        // Paket grooming
        Route::resource('grooming-packages', GroomingPackageController::class);

        // Booking grooming
        Route::resource('grooming-bookings', GroomingBookingController::class);

        // Route khusus untuk update status dan input catatan
        Route::patch(
            'grooming-bookings/{groomingBooking}/status',
            [GroomingBookingController::class, 'updateStatus']
        )
            ->name('grooming-bookings.update-status');

        Route::get(
            'grooming-bookings/{groomingBooking}/record',
            [GroomingBookingController::class, 'recordForm']
        )
            ->name('grooming-bookings.record-form');

        Route::post(
            'grooming-bookings/{groomingBooking}/record',
            [GroomingBookingController::class, 'storeRecord']
        )
            ->name('grooming-bookings.store-record');

        // Route kalender — mengembalikan data JSON untuk FullCalendar
        Route::get('grooming-calendar', [GroomingBookingController::class, 'calendar'])
            ->name('grooming-calendar');

        Route::get('grooming-calendar/events', [GroomingBookingController::class, 'calendarEvents'])
            ->name('grooming-calendar.events');
    });

/*
|--------------------------------------------------------------------------
| Routes Pelanggan (akan ditambah di sprint berikutnya)
|--------------------------------------------------------------------------
*/
Route::prefix('pelanggan')
    ->name('pelanggan.')
    ->middleware(['auth', 'role:pelanggan'])
    ->group(function () {
        Route::get('/dashboard', function () {
            return 'Dashboard pelanggan — coming soon';
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Redirect setelah login berdasarkan role
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/redirect-after-login', function () {
    $role = auth()->user()->role?->name;
    return match ($role) {
        'admin'     => redirect()->route('admin.dashboard'),
        'pelanggan' => redirect()->route('pelanggan.dashboard'),
        default     => redirect('/'),
    };
});

require __DIR__ . '/auth.php';
