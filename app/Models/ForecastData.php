<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForecastData extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'forecast_product_id',
        'period',
        'actual_qty',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ForecastProduct::class, 'forecast_product_id');
    }
}
