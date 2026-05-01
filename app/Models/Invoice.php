<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuids;
    protected $fillable = ['pesanan_id', 'no_invoice', 'jumlah_tagihan', 'status_pembayaran', 'tgl_bayar', 'metode_bayar'];

    public function pesanan() { return $this->belongsTo(Pesanan::class); }

    protected $casts = [
        'tgl_bayar' => 'date',
    ];
}
