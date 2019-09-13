<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 */
class Currency extends Model
{
    protected $table = 'currencylayer_currencies';

    public $timestamps = false;

    protected $fillable = [
        'code',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class, 'source_currency_id');
    }
}
