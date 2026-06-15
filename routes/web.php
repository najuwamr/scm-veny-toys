<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\ReportingController;
use Illuminate\Support\Facades\Route;

// ----------------------------------- //
// ----------- AUTH  ROUTES ---------- //
// ----------------------------------- //
Route::get('/', [AuthController::class, 'klik_login'])->name('login');
Route::post('/login', [AuthController::class, 'proses_login'])->name('proses_login');
Route::get('/logout', [AuthController::class, 'proses_logout'])->name('proses_logout');


// ----------------------------------- //
// ---------- ADMIN  ROUTES ---------- //
// ----------------------------------- //
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'dashboard_admin'])->name('dashboard');

    // ---- Inventory ---- //
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/dashboard', [InventoryController::class, 'dashboard'])->name('dashboard');

        Route::get('/produk', [InventoryController::class, 'indexProduk'])->name('produk');
        Route::get('/produk/create', [InventoryController::class, 'createProduk'])->name('produk.create');
        Route::get('/produk/{produk}/edit', [InventoryController::class, 'editProduk'])->name('produk.edit');
        Route::post('/produk', [InventoryController::class, 'storeProduk'])->name('produk.store');
        Route::put('/produk/{produk}', [InventoryController::class, 'updateProduk'])->name('produk.update');

        Route::get('/bahan-baku', [InventoryController::class, 'indexBahan'])->name('bahan');
        Route::get('/bahan-baku/create', [InventoryController::class, 'createBahan'])->name('bahan.create');
        Route::get('/bahan-baku/{bahan}/edit', [InventoryController::class, 'editBahan'])->name('bahan.edit');
        Route::post('/bahan-baku', [InventoryController::class, 'storeBahan'])->name('bahan.store');
        Route::put('/bahan-baku/{bahan}', [InventoryController::class, 'updateBahan'])->name('bahan.update');

        Route::get('/stok-masuk', [InventoryController::class, 'indexMasuk'])->name('masuk');
        Route::post('/stok-masuk', [InventoryController::class, 'storeMasuk'])->name('masuk.store');
        Route::get('/stok-keluar', [InventoryController::class, 'indexKeluar'])->name('keluar');
        Route::post('/stok-keluar', [InventoryController::class, 'storeKeluar'])->name('keluar.store');

        Route::get('/notifikasi', [InventoryController::class, 'notifikasi'])->name('notifikasi');
    });

    // ---- Procurement ---- //
    Route::prefix('procurement')->name('procurement.')->group(function () {
        Route::get('/', [ProcurementController::class, 'index'])->name('index');
        Route::get('/buat', [ProcurementController::class, 'create'])->name('create');
        Route::post('/', [ProcurementController::class, 'store'])->name('store');
        Route::get('/tracking', [ProcurementController::class, 'tracking'])->name('tracking');

        // ---- Production ---- //
        Route::prefix('produksi')->name('produksi.')->group(function () {
            Route::get('/', [DashboardController::class, 'produksiIndex'])->name('index');
            Route::get('/create', [DashboardController::class, 'createProduksi'])->name('create');
            Route::post('/store-rencana', [DashboardController::class, 'storeRencanaProduksi'])->name('store-rencana');
            Route::post('/store-realisasi', [DashboardController::class, 'storeRealisasiProduksi'])->name('store-realisasi');
        });

        // ---- Forecast ---- //
        Route::prefix('forecast')->name('forecast.')->group(function () {
            Route::get('/', [ForecastController::class, 'index'])->name('index');
            Route::post('/store', [ForecastController::class, 'store'])->name('store');
            Route::post('/calculate', [ForecastController::class, 'calculate'])->name('calculate');
            Route::get('/export-pdf', [ForecastController::class, 'exportPdf'])->name('exportPdf');
            Route::get('/export-excel', [ForecastController::class, 'exportExcel'])->name('exportExcel');
        });

        Route::get('/{permintaan}', [ProcurementController::class, 'show'])->whereUuid('permintaan')->name('show');
        Route::post('/{permintaan}/terima', [ProcurementController::class, 'konfirmasiTerima'])->whereUuid('permintaan')->name('terima');
    });

    // ---- Distribution ---- //
    Route::prefix('distribution')->name('distribution.')->group(function () {
        Route::get('/', [DistributionController::class, 'index'])->name('index');
        Route::get('/metode', [DistributionController::class, 'metodeIndex'])->name('metode');
        Route::post('/metode', [DistributionController::class, 'metodeStore'])->name('metode.store');
        Route::get('/jadwal/{pesananId}', [DistributionController::class, 'jadwalCreate'])->name('jadwal.create');
        Route::post('/jadwal/{pesananId}', [DistributionController::class, 'jadwalStore'])->name('jadwal.store');
        Route::get('/tracking/{id}', [DistributionController::class, 'tracking'])->name('tracking');
        Route::post('/tracking/{id}/update', [DistributionController::class, 'updateStatus'])->name('update');
    });

    // ---- Order & Payment ---- //
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/list', [PesananController::class, 'index'])->name('list');
        Route::get('/detail/{id}', [PesananController::class, 'detail'])->name('detail');
        Route::post('/action/{id}', [PesananController::class, 'action'])->name('action');
    });

    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/list', [InvoiceController::class, 'index'])->name('list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('detail');
        Route::post('/action/{id}', [InvoiceController::class, 'updateStatus'])->name('action');
        Route::post('/{id}/verify', [InvoiceController::class, 'verifyPayment'])->name('verify');
        Route::post('/{id}/reject', [InvoiceController::class, 'rejectPayment'])->name('reject');
        Route::get('/{id}/create-invoice', [InvoiceController::class, 'create'])->name('create');
    });

    // ---- Reporting ---- //
    Route::prefix('reporting')->name('reporting.')->group(function () {
        Route::get('/', [ReportingController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics', [ReportingController::class, 'analytics'])->name('analytics');
    });
});


// ----------------------------------- //
// --------- SUPPLIER  ROUTES -------- //
// ----------------------------------- //
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/', [SupplierController::class, 'dashboard'])->name('dashboard');
    Route::get('/permintaan', [SupplierController::class, 'index'])->name('index');
    Route::get('/tracking', [SupplierController::class, 'tracking'])->name('tracking');
    Route::post('/permintaan/{permintaan}/approve', [SupplierController::class, 'approve'])->whereUuid('permintaan')->name('approve');
    Route::post('/permintaan/{permintaan}/reject', [SupplierController::class, 'reject'])->whereUuid('permintaan')->name('reject');
    Route::post('/permintaan/{permintaan}/kirim', [SupplierController::class, 'kirim'])->whereUuid('permintaan')->name('kirim');

    Route::prefix('bahan')->name('materials.')->group(function () {
        Route::get('/', [SupplierController::class, 'materialsIndex'])->name('index');
        Route::get('/create', [SupplierController::class, 'createMaterial'])->name('create');
        Route::post('/', [SupplierController::class, 'storeMaterial'])->name('store');
    });
});


// ----------------------------------- //
// --------- RESELLER  ROUTES -------- //
// ----------------------------------- //
Route::middleware(['auth', 'role:reseller'])->prefix('reseller')->name('reseller.')->group(function () {
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/pesanan-saya', [PesananController::class, 'my_index'])->name('list');
        Route::get('/detail/{id}', [PesananController::class, 'detail'])->name('detail');
        Route::get('/create', [PesananController::class, 'create'])->name('create');
        Route::post('/simpan', [PesananController::class, 'store'])->name('store');
        Route::post('/{id}/store-bukti', [InvoiceController::class, 'uploadPaymentProof'])->name('store-bukti');
    });

    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/pembayaran-saya', [InvoiceController::class, 'index'])->name('list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('detail');
        Route::post('/detail/{id}/konfirmasi', [InvoiceController::class, 'confirm'])->name('confirm');
    });
});
