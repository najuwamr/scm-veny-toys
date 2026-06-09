<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\ReportingController;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     // Redirect ke login jika user belum autentik
//     if (!\Illuminate\Support\Facades\Auth::check()) {
//         return redirect('/login');
//     }
//     return view('login');
// });

// ----------------------------------- //
// ----------- AUTH  ROUTES ---------- //
// ----------------------------------- //
Route::get('/', [AuthController::class, 'klik_login'])->name('login');
Route::post('/login', [AuthController::class, 'proses_login'])->name('proses_login');
Route::get('/logout', [AuthController::class, 'proses_logout'])->name('proses_logout');


// ----------------------------------- //
// ---------- ADMIN  ROUTES ---------- //
// ----------------------------------- //
Route::middleware(['auth','role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard_admin'])->name('admin.dashboard');
    Route::prefix('/pesanan')->group(function () {
        Route::get('/list', [PesananController::class, 'index'])->name('admin.pesanan.list');
        Route::get('/detail/{id}', [PesananController::class, 'detail'])->name('admin.pesanan.detail');
        Route::post('/action/{id}', [PesananController::class, 'action'])->name('admin.pesanan.action');
    });

    Route::prefix('/invoice')->group(function () {
        Route::get('/list', [InvoiceController::class, 'index'])->name('admin.invoice.list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('admin.invoice.detail');
        Route::post('/action/{id}', [InvoiceController::class, 'action'])->name('admin.invoice.action');

        Route::get('/{id}/create-invoice', [InvoiceController::class, 'create'])->name('admin.invoice.create');
        // Route::get('/verify/{id}', [PesananController::class, 'verify'])->name('pesanan.verify');
    });

    Route::prefix('/distribution')->group(function () {
        Route::get('/', [DistributionController::class, 'index'])->name('admin.distribution.index');
        Route::get('/metode', [DistributionController::class, 'metodeIndex'])->name('admin.distribution.metode');
        Route::post('/metode', [DistributionController::class, 'metodeStore'])->name('admin.distribution.metode.store');

        Route::get('/jadwal/{pesananId}', [DistributionController::class, 'jadwalCreate'])->name('admin.distribution.jadwal.create');
        Route::post('/jadwal/{pesananId}', [DistributionController::class, 'jadwalStore'])->name('admin.distribution.jadwal.store');

        Route::get('/tracking/{id}', [DistributionController::class, 'tracking'])->name('admin.distribution.tracking');
        Route::post('/tracking/{id}/update', [DistributionController::class, 'updateStatus'])->name('admin.distribution.update');
    });

    Route::prefix('/reporting')->group(function () {
        Route::get('/', [ReportingController::class, 'dashboard'])->name('admin.reporting.dashboard');
        Route::get('/analytics', [ReportingController::class, 'analytics'])->name('admin.reporting.analytics');
    });
});


// ----------------------------------- //
// --------- SUPPLIER  ROUTES -------- //
// ----------------------------------- //
Route::middleware(['role:supplier'])->prefix('supplier')->group(function () {
    // Tambahkan routes untuk supplier di sini
});


// ----------------------------------- //
// ------- DISTRIBUTOR  ROUTES ------- //
// ----------------------------------- //
Route::middleware(['auth','role:distributor'])->prefix('distributor')->group(function () {
    // Tambahkan routes untuk distributor di sini
});


// ----------------------------------- //
// -------- RESELLER  ROUTES --------- //
// ----------------------------------- //
Route::middleware(['auth','role:reseller'])->prefix('reseller')->group(function () {
    Route::get('/', [PesananController::class, 'my_index'])->name('reseller.dashboard');

    Route::prefix('/pesanan')->group(function () {
        Route::get('/pesanan-saya', [PesananController::class, 'my_index'])->name('reseller.pesanan.list');
        Route::get('/detail/{id}', [PesananController::class, 'detail'])->name('reseller.pesanan.detail');

        Route::get('/create', [PesananController::class, 'create'])->name('reseller.pesanan.create');
        Route::post('/simpan', [PesananController::class, 'store'])->name('reseller.pesanan.store');
    });

    Route::prefix('/payment')->group(function () {
        Route::get('/pembayaran-saya', [InvoiceController::class, 'index'])->name('reseller.payment.list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('reseller.payment.detail'); //lihat invoice pembayaran
        Route::post('/detail/{id}/konfirmasi', [InvoiceController::class, 'confirm'])->name('reseller.payment.confirm');
    });
});
