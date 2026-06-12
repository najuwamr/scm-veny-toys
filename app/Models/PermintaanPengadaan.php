<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\BahanBaku;
use App\Models\BahanBakuSupplier;
use App\Models\PengirimanPengadaan;
use App\Models\Supplier;

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

    public function bahanBaku()
    {
        return $this->hasOneThrough(
            BahanBaku::class,
            BahanBakuSupplier::class,
            'id',
            'id',
            'bahan_baku_supplier_id',
            'bahan_baku_id'
        );
    }

    public function supplier()
    {
        return $this->hasOneThrough(
            Supplier::class,
            BahanBakuSupplier::class,
            'id',
            'id',
            'bahan_baku_supplier_id',
            'supplier_id'
        );
    }

    public function getJumlahDimintaAttribute()
    {
        return $this->jumlah;
    }

    public function getCatatanAdminAttribute()
    {
        return $this->catatan;
    }

    public function getCatatanSupplierAttribute()
    {
        return $this->catatan;
    }
}
