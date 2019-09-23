<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use Carbon\Carbon;
use Orkhanahmadov\Currencylayer\Client;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;
use Orkhanahmadov\LaravelCurrencylayer\Currencylayer;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

class CurrencylayerTest extends TestCase
{
    /**
     * @var Currencylayer
     */
    private $service;

    public function testLiveWithSingleTarget()
    {
        $this->assertSame(0, Currency::count());
        $this->assertSame(0, Rate::count());

        $rate = $this->service->live('USD', 'AED');

        $this->assertSame(3.673103, $rate);
    }

    public function testLiveWithMultipleTargets()
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

    public function testLiveWithCurrencyTypeHint()
    {
        $source = factory(Currency::class)->create(['code' => 'USD']);
        $target = factory(Currency::class)->create(['code' => 'AED']);
        factory(Currency::class)->create(['code' => 'AMD']);
        $this->assertSame(3, Currency::count());

        $rate = $this->service->live($source, $target);

        $this->assertSame(3.673103, $rate);
        $this->assertSame(3, Currency::count());
    }

    public function testLiveWontCreateRateIfExists()
    {
        $source = factory(Currency::class)->create(['code' => 'USD']);
        $target = factory(Currency::class)->create(['code' => 'AED']);
        factory(Rate::class)->create([
            'source_currency_id' => $source->id,
            'target_currency_id' => $target->id,
            'timestamp' => 1568218086,
            'rate' => 12.22222223213123,
        ]);

        $rate = $this->service->live($source, $target);

        $this->assertSame(12.22222223213123, $rate);
        $this->assertSame(2, Rate::count());
    }

    public function testRateWithSingleTarget()
    {
        $this->assertSame(0, Currency::count());
        $this->assertSame(0, Rate::count());

        $rate = $this->service->rate('USD', Carbon::today(), 'AED');

        $this->assertSame(3.673103, $rate);
    }

    public function testRateWithMultipleTargets()
    {
        $this->assertSame(0, Currency::count());
        $this->assertSame(0, Rate::count());

        $rates = $this->service->rate('USD', Carbon::today()->format('Y-m-d'), 'AED', 'AMD');

        $this->assertTrue(is_array($rates));
        $this->assertSame(3.673103, $rates['AED']);
        $this->assertSame(476.340291, $rates['AMD']);
        $this->assertSame(3, Currency::count());
        $this->assertSame(2, Rate::count());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(Client::class, function () {
            return new FakeClient();
        });

        $this->service = app(Currencylayer::class);
    }
}
