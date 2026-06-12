<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForecastResult extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'forecast_product_id',
        'forecast_period',
        'forecast_qty',
        'mad',
        'mape',
        'method_used',
        'window_size',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ForecastProduct::class, 'forecast_product_id');
    }
}
