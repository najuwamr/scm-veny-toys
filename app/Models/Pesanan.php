<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasUuids;
    protected $fillable = ['reseller_id', 'no_pesanan', 'status', 'total_harga', 'tgl_pesanan', 'catatan'];

    public function items() { return $this->hasMany(ItemPesanan::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
    public function distribusi() { return $this->hasOne(Distribusi::class); }
    public function reseller() { return $this->belongsTo(Reseller::class); }
}
