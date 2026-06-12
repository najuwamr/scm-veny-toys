<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!\Illuminate\Support\Facades\Auth::check()) {
        return redirect('/login');
    }

    $redirectPath = match(\Illuminate\Support\Facades\Auth::user()->role) {
        'admin' => '/admin',
        'supplier' => '/supplier',
        'reseller' => '/reseller/pesanan/pesanan-saya',
        default => '/login',
    };

    return redirect($redirectPath);
});

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
        Route::post('/verify/{id}', [InvoiceController::class, 'verifyPayment'])->name('admin.invoice.verify');
        Route::post('/reject/{id}', [InvoiceController::class, 'rejectPayment'])->name('admin.invoice.reject');
        Route::post('/action/{id}', [InvoiceController::class, 'updateStatus'])->name('admin.invoice.action');

        Route::get('/{id}/create-invoice', [InvoiceController::class, 'create'])->name('admin.invoice.create');
    });

    Route::prefix('/procurement')->group(function () {
        Route::prefix('/produksi')->group(function () {
            Route::get('/', [DashboardController::class, 'produksiIndex'])->name('admin.procurement.produksi.index');
            Route::get('/create', [DashboardController::class, 'createProduksi'])->name('admin.procurement.produksi.create');
            Route::post('/store-rencana', [DashboardController::class, 'storeRencanaProduksi'])->name('admin.procurement.produksi.store-rencana');
            Route::post('/store-realisasi', [DashboardController::class, 'storeRealisasiProduksi'])->name('admin.procurement.produksi.store-realisasi');
        });

        Route::prefix('/forecast')->group(function () {
            Route::get('/', [ForecastController::class, 'index'])->name('admin.procurement.forecast.index');
            Route::post('/store', [ForecastController::class, 'store'])->name('admin.procurement.forecast.store');
            Route::post('/calculate', [ForecastController::class, 'calculate'])->name('admin.procurement.forecast.calculate');
            Route::get('/export-pdf', [ForecastController::class, 'exportPdf'])->name('admin.procurement.forecast.exportPdf');
            Route::get('/export-excel', [ForecastController::class, 'exportExcel'])->name('admin.procurement.forecast.exportExcel');
        });
    });
});


// ----------------------------------- //
// --------- SUPPLIER  ROUTES -------- //
// ----------------------------------- //
Route::middleware(['role:supplier'])->prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'dashboard'])->name('supplier.dashboard');

    Route::prefix('offers')->group(function () {
        Route::get('/', [SupplierController::class, 'offersIndex'])->name('supplier.offers.index');
        Route::get('/create', [SupplierController::class, 'createOffer'])->name('supplier.offers.create');
        Route::post('/', [SupplierController::class, 'storeOffer'])->name('supplier.offers.store');
    });

    Route::prefix('requests')->group(function () {
        Route::get('/', [SupplierController::class, 'requestsIndex'])->name('supplier.requests.index');
        Route::post('/{id}/approve', [SupplierController::class, 'approveRequest'])->name('supplier.requests.approve');
        Route::post('/{id}/reject', [SupplierController::class, 'rejectRequest'])->name('supplier.requests.reject');
        Route::post('/{id}/complete', [SupplierController::class, 'completeRequest'])->name('supplier.requests.complete');
    });
});


// -------- RESELLER  ROUTES --------- //
// ----------------------------------- //
Route::middleware(['auth','role:reseller'])->prefix('reseller')->group(function () {
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
