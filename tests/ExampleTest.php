<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Tests;

class ExampleTest extends TestCase
{
    public function testTrueIsTrue()
    {
//        dd($response = $this->service->list());

        $this->service->setData($this->jsonFixture('live'))
            ->source('USD')
            ->currencies('EUR,CHF');

        dd($this->service->live());

//        array_keys($response['currencies']);


        $this->assertTrue(true);
    }
}
