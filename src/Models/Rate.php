<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    protected $table = 'currencylayer_rates';

    public $timestamps = false;

    protected $fillable = [
        'target_currency_id',
        'rate',
        'timestamp',
    ];

    protected $dates = [
        'timestamp',
    ];

    protected $casts = [
        'rate' => 'float',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'source_currency_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'target_currency_id');
    }
}
