<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\BahanBaku;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// ── Cek stok kritis setiap hari jam 08.00 ────────────────────────────────────
Schedule::call(function () {
    $stokKritis = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->get();

    if ($stokKritis->isEmpty()) {
        return; // tidak ada yang kritis, skip
    }

    // Log ke storage/logs/laravel.log
    $namaList = $stokKritis->pluck('nama_bahan')->join(', ');
    \Illuminate\Support\Facades\Log::warning("⚠️ Stok kritis ditemukan: {$namaList}");
    })->dailyAt('08:00')->name('cek-stok-kritis')->withoutOverlapping();

    // ── Cek permintaan procurement yang sudah lama pending (lebih dari 3 hari) ────
Schedule::call(function () {
    $permintaanLama = \App\Models\PermintaanPengadaan::where('status', 'menunggu')
        ->where('created_at', '<=', now()->subDays(3))
        ->with('bahanBakuSupplier.bahanBaku')
        ->get();

    if ($permintaanLama->isEmpty()) {
        return;
    }

    $idList = $permintaanLama->pluck('id')->join(', ');
    \Illuminate\Support\Facades\Log::warning("⏰ Permintaan pending lebih dari 3 hari: {$idList}");

})->dailyAt('09:00')->name('cek-permintaan-pending')->withoutOverlapping();
