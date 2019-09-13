<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Commands;

use Illuminate\Console\Command;
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
        $rates = $currencylayer->rate(
            /* @var string */
            $this->argument('source'),
            /* @var string */
            $this->argument('date'),
            /* @var array */
            $this->argument('currencies')
        );

        $header = ['Currency', 'Rate'];
        if (is_array($rates)) {
            $this->table($header, [array_keys($rates), array_values($rates)]);
        } else {
            $this->table($header, [$this->argument('currencies'), [$rates]]);
        }
    }
}
