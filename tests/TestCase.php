<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use OceanApplications\currencylayer\client;
use Orkhanahmadov\LaravelCurrencylayer\Currencylayer;
use Orchestra\Testbench\TestCase as Orchestra;
use Orkhanahmadov\LaravelCurrencylayer\LaravelCurrencylayerServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * @var Currencylayer
     */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->withFactories(__DIR__.'/../database/factories');

        $this->app->bind(client::class, function () {
            return new FakeClient();
        });

        $this->service = app(Currencylayer::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelCurrencylayerServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        include_once __DIR__.'/../database/migrations/create_currencylayer_currencies_table.php.stub';
        (new \CreateCurrencylayerCurrenciesTable())->up();

        include_once __DIR__.'/../database/migrations/create_currencylayer_currency_rates_table.php.stub';
        (new \CreateCurrencylayerRatesTable())->up();
    }
}
