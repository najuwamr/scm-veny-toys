<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    use HasUuids;
    protected $fillable = ['pesanan_id', 'metode_pengiriman_id', 'status', 'tgl_dijadwalkan', 'tgl_diterima'];

    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function metodePengiriman() { return $this->belongsTo(MetodePengiriman::class); }
}
