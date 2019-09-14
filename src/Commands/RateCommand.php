<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Commands;

use Orkhanahmadov\LaravelCurrencylayer\Currencylayer;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Currencylayer $currencylayer
     */
    public function handle(Currencylayer $currencylayer)
    {
        /* @var array|float */
        $rates = $currencylayer->rate(
            /* @var string */
            $source = $this->argument('source'),
            /* @var string */
            $date = $this->argument('date'),
            /* @var array */
            $currencies = $this->argument('currencies')
        );

        $this->output(
            $date . ' ' . $source . ' rates',
            $this->prepareRows($currencies, $rates)
        );
    }
}
