<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CatController;

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
