<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests\Models;

use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;
use Orkhanahmadov\LaravelCurrencylayer\Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function testHasManyTargetRates()
    {
        $currency = factory(Currency::class)->create();
        factory(Rate::class, 2)->create(['source_currency_id' => $currency->id]);

        $this->assertCount(2, $currency->targetRates);
        $this->assertInstanceOf(Rate::class, $currency->targetRates->first());
    }
}
