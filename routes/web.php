<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PublicBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CatController;
use App\Http\Controllers\Admin\GroomingPackageController;
use App\Http\Controllers\Admin\GroomingBookingController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\BoardingBookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Halaman Publik (Landing Page & Booking Publik)
|--------------------------------------------------------------------------
*/
// Routes Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [HomeController::class, 'services'])->name('services');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/galeri', [HomeController::class, 'gallery'])->name('gallery');

// Booking Publik (Guest diperbolehkan)
Route::get('/booking', [PublicBookingController::class, 'create'])->name('public.booking');
Route::post('/booking', [PublicBookingController::class, 'store'])->name('public.booking.store');
Route::get('/booking/sukses', [PublicBookingController::class, 'success'])->name('public.booking.success');

/*
|--------------------------------------------------------------------------
| Routes Admin
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

        Route::resource('grooming-packages', GroomingPackageController::class);
        Route::resource('grooming-bookings', GroomingBookingController::class);

        Route::patch(
            'grooming-bookings/{groomingBooking}/status',
            [GroomingBookingController::class, 'updateStatus']
        )->name('grooming-bookings.update-status');

        Route::get(
            'grooming-bookings/{groomingBooking}/record',
            [GroomingBookingController::class, 'recordForm']
        )->name('grooming-bookings.record-form');

        Route::post(
            'grooming-bookings/{groomingBooking}/record',
            [GroomingBookingController::class, 'storeRecord']
        )->name('grooming-bookings.store-record');

        Route::get('grooming-calendar', [GroomingBookingController::class, 'calendar'])
            ->name('grooming-calendar');

        Route::get('grooming-calendar/events', [GroomingBookingController::class, 'calendarEvents'])
            ->name('grooming-calendar.events');

        Route::resource('room-types', RoomTypeController::class)->except(['show']);
        Route::resource('rooms', RoomController::class)->except(['show']);
        Route::resource('boarding-bookings', BoardingBookingController::class);

        Route::patch(
            'boarding-bookings/{boardingBooking}/status',
            [BoardingBookingController::class, 'updateStatus']
        )->name('boarding-bookings.update-status');

        Route::get(
            'boarding-bookings/{boardingBooking}/journal',
            [BoardingBookingController::class, 'journalForm']
        )->name('boarding-bookings.journal-form');

        Route::post(
            'boarding-bookings/{boardingBooking}/journal',
            [BoardingBookingController::class, 'storeJournal']
        )->name('boarding-bookings.store-journal');

        Route::get('boarding-calendar', fn() => view('admin.boarding-bookings.calendar'))
            ->name('boarding-calendar');

        Route::get('boarding-calendar/events', [BoardingBookingController::class, 'calendarEvents'])
            ->name('boarding-calendar.events');

        Route::resource('categories', CategoryController::class)->except(['show']);

        Route::get('products/search/api', [ProductController::class, 'search'])
            ->name('products.search');

        Route::resource('products', ProductController::class);

        Route::resource('stock-movements', StockMovementController::class)
            ->only(['index', 'create', 'store']);

        Route::get('sales/pos', [SaleController::class, 'create'])
            ->name('sales.pos');

        Route::get('sales', [SaleController::class, 'index'])
            ->name('sales.index');

        Route::post('sales', [SaleController::class, 'store'])
            ->name('sales.store');

        Route::get('sales/{sale}', [SaleController::class, 'show'])
            ->name('sales.show');

        Route::delete('sales/{sale}', [SaleController::class, 'destroy'])
            ->name('sales.destroy');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])            ->name('index');
            Route::get('/export/sales', [ReportController::class, 'exportSalesExcel']) ->name('export.sales');
            Route::get('/export/grooming', [ReportController::class,'exportGroomingExcel'])->name('export.grooming');
            Route::get('/export/pdf', [ReportController::class, 'exportPdf'])        ->name('export.pdf');
        });
    });

/*
|--------------------------------------------------------------------------
| Routes Pelanggan (Sprint 6)
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
