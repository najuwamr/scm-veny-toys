<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PrakiraanProduksi extends Model
{
    use HasUuids;

    protected $fillable = [
        'produk_id',
        'bulan',
        'tahun',
        'qty_prediksi',
        'metode',
    ];

    /**
     * Relasi ke Produk
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Accessor untuk mendapatkan nama bulan dalam Bahasa Indonesia
     */
    protected function namaBulan(): Attribute
    {
        return Attribute::get(function () {
            $bulanIndonesia = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            return $bulanIndonesia[$this->bulan] ?? 'Tidak Diketahui';
        });
    }
}
