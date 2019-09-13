<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests\Commands;

use OceanApplications\currencylayer\client;
use Orkhanahmadov\LaravelCurrencylayer\Currencylayer;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;
use Orkhanahmadov\LaravelCurrencylayer\Tests\FakeClient;
use Orkhanahmadov\LaravelCurrencylayer\Tests\TestCase;

class LiveCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(client::class, function () {
            return new FakeClient();
        });
    }

    public function testWithMultipleCurrencies()
    {
        $this->artisan('currencylayer:live USD AED AMD')
            ->assertExitCode(0);

        $this->assertSame(3, Currency::count());
        $this->assertSame(2, Rate::count());
    }

    public function testWithSingleCurrency()
    {
        $this->artisan('currencylayer:live USD AED')
            ->assertExitCode(0);
    }
}
