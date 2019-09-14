<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests\Models;

use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;
use Orkhanahmadov\LaravelCurrencylayer\Tests\TestCase;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

class CurrencyTest extends TestCase
{
    public function testHasManyRates()
    {
        $currency = factory(Currency::class)->create();
        factory(Rate::class, 2)->create(['source_currency_id' => $currency->id]);

        $this->assertCount(2, $currency->rates);
        $this->assertInstanceOf(Rate::class, $currency->rates->first());
    }

    public function testRateFor()
    {
        $sourceCurrency = factory(Currency::class)->create();
        $targetCurrency = factory(Currency::class)->create();
        $createdRate = factory(Rate::class)->create([
            'source_currency_id' => $sourceCurrency->id,
            'target_currency_id' => $targetCurrency->id,
            'timestamp' => '2019-01-20 15:20:30',
        ]);
        factory(Rate::class)->create([
            'source_currency_id' => $sourceCurrency->id,
            'target_currency_id' => $targetCurrency->id,
            'timestamp' => '2019-01-19 15:20:30',
        ]);

        $rate = $sourceCurrency->rateFor($targetCurrency->code);
        $this->assertSame($createdRate->rate, $rate->rate);
    }

    public function testRateForWithDate()
    {
        $sourceCurrency = factory(Currency::class)->create();
        $targetCurrency = factory(Currency::class)->create();
        $createdRate = factory(Rate::class)->create([
            'source_currency_id' => $sourceCurrency->id,
            'target_currency_id' => $targetCurrency->id,
            'timestamp' => '2019-01-20 15:20:30',
        ]);
        factory(Rate::class)->create([
            'source_currency_id' => $sourceCurrency->id,
            'target_currency_id' => $targetCurrency->id,
            'timestamp' => '2019-01-20 12:20:30',
        ]);
        factory(Rate::class)->create([
            'source_currency_id' => $sourceCurrency->id,
            'target_currency_id' => $targetCurrency->id,
            'timestamp' => '2019-01-22 18:20:30',
        ]);

        $rate = $sourceCurrency->rateFor($targetCurrency, '2019-01-20');
        $this->assertSame($createdRate->rate, $rate->rate);
    }
}
