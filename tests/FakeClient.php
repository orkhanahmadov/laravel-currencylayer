<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use Orkhanahmadov\Currencylayer\Client;
use Orkhanahmadov\Currencylayer\Data\Change;
use Orkhanahmadov\Currencylayer\Data\Conversion;
use Orkhanahmadov\Currencylayer\Data\Quotes;
use Orkhanahmadov\Currencylayer\Data\Timeframe;

class FakeClient implements Client
{
    /**
     * @var \DateTimeInterface|string
     */
    private $date;

    /**
     * @param string $fileName
     *
     * @return array
     */
    private function jsonFixture(string $fileName): array
    {
        return json_decode(file_get_contents(__DIR__.'/__fixtures__/'.$fileName.'.json'), true);
    }

    /**
     * @return Quotes
     * @throws \Exception
     */
    public function quotes(): Quotes
    {
        if ($this->date) {
            return new Quotes($this->jsonFixture('historical'));
        }

        return new Quotes($this->jsonFixture('live'));
    }

    /**
     * @param string $sourceCurrency
     *
     * @return $this
     */
    public function source(string $sourceCurrency): Client
    {
        return $this;
    }

    /**
     * @param array<string>|string $currency
     *
     * @return $this
     */
    public function currency($currency): Client
    {
        return $this;
    }

    /**
     * @param \DateTimeInterface|string $date
     *
     * @return $this
     */
    public function date($date): Client
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param int|float $amount
     *
     * @return Conversion
     */
    public function convert($amount): Conversion
    {
        //
    }

    /**
     * @param \DateTimeInterface|string $startDate
     * @param \DateTimeInterface|string $endDate
     *
     * @return Timeframe
     */
    public function timeframe($startDate, $endDate): Timeframe
    {
        //
    }

    /**
     * @param \DateTimeInterface|string $startDate
     * @param \DateTimeInterface|string $endDate
     *
     * @return Change
     */
    public function change($startDate, $endDate): Change
    {
        //
    }

    /**
     * @return array
     */
    public function list(): array
    {
        //
    }
}
