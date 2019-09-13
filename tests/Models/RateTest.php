<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests\Models;

use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;
use Orkhanahmadov\LaravelCurrencylayer\Tests\TestCase;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

class RateTest extends TestCase
{
    public function testBelongsToSource()
    {
        $currency = factory(Currency::class)->create();
        $rate = factory(Rate::class)->create(['source_currency_id' => $currency->id]);

        $this->assertInstanceOf(Currency::class, $rate->source);
        $this->assertSame($currency->id, $rate->source_currency_id);
    }

    public function testBelongsToTarget()
    {
        $currency = factory(Currency::class)->create();
        $rate = factory(Rate::class)->create(['target_currency_id' => $currency->id]);

        $this->assertInstanceOf(Currency::class, $rate->target);
        $this->assertSame($currency->id, $rate->target_currency_id);
    }
}
