<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use CurrencylayerFacade;
use Orkhanahmadov\LaravelCurrencylayer\Currencylayer;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;

class FacadeTest extends TestCase
{
    public function testFacadeWithFake()
    {
        $this->app->singleton('currencylayer', function () {
            return new Currencylayer(new FakeClient());
        });

        $rate = CurrencylayerFacade::live('USD', 'AED');

        $this->assertSame(3.673103, $rate);
    }

    /**
     * @group integration
     */
    public function testFacadeWithRealClient()
    {
        CurrencylayerFacade::live('USD', 'AED');

        $this->assertSame(2, Currency::count());
        $this->assertSame(1, Rate::count());
    }
}
