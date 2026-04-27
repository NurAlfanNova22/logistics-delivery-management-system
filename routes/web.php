<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SopirController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PesananController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\CustomerController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware([AdminMiddleware::class])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/laporan-keuangan', [LaporanController::class, 'keuangan'])
            ->name('admin.laporan.keuangan');
            
        Route::get('/laporan-keuangan/export-pdf', [LaporanController::class, 'exportPdf'])
            ->name('admin.laporan.exportPdf');

        Route::resource('/sopir', SopirController::class);
        Route::resource('/kendaraan', KendaraanController::class);

        Route::get('/customer', [CustomerController::class, 'index'])->name('admin.customer.index');
        Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('admin.customer.show');
        Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('admin.customer.destroy');

        /*
        |--------------------------------------
        | PESANAN - ROUTE KHUSUS (HARUS DI ATAS)
        |--------------------------------------
        */

        // Assign Driver
        Route::get('/pesanan/{id}/assign',
            [PesananController::class, 'assignForm']
        )->name('pesanan.assignForm');

        Route::post('/pesanan/{id}/assign',
            [PesananController::class, 'assignStore']
        )->name('pesanan.assignStore');

        // Update Status
        Route::get('/pesanan/{id}/update-status',
            [PesananController::class, 'updateStatusForm']
        )->name('pesanan.updateStatusForm');

        Route::post('/pesanan/{id}/update-status',
            [PesananController::class, 'updateStatus']
        )->name('pesanan.updateStatus');

        /*
        |--------------------------------------
        | RESOURCE PALING BAWAH
        |--------------------------------------
        */
        Route::resource('/pesanan', PesananController::class);
    });