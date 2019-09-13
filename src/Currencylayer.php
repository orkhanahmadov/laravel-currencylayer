<?php

namespace Orkhanahmadov\LaravelCurrencylayer;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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

    public function live($source, ...$currencies)
    {
        $currencies = Arr::flatten($currencies);

        $response = $this->apiRate($source, implode(',', $currencies));

        $rates = [];
        $sourceCurrency = Currency::firstOrCreate(['code' => $source]);
        foreach ($response['quotes'] as $code => $rate) {
            $targetCurrency = Currency::firstOrCreate(['code' => $targetCurrencyCode = substr($code, -3)]);

            $createdRate = $sourceCurrency->rates()->create([
                'target_currency_id' => $targetCurrency->id,
                'rate' => $rate,
                'timestamp' => $response['timestamp'],
            ]);
            $rates[$targetCurrencyCode] = $createdRate->rate;
        }

        return count($currencies) === 1 ? array_values($rates)[0] : $rates;
    }

    private function apiRate(string $source, string $currencies, ?string $date = null): array
    {
        $client = $this->client->source($source)->currencies($currencies);

        return $date ? $client->date($date)->historical() : $client->live();
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

//    /**
//     * @param Currency|string $source
//     * @param Currency|string $target
//     * @param Carbon|string|null $date
//     *
//     * @return float
//     */
//    public function rate($source, $target, $date = null)
//    {
//        if (! $source instanceof Currency) {
//            $source = Currency::where('code', $source)->first();
//        }
//
//        if (! $target instanceof Currency) {
//            $target = Currency::where('code', $target)->first();
//        }
//
//        if (! $source || ! $target) {
//            throw new \InvalidArgumentException(
//                'Source or target currency is not available. Did you fetch all currencies? ' .
//                'Call currencies() method to fetch all available currencies.'
//            );
//        }
//
//        if (! $date) {
//            $this->fetch($source->code, $target->code);
//        }
//
//        return $source->rate($target)->rate;
//    }



//    public function currencies()
//    {
//        $response = $this->client->list();
//
//        foreach ($response['currencies'] as $code => $name) {
//            $currency = Currency::where('code', $code)->first();
//            if (! $currency) {
//                Currency::create(['code' => $code, 'name' => $name]);
//            }
//        }
//    }
}
