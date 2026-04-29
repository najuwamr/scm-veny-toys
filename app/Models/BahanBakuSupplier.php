<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

// Gunakan 'Pivot' bukan 'Model'
class BahanBakuSupplier extends Pivot
{
    protected $table = 'bahan_baku_supplier';
    public $incrementing = true; // Karena kita punya kolom ID di tabel pivot

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
