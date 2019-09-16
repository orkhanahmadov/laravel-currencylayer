<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Commands;

use Orkhanahmadov\LaravelCurrencylayer\Contracts\CurrencyService;

class LiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencylayer:live 
                            {source : Source currency code} 
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
        $rates = $currencyService->live(
            /**
             * @var string
             */
            $source = $this->argument('source'),
            /**
             * @var array
             */
            $currencies = $this->argument('currencies')
        );

        $this->output(
            'Live '.$source.' rates',
            $this->prepareRows($currencies, $rates)
        );
    }
}
