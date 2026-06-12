<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuids;

    public const PAYMENT_METHODS = [
        'transfer',
        'e-wallet',
        'cod',
    ];

    protected $fillable = ['pesanan_id', 'no_invoice', 'jumlah_tagihan', 'status_pembayaran', 'tgl_bayar', 'metode_bayar'];

    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function verifiedBy() { return $this->belongsTo(User::class, 'verified_by'); }

    protected $casts = [
        'tgl_bayar' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Scope untuk transaksi yang selesai (dibayar penuh)
     */
    public function scopeSelesai($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    /**
     * Scope untuk transaksi transfer yang belum diverifikasi
     */
    public function scopeTransferUnverified($query)
    {
        return $query->where('metode_bayar', 'transfer')
                     ->whereNull('verified_at');
    }

    /**
     * Update status pembayaran berdasarkan nominal_terbayar
     * Dipanggil secara otomatis setelah nominal_terbayar diubah
     */
    public function updatePaymentStatus()
    {
        if ($this->nominal_terbayar <= 0) {
            $this->status_pembayaran = 'belum_bayar';
        } elseif ($this->nominal_terbayar < $this->jumlah_tagihan) {
            $this->status_pembayaran = 'sebagian';
        } else {
            $this->status_pembayaran = 'lunas';
            $this->tgl_bayar = now()->toDateString();
        }

        $this->sisa_tagihan = max(0, $this->jumlah_tagihan - $this->nominal_terbayar);
        return $this;
    }

    /**
     * Observer: Hitung ulang status saat nominal_terbayar berubah
     */
    protected static function booted()
    {
        static::updating(function ($model) {
            if ($model->isDirty('nominal_terbayar')) {
                $model->updatePaymentStatus();
            }
        });
    }
}
