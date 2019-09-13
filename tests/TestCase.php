<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use CreateCurrencylayerCurrenciesTable;
use CreateCurrencylayerRatesTable;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Orkhanahmadov\LaravelCurrencylayer\CurrencylayerFacade;
use Orkhanahmadov\LaravelCurrencylayer\LaravelCurrencylayerServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->withFactories(__DIR__ . '/../database/factories');
    }

    /**
     * Set up the database.
     *
     * @param Application $app
     */
    protected function setUpDatabase($app)
    {
        include_once __DIR__ . '/../database/migrations/create_currencylayer_currencies_table.php.stub';
        (new CreateCurrencylayerCurrenciesTable())->up();

        include_once __DIR__ . '/../database/migrations/create_currencylayer_currency_rates_table.php.stub';
        (new CreateCurrencylayerRatesTable())->up();
    }

    /**
     * @param Application $app
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
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'CurrencylayerFacade' => CurrencylayerFacade::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
}
