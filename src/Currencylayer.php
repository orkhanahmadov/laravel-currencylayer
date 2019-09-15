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
     * @param array<string>|string ...$currencies
     *
     * @return array<float>|float
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
     * @param array<string>|string ...$currencies
     *
     * @return array<float>|float
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
     * @param array<float> $quotes
     * @param int $timestamp
     *
     * @return array<float>
     */
    private function createRates(Currency $source, array $quotes, int $timestamp): array
    {
        $rates = [];

        foreach ($quotes as $code => $rate) {
            $target = Currency::firstOrCreate(['code' => $targetCurrencyCode = substr($code, -3)]);

            $currencyRate = $this->assignCurrencyRate($source, $target, $rate, $timestamp);

            $rates[$targetCurrencyCode] = $currencyRate->rate;
        }

        return $rates;
    }

    /**
     * @param Currency $source
     * @param Currency $target
     * @param float $rate
     * @param int $timestamp
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    private function assignCurrencyRate(Currency $source, Currency $target, float $rate, int $timestamp)
    {
        $currencyRate = $source->rates()->where([
            'target_currency_id' => $target->id,
            'timestamp' => Carbon::parse($timestamp),
        ])->first();

        if (! $currencyRate) {
            $currencyRate = $source->rates()->create([
                'target_currency_id' => $target->id,
                'rate' => $rate,
                'timestamp' => $timestamp,
            ]);
        }

        return $currencyRate;
    }

    /**
     * @param Currency $source
     * @param array<string> $currencies
     * @param Carbon|null $date
     *
     * @return array<mixed>
     */
    private function apiRates(Currency $source, array $currencies, ?Carbon $date = null): array
    {
        $client = $this->client->source($source->code)->currencies(implode(',', $currencies));

        return $date ? $client->date($date->format('Y-m-d'))->historical() : $client->live();
    }
}
