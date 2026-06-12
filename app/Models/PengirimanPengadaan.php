<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengirimanPengadaan extends Model
{
    use HasUuids;

    /**
     * Atribut yang dapat diisi.
     */
    protected $fillable = [
        'permintaan_pengadaan_id',
        'status',
        'tgl_kirim',
        'tgl_terima',
        'ekspedisi',
        'no_resi',
        'estimasi_tiba',
        'catatan',
    ];

    /**
     * Casting tipe data untuk tanggal agar otomatis menjadi objek Carbon.
     */
    protected $casts = [
        'tgl_kirim' => 'datetime',
        'tgl_terima' => 'datetime',
        'estimasi_tiba' => 'datetime',
    ];

    /**
     * Relasi ke Permintaan Pengadaan.
     * Satu pengiriman dimiliki oleh satu permintaan pengadaan.
     */
    public function permintaanPengadaan(): BelongsTo
    {
        return $this->belongsTo(PermintaanPengadaan::class, 'permintaan_pengadaan_id');
    }

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(PermintaanPengadaan::class, 'permintaan_pengadaan_id');
    }

    public function getStatusPengirimanAttribute(): string
    {
        return $this->status;
    }

    public function getTanggalKirimAttribute()
    {
        return $this->tgl_kirim;
    }

    public function getEstimasiTibaAttribute()
    {
        return $this->tgl_terima;
    }
}
