<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PesananController;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.form-pesan-produk');
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
Route::prefix('admin')->group(function () {
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
});


// ----------------------------------- //
// --------- SUPPLIER  ROUTES -------- //
// ----------------------------------- //



// ----------------------------------- //
// ------- DISTRIBUTOR  ROUTES ------- //
// ----------------------------------- //



// ----------------------------------- //
// -------- RESELLER  ROUTES --------- //
// ----------------------------------- //
Route::prefix('reseller')->group(function () {
    Route::prefix('/pesanan')->group(function () {
        Route::get('/pesanan-saya', [PesananController::class, 'my_index'])->name('pesanan.list');
        Route::get('/detail/{id}', [PesananController::class, 'detail'])->name('pesanan.detail');

        Route::get('/create', [PesananController::class, 'create'])->name('pesanan.create');
        Route::post('/simpan', [PesananController::class, 'store'])->name('pesanan.store');
    });

    Route::prefix('/payment')->group(function () {
        Route::get('/pembayaran-saya', [InvoiceController::class, 'index'])->name('pesanan.list');
        Route::get('/detail/{id}', [InvoiceController::class, 'detail'])->name('pesanan.detail'); //lihat invoice pembayaran
    });
});