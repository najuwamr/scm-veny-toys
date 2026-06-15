<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForecastProduct extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'produk_id',
        'name',
        'category',
        'sku',
        'unit_size',
        'forecast_method',
        'granularity',
    ];

    public function data(): HasMany
    {
        return $this->hasMany(ForecastData::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ForecastResult::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
