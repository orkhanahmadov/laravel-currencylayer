<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

use OceanApplications\currencylayer\client;

class FakeClient extends client
{
    /**
     * @return array
     */
    public function list()
    {
        return $this->jsonFixture('list');
    }

    /**
     * @param string $fileName
     *
     * @return array
     */
    private function jsonFixture(string $fileName): array
    {
        return json_decode(file_get_contents(__DIR__ . '/__fixtures__/' . $fileName . '.json'), true);
    }

    /**
     * @return array
     */
    public function live()
    {
        return $this->jsonFixture('live');
    }

    /**
     * @return array
     */
    public function historical()
    {
        return $this->jsonFixture('historical');
    }
}
