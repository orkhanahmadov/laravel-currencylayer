<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Commands;

use Illuminate\Console\Command;
use Orkhanahmadov\LaravelCurrencylayer\Currencylayer;

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
     *
     * @return mixed
     */
    public function handle(Currencylayer $currencylayer)
    {
        $rates = $currencylayer->live(
            $this->argument('source'),
            $this->argument('currencies')
        );

        $header = ['Currency', 'Value'];
        if (is_array($rates)) {
            $this->table($header, [array_keys($rates), array_values($rates)]);
        } else {
            $this->table($header, [$this->argument('currencies'), [$rates]]);
        }
    }
}
