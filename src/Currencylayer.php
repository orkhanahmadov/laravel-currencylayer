<?php

namespace Orkhanahmadov\LaravelCurrencylayer;

use Carbon\Carbon;
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
     * Currencylayer constructor.
     *
     * @param client $client
     */
    public function __construct(client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Currency|string $source
     * @param mixed ...$currencies
     *
     * @return array|float
     */
    public function live($source, ...$currencies)
    {
        $currencies = Arr::flatten($currencies);
        if (! $source instanceof Currency) {
            $source = Currency::firstOrCreate(['code' => $source]);
        }

        $apiResponse = $this->apiRates($source, $currencies);

        $rates = $this->createRates($source, $apiResponse['quotes'], $apiResponse['timestamp']);

        return count($currencies) === 1 ? array_values($rates)[0] : $rates;
    }

    /**
     * @param Currency|string $source
     * @param Carbon|string $date
     * @param mixed ...$currencies
     *
     * @return array|float
     */
    public function rate($source, $date, ...$currencies)
    {
        $currencies = Arr::flatten($currencies);
        if (! $source instanceof Currency) {
            $source = Currency::firstOrCreate(['code' => $source]);
        }
        if (! $date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $apiResponse = $this->apiRates($source, $currencies, $date);

        $rates = $this->createRates($source, $apiResponse['quotes'], $apiResponse['timestamp']);

        return count($currencies) === 1 ? array_values($rates)[0] : $rates;
    }

    /**
     * @param Currency $source
     * @param array $quotes
     * @param int $timestamp
     *
     * @return array
     */
    private function createRates(Currency $source, array $quotes, int $timestamp): array
    {
        $rates = [];

        foreach ($quotes as $code => $rate) {
            $targetCurrency = Currency::firstOrCreate(['code' => $targetCurrencyCode = substr($code, -3)]);

            $existingRate = $source->rates()->where([
                'target_currency_id' => $targetCurrency->id,
                'timestamp' => Carbon::parse($timestamp),
            ])->first();

            if ($existingRate) {
                $rates[$targetCurrencyCode] = $existingRate->rate;
            } else {
                $createdRate = $source->rates()->create([
                    'target_currency_id' => $targetCurrency->id,
                    'rate' => $rate,
                    'timestamp' => $timestamp,
                ]);
                $rates[$targetCurrencyCode] = $createdRate->rate;
            }
        }

        return $rates;
    }

    /**
     * @param Currency $source
     * @param array $currencies
     * @param Carbon|null $date
     *
     * @return array
     */
    private function apiRates(Currency $source, array $currencies, ?Carbon $date = null): array
    {
        $client = $this->client->source($source->code)->currencies(implode(',', $currencies));

        return $date ? $client->date($date->format('Y-m-d'))->historical() : $client->live();
    }
}
