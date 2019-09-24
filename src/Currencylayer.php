<?php

namespace Orkhanahmadov\LaravelCurrencylayer;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Orkhanahmadov\Currencylayer\Client;
use Orkhanahmadov\Currencylayer\Data\Quotes;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Contracts\CurrencyService;

class Currencylayer implements CurrencyService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Currencylayer constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
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

        $apiData = $this->apiRates($source, $currencies);

        $rates = $this->createRates($source, $apiData->getQuotes(), $apiData->getTimestamp());

        return count($currencies) === 1 ? array_values($rates)[0] : $rates;
    }

    /**
     * @param Currency|string $source
     * @param \DateTimeInterface|string $date
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
        if (! $date instanceof \DateTimeInterface) {
            $date = Carbon::parse($date);
        }

        $apiData = $this->apiRates($source, $currencies, $date);

        $rates = $this->createRates($source, $apiData->getQuotes(), $apiData->getTimestamp());

        return count($currencies) === 1 ? array_values($rates)[0] : $rates;
    }

    /**
     * @param Currency $source
     * @param array<float> $apiRates
     * @param int $timestamp
     *
     * @return array<float>
     */
    private function createRates(Currency $source, array $apiRates, int $timestamp): array
    {
        $rates = [];

        foreach ($apiRates as $code => $rate) {
            $target = Currency::firstOrCreate(['code' => $targetCurrencyCode = substr($code, -3)]);

            $currencyRate = $this->assignRate($source, $target, $rate, $timestamp);

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
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function assignRate(Currency $source, Currency $target, float $rate, int $timestamp)
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
     * @param \DateTimeInterface|null $date
     *
     * @return Quotes
     */
    private function apiRates(Currency $source, array $currencies, ?\DateTimeInterface $date = null): Quotes
    {
        $client = $this->client->source($source->code)->currency($currencies);
        if ($date) {
            $client->date($date);
        }

        return $client->quotes();
    }
}
