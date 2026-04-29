<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PermintaanPengadaan extends Model
{
    use HasUuids;
    protected $fillable = [
        'bahan_baku_supplier_id',
        'jumlah',
        'status',
        'catatan',
        'disetujui_pada',
    ];

    public function bahanBakuSupplier()
    {
        return $this->belongsTo(BahanBakuSupplier::class);
    }

    public function pengiriman()
    {
        return $this->hasOne(PengirimanPengadaan::class);
    }
}
