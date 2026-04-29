<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bahanBaku()
    {
        return $this->belongsToMany(BahanBaku::class, 'bahan_baku_supplier')
                    ->using(BahanBakuSupplier::class) // Pastikan ini hanya 1 argumen
                    ->withPivot(['id', 'harga'])
                    ->withTimestamps();
    }
}
