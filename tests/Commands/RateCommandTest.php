<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests\Commands;

use OceanApplications\currencylayer\client;
use Orkhanahmadov\LaravelCurrencylayer\Currencylayer;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;
use Orkhanahmadov\LaravelCurrencylayer\Tests\FakeClient;
use Orkhanahmadov\LaravelCurrencylayer\Tests\TestCase;

class RateCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(client::class, function () {
            return new FakeClient();
        });
    }

    public function test()
    {
        $this->artisan('currencylayer:rate USD 2005-02-01 AED AMD')
            ->assertExitCode(0);

        $this->assertSame(3, Currency::count());
        $this->assertSame(2, Rate::count());
    }

    public function testWithSingleCurrency()
    {
        $this->artisan('currencylayer:rate USD 2005-02-01 AED')
            ->assertExitCode(0);
    }
}
