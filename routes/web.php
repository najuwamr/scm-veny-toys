<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdusenController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!\Illuminate\Support\Facades\Auth::check()) {
        return redirect('/login');
    }

    $redirectPath = match(\Illuminate\Support\Facades\Auth::user()->role) {
        'admin' => '/admin',
        'supplier' => '/supplier',
        'produsen' => '/produsen',
        'reseller' => '/reseller/pesanan/pesanan-saya',
        default => '/login',
    };

    return redirect($redirectPath);
});

// ----------------------------------- //
// ----------- AUTH  ROUTES ---------- //
// ----------------------------------- //
Route::get('/login', [AuthController::class, 'klik_login'])->name('login');
Route::post('/login', [AuthController::class, 'proses_login'])->name('proses_login');
Route::get('/logout', [AuthController::class, 'proses_logout'])->name('proses_logout');


// ----------------------------------- //
// ---------- ADMIN  ROUTES ---------- //
// ----------------------------------- //
Route::middleware(['role:admin'])->prefix('admin')->group(function () {
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

        Route::get('/{id}/create-invoice', [InvoiceController::class, 'create'])->name('admin.invoice.create');
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


// ----------------------------------- //
// --------- PRODUSEN  ROUTES --------- //
// ----------------------------------- //
Route::middleware(['role:produsen'])->prefix('produsen')->group(function () {
    Route::get('/', [ProdusenController::class, 'dashboard'])->name('produsen.dashboard');

    Route::prefix('inventory')->group(function () {
        Route::get('/bahan-baku', [ProdusenController::class, 'bahanBakuIndex'])->name('produsen.inventory.bahan-baku.index');
        Route::get('/produk', [ProdusenController::class, 'produkIndex'])->name('produsen.inventory.produk.index');
        Route::get('/mutasi', [ProdusenController::class, 'mutasiForm'])->name('produsen.inventory.mutasi.create');
        Route::post('/mutasi', [ProdusenController::class, 'storeMutasi'])->name('produsen.inventory.mutasi.store');
    });

    Route::prefix('procurement')->group(function () {
        Route::get('/', [ProdusenController::class, 'permintaanIndex'])->name('produsen.procurement.index');
        Route::get('/create', [ProdusenController::class, 'createPermintaan'])->name('produsen.procurement.create');
        Route::post('/store', [ProdusenController::class, 'storePermintaan'])->name('produsen.procurement.store');
    });

    Route::prefix('produksi')->group(function () {
        Route::get('/', [ProdusenController::class, 'produksiIndex'])->name('produsen.produksi.index');
        Route::get('/create', [ProdusenController::class, 'createProduksi'])->name('produsen.produksi.create');
        Route::post('/store-rencana', [ProdusenController::class, 'storeRencanaProduksi'])->name('produsen.produksi.store-rencana');
        Route::post('/store-realisasi', [ProdusenController::class, 'storeRealisasiProduksi'])->name('produsen.produksi.store-realisasi');
    });

    Route::get('/forecast', [ProdusenController::class, 'forecastIndex'])->name('produsen.forecast.index');
    Route::post('/forecast/use/{id}', [ProdusenController::class, 'useForecast'])->name('produsen.forecast.use');
});


// ----------------------------------- //
// -------- RESELLER  ROUTES --------- //
// ----------------------------------- //
Route::middleware(['role:reseller'])->prefix('reseller')->group(function () {
    Route::prefix('/pesanan')->group(function () {
        Route::get('/pesanan-saya', [PesananController::class, 'my_index'])->name('reseller.pesanan.list');
        Route::get('/detail/{id}', [PesananController::class, 'my_detail'])->name('reseller.pesanan.detail');
        Route::get('/{id}/upload-bukti', [PesananController::class, 'my_upload_payment_proof'])->name('reseller.pesanan.upload-bukti');
        Route::post('/{id}/store-bukti', [InvoiceController::class, 'uploadPaymentProof'])->name('reseller.pesanan.store-bukti');
        Route::post('/{id}/confirm-received', [PesananController::class, 'confirmReceived'])->name('reseller.pesanan.confirm-received');

        Route::get('/create', [PesananController::class, 'create'])->name('reseller.pesanan.create');
        Route::post('/simpan', [PesananController::class, 'store'])->name('reseller.pesanan.store');
    });

    Route::prefix('/payment')->group(function () {
        Route::get('/pembayaran-saya', [InvoiceController::class, 'index'])->name('reseller.payment.list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('reseller.payment.detail'); //lihat invoice pembayaran
    });
});
