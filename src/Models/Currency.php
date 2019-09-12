<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $table = 'currencylayer_currencies';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
    ];

    public function targetRates(): HasMany
    {
        return $this->hasMany(Rate::class, 'source_currency_id');
    }
}
