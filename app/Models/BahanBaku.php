<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'kategori',
        'satuan',
        'stok_saat_ini',
        'stok_minimum',
        'keterangan',
    ];

    protected $casts = [
        'stok_saat_ini' => 'decimal:2',
        'stok_minimum'  => 'decimal:2',
    ];

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'bahan_baku_supplier')
                    ->using(BahanBakuSupplier::class) // Cukup satu argumen nama class
                    ->withPivot(['id', 'harga'])
                    ->withTimestamps();
    }

    public function mutations()
    {
        return $this->morphMany(Mutation::class, 'mutatable');
    }
    public function isStokKritis(): bool
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }
}
