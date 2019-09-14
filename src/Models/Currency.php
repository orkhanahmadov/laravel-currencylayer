<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Models;

use Carbon\Carbon;
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

    /**
     * Get rate against for given currency and date.
     *
     * @param Currency|string $currency
     * @param Carbon|string|null $date
     *
     * @return Rate|null
     */
    public function rateFor($currency, $date = null): ?Rate
    {
        if (! $currency instanceof Currency) {
            $currency = Currency::where('code', $currency)->firstOrFail();
        }

        $query = $this->rates()->where('target_currency_id', $currency->id);

        if ($date) {
            $query = $query->whereDay('timestamp', $date instanceof Carbon ? $date : Carbon::parse($date));
        }

        return $query->orderByDesc('timestamp')->first();
    }
}
