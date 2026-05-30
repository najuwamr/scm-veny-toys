<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PesananController;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect ke login jika user belum autentik
    if (!\Illuminate\Support\Facades\Auth::check()) {
        return redirect('/login');
    }
    return view('admin.dashboard');
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
        Route::post('/create-invoice/{id}', [PesananController::class, 'createInvoice'])->name('admin.pesanan.create-invoice');
    });
    
    Route::prefix('/invoice')->group(function () {
        Route::get('/list', [InvoiceController::class, 'index'])->name('admin.invoice.list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('admin.invoice.detail');
        Route::post('/verify-payment/{id}', [InvoiceController::class, 'verifyPayment'])->name('admin.invoice.verify');
        Route::post('/reject-payment/{id}', [InvoiceController::class, 'rejectPayment'])->name('admin.invoice.reject');
        Route::post('/confirm-delivery/{id}', [InvoiceController::class, 'confirmDelivery'])->name('admin.invoice.confirm-delivery');
    });
});

// ----------------------------------- //
// --------- SUPPLIER  ROUTES -------- //
// ----------------------------------- //
Route::middleware(['role:supplier'])->prefix('supplier')->group(function () {
    // Tambahkan routes untuk supplier di sini
});

// ----------------------------------- //
// --------- PRODUSEN  ROUTES -------- //
// ----------------------------------- //
Route::middleware(['role:produsen'])->prefix('produsen')->group(function () {
    // Tambahkan routes untuk produsen di sini
});

// ----------------------------------- //
// -------- RESELLER  ROUTES --------- //
// ----------------------------------- //
Route::middleware(['role:reseller'])->prefix('reseller')->group(function () {
    Route::prefix('/pesanan')->group(function () {
        Route::get('/pesanan-saya', [PesananController::class, 'my_index'])->name('pesanan.list');
        Route::get('/detail/{id}', [PesananController::class, 'my_detail'])->name('pesanan.detail');
        Route::get('/create', [PesananController::class, 'create'])->name('pesanan.create');
        Route::post('/simpan', [PesananController::class, 'store'])->name('pesanan.store');
        Route::post('/confirm-received/{id}', [PesananController::class, 'confirmReceived'])->name('pesanan.confirm-received');
    });

    Route::prefix('/payment')->group(function () {
        Route::get('/upload-bukti/{pesanan_id}', [PesananController::class, 'my_upload_payment_proof'])->name('pesanan.upload-bukti');
        Route::post('/upload-bukti/{pesanan_id}', [InvoiceController::class, 'uploadPaymentProof'])->name('pesanan.store-bukti');
    });

    Route::prefix('/invoice')->group(function () {
        Route::get('/pembayaran-saya', [InvoiceController::class, 'index'])->name('invoice.list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('invoice.detail');
    });
});
