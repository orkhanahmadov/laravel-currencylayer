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
     * @param string $fileName
     *
     * @return array
     */
    private function jsonFixture(string $fileName): array
    {
        return json_decode(file_get_contents(__DIR__.'/__fixtures__/'.$fileName.'.json'), true);
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
     * @param array<string>|string $currencies
     *
     * @return $this
     */
    public function currencies($currencies): Client
    {
        return $this;
    }

    /**
     * @param \DateTimeImmutable|string $date
     *
     * @return $this
     */
    public function date($date): Client
    {
        return $this;
    }

    /**
     * @param \DateTimeImmutable|string $date
     *
     * @return $this
     */
    public function startDate($date): Client
    {
        return $this;
    }

    /**
     * @param \DateTimeImmutable|string $date
     *
     * @return $this
     */
    public function endDate($date): Client
    {
        return $this;
    }

    /**
     * @return Quotes
     */
    public function quotes(): Quotes
    {
        return new Quotes($this->jsonFixture('live'));
    }

    /**
     * @param int|float $amount
     *
     * @return Conversion
     */
    public function convert($amount): Conversion
    {
        // TODO: Implement convert() method.
    }

    /**
     * @return Timeframe
     */
    public function timeframe(): Timeframe
    {
        // TODO: Implement timeframe() method.
    }

    /**
     * @return Change
     */
    public function change(): Change
    {
        // TODO: Implement change() method.
    }
}
