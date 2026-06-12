<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'mutatable_id',
        'mutatable_type',
        'jenis_mutasi',
        'jumlah',
        'catatan',
        'created_at',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function mutatable()
    {
        return $this->morphTo();
    }

    public function bahanBaku()
    {
        return $this->morphTo('mutatable');
    }
}
