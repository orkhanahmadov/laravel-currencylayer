<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Orkhanahmadov\LaravelCurrencylayer\Currencylayer
 */
class Currencylayer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'currencylayer';
    }
}
