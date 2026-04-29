<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama',
        'kategori',
        'satuan',
        'stok_saat_ini',
        'stok_minimum',
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
}
