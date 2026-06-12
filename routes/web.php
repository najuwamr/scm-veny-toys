<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\ReportingController;
use App\Models\Pesanan;
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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard_admin'])->name('admin.dashboard');

    // Order & Payment
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
    });

    // Inventory
    Route::prefix('/inventory')->group(function () {
        Route::get('/dashboard', [InventoryController::class, 'dashboard'])->name('inventory.dashboard');
        Route::get('/produk', [InventoryController::class, 'indexProduk'])->name('inventory.produk');
        Route::get('/produk/create', [InventoryController::class, 'createProduk'])->name('inventory.produk.create');
        Route::get('/produk/{produk}/edit', [InventoryController::class, 'editProduk'])->name('inventory.produk.edit');
        Route::post('/produk', [InventoryController::class, 'storeProduk'])->name('inventory.produk.store');
        Route::put('/produk/{produk}', [InventoryController::class, 'updateProduk'])->name('inventory.produk.update');
        Route::get('/bahan-baku', [InventoryController::class, 'indexBahan'])->name('inventory.bahan');
        Route::get('/bahan-baku/create', [InventoryController::class, 'createBahan'])->name('inventory.bahan.create');
        Route::get('/bahan-baku/{bahan}/edit', [InventoryController::class, 'editBahan'])->name('inventory.bahan.edit');
        Route::post('/bahan-baku', [InventoryController::class, 'storeBahan'])->name('inventory.bahan.store');
        Route::put('/bahan-baku/{bahan}', [InventoryController::class, 'updateBahan'])->name('inventory.bahan.update');
        Route::get('/stok-masuk', [InventoryController::class, 'indexMasuk'])->name('inventory.masuk');
        Route::post('/stok-masuk', [InventoryController::class, 'storeMasuk'])->name('inventory.masuk.store');
        Route::get('/stok-keluar', [InventoryController::class, 'indexKeluar'])->name('inventory.keluar');
        Route::post('/stok-keluar', [InventoryController::class, 'storeKeluar'])->name('inventory.keluar.store');
        Route::get('/notifikasi', [InventoryController::class, 'notifikasi'])->name('inventory.notifikasi');
    });

    // Procurement
    Route::prefix('/procurement')->group(function () {
        Route::get('/', [ProcurementController::class, 'index'])->name('procurement.index');
        Route::get('/buat', [ProcurementController::class, 'create'])->name('procurement.create');
        Route::post('/', [ProcurementController::class, 'store'])->name('procurement.store');
        Route::get('/tracking', [ProcurementController::class, 'tracking'])->name('procurement.tracking');
        Route::get('/{permintaan}', [ProcurementController::class, 'show'])->name('procurement.show');
        Route::post('/{permintaan}/terima', [ProcurementController::class, 'konfirmasiTerima'])->name('procurement.terima');
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
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('/{permintaan}/approve', [SupplierController::class, 'approve'])->name('supplier.approve');
    Route::post('/{permintaan}/reject', [SupplierController::class, 'reject'])->name('supplier.reject');
    Route::post('/{permintaan}/kirim', [SupplierController::class, 'kirim'])->name('supplier.kirim');
    Route::get('/tracking', [SupplierController::class, 'tracking'])->name('supplier.tracking');
});


// ----------------------------------- //
// ------- DISTRIBUTOR  ROUTES ------- //
// ----------------------------------- //
Route::middleware(['auth', 'role:distributor'])->prefix('distributor')->group(function () {
    // Tambahkan routes untuk distributor di sini
});


// ----------------------------------- //
// -------- RESELLER  ROUTES --------- //
// ----------------------------------- //
Route::middleware(['auth', 'role:reseller'])->prefix('reseller')->group(function () {
    Route::prefix('/pesanan')->group(function () {
        Route::get('/pesanan-saya', [PesananController::class, 'my_index'])->name('reseller.pesanan.list');
        Route::get('/detail/{id}', [PesananController::class, 'detail'])->name('reseller.pesanan.detail');
        Route::get('/create', [PesananController::class, 'create'])->name('reseller.pesanan.create');
        Route::post('/simpan', [PesananController::class, 'store'])->name('reseller.pesanan.store');
    });

    Route::prefix('/payment')->group(function () {
        Route::get('/pembayaran-saya', [InvoiceController::class, 'index'])->name('reseller.payment.list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('reseller.payment.detail');
        Route::post('/detail/{id}/konfirmasi', [InvoiceController::class, 'confirm'])->name('reseller.payment.confirm');
    });
});
