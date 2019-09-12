<?php

namespace Orkhanahmadov\LaravelCurrencylayer;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Orkhanahmadov\LaravelCurrencylayer\Currencylayer
 */
class CurrencylayerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'currencylayer';
    }
}
