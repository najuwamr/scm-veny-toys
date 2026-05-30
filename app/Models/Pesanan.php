<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasUuids;
    protected $fillable = [
        'reseller_id',
        'no_pesanan',
        'status',
        'total_harga',
        'tgl_pesanan',
        'catatan',
        'delivered_at',
        'completed_at',
    ];

    protected $casts = [
        'tgl_pesanan' => 'date',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function items() { return $this->hasMany(ItemPesanan::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
    public function distribusi() { return $this->hasOne(Distribusi::class); }
    public function reseller() { return $this->belongsTo(Reseller::class); }

    /**
     * Scope untuk pesanan yang sudah selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk pesanan yang dalam pengiriman
     */
    public function scopeDikirim($query)
    {
        return $query->where('status', 'dikirim');
    }

    /**
     * Scope untuk pesanan yang siap dikirim (status diproses dan invoice lunas)
     */
    public function scopeReadyToShip($query)
    {
        return $query->where('status', 'diproses')
                     ->whereHas('invoice', function ($q) {
                         $q->where('status_pembayaran', 'lunas');
                     });
    }
}

