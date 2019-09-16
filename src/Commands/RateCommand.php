<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Commands;

use Orkhanahmadov\LaravelCurrencylayer\Contracts\CurrencyService;

class RateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencylayer:rate 
                            {source : Source currency code} 
                            {date : Date for currency rates} 
                            {currencies* : Target currency codes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets live rates for currencies';

    /**
     * Execute the console command.
     *
     * @param CurrencyService $currencyService
     */
    public function handle(CurrencyService $currencyService): void
    {
        /**
         * @var array|float
         */
        $rates = $currencyService->rate(
            /**
             * @var string
             */
            $source = $this->argument('source'),
            /**
             * @var string
             */
            $date = $this->argument('date'),
            /**
             * @var array
             */
            $currencies = $this->argument('currencies')
        );

        $this->output(
            $date.' '.$source.' rates',
            $this->prepareRows($currencies, $rates)
        );
    }
}
