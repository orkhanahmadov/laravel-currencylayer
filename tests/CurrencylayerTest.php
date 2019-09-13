<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;

class CurrencylayerTest extends TestCase
{
//    public function test()
//    {
//        dd($this->service->rate('USD', 'EUR', 'HUF'));
//    }

//    public function testCurrencies()
//    {
//            factory(Currency::class)->create(['code' => 'AFN']);
//        $this->assertSame(1, Currency::count());
//
//        $this->service->currencies();
//
//        $this->assertSame(5, Currency::count());
//    }
//
//    public function testFetchLiveRates()
//    {
//        factory(Currency::class)->create(['code' => 'USD']);
//        factory(Currency::class)->create(['code' => 'AED']);
//        $this->assertSame(0, Rate::count());
//
//        $this->service->fetch('USD', 'AED');
//
//        $this->assertCount(1, $rates = Rate::get());
//        $this->assertSame('USD', $rates->first()->source->code);
//        $this->assertSame('AED', $rates->first()->target->code);
//        $this->assertSame(3.673103, $rates->first()->rate);
//        $this->assertSame(1568218086, $rates->first()->rate_for->unix());
//    }
//
//    public function testFetchHistoricalRates()
//    {
//        factory(Currency::class)->create(['code' => 'USD']);
//        factory(Currency::class)->create(['code' => 'AED']);
//        $this->assertSame(0, Rate::count());
//
//        $this->service->date('2005-02-01')->fetch('USD', 'AED');
//
//        $this->assertCount(1, $rates = Rate::get());
//        $this->assertSame('USD', $rates->first()->source->code);
//        $this->assertSame('AED', $rates->first()->target->code);
//        $this->assertSame(3.67266, $rates->first()->rate);
//        $this->assertSame(1107302399, $rates->first()->rate_for->unix());
//    }
//
//    public function testRate()
//    {
//        $source = factory(Currency::class)->create(['code' => 'USD']);
//        $target = factory(Currency::class)->create(['code' => 'EUR']);
//        factory(Rate::class)->create([
//            'source_currency_id' => $source->id,
//            'target_currency_id' => $target->id,
//            'rate' => 1.22345,
//        ]);
//        factory(Rate::class)->create([
//            'source_currency_id' => $source->id,
//            'target_currency_id' => $target->id,
//            'rate' => 1.12345,
//            'rate_for' => Carbon::now()->subHour()->unix(),
//        ]);
//
//        $rate = $this->service->rate($source, $target);
//
//        $this->assertSame(1.22345, $rate);
//    }

    public function testRate()
    {
//        $source = factory(Currency::class)->create(['code' => 'USD']);
//        $target = factory(Currency::class)->create(['code' => 'EUR']);
//        factory(Rate::class)->create([
//            'source_currency_id' => $source->id,
//            'target_currency_id' => $target->id,
//            'rate' => 1.22345555,
//        ]);
//        factory(Rate::class)->create([
//            'source_currency_id' => $source->id,
//            'target_currency_id' => $target->id,
//            'rate' => 1.12345555,
//            'rate_for' => Carbon::now()->subHour()->unix(),
//        ]);

        $this->service->rate('USD', 'AED');
        $this->service->rate('USD', 'AED', '2005-02-01');
        $this->service->fetch()->rate('USD', 'AED');
        $this->service->fetch()->rate('USD', 'AED', '2005-02-01');

        $this->service->allRates('USD');
        $this->service->allRates('USD', '2005-02-01');
        $this->service->fetch()->allRates('USD');
        $this->service->fetch()->allRates('USD', '2005-02-01');
    }

    public function testLiveMethodWithSingleTarget()
    {
        $this->assertSame(0, Currency::count());
        $this->assertSame(0, Rate::count());
        $rate = $this->service->live('USD', 'AED');

        $this->assertSame(3.673103, $rate);
    }

    public function testLiveMethodWithMultipleTargets()
    {
        $this->assertSame(0, Currency::count());
        $this->assertSame(0, Rate::count());
        $rates = $this->service->live('USD', 'AED', 'AMD');

        $this->assertTrue(is_array($rates));
        $this->assertSame(3.673103, $rates['AED']);
        $this->assertSame(476.340291, $rates['AMD']);
        $this->assertSame(3, Currency::count());
        $this->assertSame(2, Rate::count());
    }

    public function testAllRates()
    {
        $this->service->allRates('USD');
        $this->service->allRates('USD', '2005-02-01');
        $this->service->fetch()->allRates('USD');
        $this->service->fetch()->allRates('USD', '2005-02-01');
    }
}
