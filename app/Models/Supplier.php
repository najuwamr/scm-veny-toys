<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNameAttribute()
    {
        return $this->user->name ?? $this->nama_perusahaan;
    }

    public function bahanBaku()
    {
        return $this->belongsToMany(BahanBaku::class, 'bahan_baku_supplier')
                    ->using(BahanBakuSupplier::class)
                    ->withPivot(['id', 'harga'])
                    ->withTimestamps();
    }
}
