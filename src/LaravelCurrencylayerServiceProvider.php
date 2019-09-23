<?php

namespace Orkhanahmadov\LaravelCurrencylayer;

use Illuminate\Support\ServiceProvider;
use Orkhanahmadov\Currencylayer\Client;
use Orkhanahmadov\Currencylayer\CurrencylayerClient;
use Orkhanahmadov\LaravelCurrencylayer\Commands\LiveCommand;
use Orkhanahmadov\LaravelCurrencylayer\Commands\RateCommand;
use Orkhanahmadov\LaravelCurrencylayer\Contracts\CurrencyService;

/**
 * @codeCoverageIgnore
 */
class LaravelCurrencylayerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->app->bind(Client::class, static function () {
            // todo: add https config
            return new CurrencylayerClient(config('currencylayer.access_key'));
        });
        $this->app->bind(CurrencyService::class, Currencylayer::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('currencylayer.php'),
            ], 'config');

            if (! class_exists('CreateCurrencylayerCurrenciesTable') &&
                ! class_exists('CreateCurrencylayerRatesTable')
            ) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_currencylayer_currencies_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_currencylayer_currencies_table.php'),
                    __DIR__.'/../database/migrations/create_currencylayer_rates_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_currencylayer_rates_table.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'currencylayer');

        $this->app->singleton('currencylayer', static function () {
            // todo: add https config
            return new Currencylayer(new CurrencylayerClient(config('currencylayer.access_key')));
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                LiveCommand::class,
                RateCommand::class,
            ]);
        }
    }
}
