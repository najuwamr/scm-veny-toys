<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    protected $fillable = ['pesanan_id', 'produk_id', 'jumlah', 'harga_satuan', 'subtotal'];

    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function produk() { return $this->belongsTo(Produk::class); }
}
