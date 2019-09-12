<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use Carbon\Carbon;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;

class CurrencylayerTest extends TestCase
{
//    public function test()
//    {
//        dd($this->service->rate('USD', 'EUR', 'HUF'));
//    }

    public function testCurrencies()
    {
        factory(Currency::class)->create(['code' => 'AFN']);
        $this->assertSame(1, Currency::count());

        $this->service->currencies();

        $this->assertSame(5, Currency::count());
    }

    public function testFetchLiveRates()
    {
        factory(Currency::class)->create(['code' => 'USD']);
        factory(Currency::class)->create(['code' => 'AED']);
        $this->assertSame(0, Rate::count());

        $this->service->fetch('USD', 'AED');

        $this->assertCount(1, $rates = Rate::get());
        $this->assertSame('USD', $rates->first()->source->code);
        $this->assertSame('AED', $rates->first()->target->code);
        $this->assertSame(3.673103, $rates->first()->rate);
        $this->assertSame(1568218086, $rates->first()->rate_for->unix());
    }

    public function testFetchHistoricalRates()
    {
        factory(Currency::class)->create(['code' => 'USD']);
        factory(Currency::class)->create(['code' => 'AED']);
        $this->assertSame(0, Rate::count());

        $this->service->date('2005-02-01')->fetch('USD', 'AED');

        $this->assertCount(1, $rates = Rate::get());
        $this->assertSame('USD', $rates->first()->source->code);
        $this->assertSame('AED', $rates->first()->target->code);
        $this->assertSame(3.67266, $rates->first()->rate);
        $this->assertSame(1107302399, $rates->first()->rate_for->unix());
    }
}
