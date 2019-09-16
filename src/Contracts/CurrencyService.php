<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Contracts;

use Carbon\Carbon;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

interface CurrencyService
{
    /**
     * @param Currency|string $source
     * @param array<string>|string ...$currencies
     *
     * @return array<float>|float
     */
    public function live($source, ...$currencies);

    /**
     * @param Currency|string $source
     * @param Carbon|string $date
     * @param array<string>|string ...$currencies
     *
     * @return array<float>|float
     */
    public function rate($source, $date, ...$currencies);
}
