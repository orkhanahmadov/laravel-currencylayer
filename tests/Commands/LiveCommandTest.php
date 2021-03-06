<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests\Commands;

use Orkhanahmadov\Currencylayer\Client;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;
use Orkhanahmadov\LaravelCurrencylayer\Tests\FakeClient;
use Orkhanahmadov\LaravelCurrencylayer\Tests\TestCase;

class LiveCommandTest extends TestCase
{
    public function testWithMultipleCurrencies()
    {
        $this->artisan('currencylayer:live', ['source' => 'USD', 'currencies' => ['AED', 'AMD']])
            ->assertExitCode(0);

        $this->assertSame(3, Currency::count());
        $this->assertSame(2, Rate::count());
    }

    public function testWithSingleCurrency()
    {
        $this->artisan('currencylayer:live', ['source' => 'USD', 'currencies' => ['AED']])
            ->assertExitCode(0);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(Client::class, function () {
            return new FakeClient();
        });
    }
}
