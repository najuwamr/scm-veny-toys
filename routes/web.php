<?php

use App\Http\Controllers\AuthController;
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
Route::prefix('pesanan')->group(function () {
    Route::get('/list', [PesananController::class, 'index'])->name('pesanan.list');
    Route::get('/detail/{id}', [PesananController::class, 'detail'])->name('pesanan.detail');
    // Route::get('/verify/{id}', [PesananController::class, 'verify'])->name('pesanan.verify');
});

Route::prefix('payment')->group(function () {
    Route::get('/list', [PesananController::class, 'index'])->name('pesanan.list');
    Route::get('/detail/{id}', [PesananController::class, 'detail'])->name('pesanan.detail');
    // Route::get('/verify/{id}', [PesananController::class, 'verify'])->name('pesanan.verify');
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
