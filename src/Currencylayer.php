<?php

namespace Orkhanahmadov\LaravelCurrencylayer;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
use OceanApplications\currencylayer\client;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

class Currencylayer
{
    /**
     * @var client
     */
    private $client;
    /**
     * @var string|null
     */
    private $date = null;

    /**
     * Currencylayer constructor.
     *
     * @param client $client
     */
    public function __construct(client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Carbon|string $date
     *
     * @return Currencylayer
     */
    public function date($date): self
    {
        $this->date = $date instanceof Carbon ?
            $date->format('Y-m-d') : Carbon::parse($date)->format('Y-m-d');

        return $this;
    }

//    /**
//     * @return self
//     */
//    public function fetch(): self
//    {
//        $this->shouldFetch = true;
//        return $this;
//    }

    /**
     * @param Currency|string $source
     * @param Currency|string $target
     */
    public function rate($source, $target)
    {
        if (! $source instanceof Currency) {
            $source = Currency::where('code', $source)->first();
        }

        if (! $target instanceof Currency) {
            $target = Currency::where('code', $target)->first();
        }

        if (! $source || ! $target) {
            throw new \InvalidArgumentException(
                'Source or target currency is not available. Did you fetch all currencies? ' .
                'Call currencies() method to fetch all available currencies.'
            );
        }

        $source->rates()->where('target_currency_id', $target->id)->latest()->first();

//        $currencies = Arr::flatten($currencies);
//
//        return $this->client
//            ->source($source)
//            ->currencies(implode(',', $currencies))
//            ->live();
    }

    public function fetch(string $source, ...$currencies)
    {
        $client = $this->client->source($source)->currencies(implode(',', Arr::flatten($currencies)));
        $response = $this->date ? $client->date($this->date)->historical() : $client->live();

        $sourceCurrency = Currency::where('code', $response['source'])->first();

        foreach ($response['quotes'] as $code => $rate) {
            $targetCurrency = Currency::where('code', substr($code, -3))->first();
            if ($sourceCurrency && $targetCurrency) {
                $sourceCurrency->targetRates()->create([
                    'target_currency_id' => $targetCurrency->id,
                    'rate' => $rate,
                    'rate_for' => $response['timestamp'],
                ]);
            }
        }
    }

    public function currencies()
    {
        $response = $this->client->list();

        foreach ($response['currencies'] as $code => $name) {
            $currency = Currency::where('code', $code)->first();
            if (! $currency) {
                Currency::create(['code' => $code, 'name' => $name]);
            }
        }
    }
}
