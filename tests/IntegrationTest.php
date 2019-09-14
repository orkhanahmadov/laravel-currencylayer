<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use Carbon\Carbon;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;
use Orkhanahmadov\LaravelCurrencylayer\Currencylayer;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

/**
 * @group integration
 */
class IntegrationTest extends TestCase
{
    /**
     * @var Currencylayer
     */
    private $service;

    public function testLive()
    {
        $this->assertSame(0, Currency::count());
        $this->assertSame(0, Rate::count());

        $this->service->live('USD', 'EUR');

        $this->assertSame(2, Currency::count());
        $this->assertSame(1, Rate::count());
    }

    public function testRate()
    {
        $this->assertSame(0, Currency::count());
        $this->assertSame(0, Rate::count());

        $this->service->rate('USD', Carbon::yesterday(), 'EUR');

        $this->assertSame(2, Currency::count());
        $this->assertSame(1, Rate::count());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app('currencylayer');
    }
}
