<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    /**
     * Atribut yang dapat diisi (Mass Assignable).
     * Sesuaikan dengan nama kolom di migrasi kamu.
     */
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'telepon',
    ];

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function reseller()
    {
        return $this->hasOne(Reseller::class);
    }

    /**
     * Atribut yang disembunyikan saat serialisasi (seperti API response).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
