<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama',
        'ukuran',
        'harga',
        'stok_saat_ini',
        'stok_minimum',
    ];

    public function mutations()
    {
        return $this->morphMany(Mutation::class, 'mutatable');
    }
}
