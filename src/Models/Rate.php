<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $source_currency_id
 * @property int $target_currency_id
 * @property float $rate
 * @property int $timestamp
 */
class Rate extends Model
{
    /**
     * @var string
     */
    protected $table = 'currencylayer_rates';
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var array<string>
     */
    protected $fillable = [
        'target_currency_id',
        'rate',
        'timestamp',
    ];
    /**
     * @var array<string>
     */
    protected $dates = [
        'timestamp',
    ];
    /**
     * @var array<string>
     */
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
