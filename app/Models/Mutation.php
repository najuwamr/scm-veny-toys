<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    use HasUuids;

    protected $fillable = [
        'mutatable_id',
        'mutatable_type',
        'jenis_mutasi',
        'jumlah',
        'catatan',
    ];

    public function mutatable()
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
        ];
    }
}
