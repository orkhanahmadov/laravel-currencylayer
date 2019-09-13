<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;
use Orkhanahmadov\LaravelCurrencylayer\Models\Rate;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(Rate::class, function (Faker $faker) {
    return [
        'source_currency_id' => function () {
            return factory(Currency::class)->create();
        },
        'target_currency_id' => function () {
            return factory(Currency::class)->create();
        },
        'rate' => $faker->randomFloat(12, 1, 10),
        'timestamp' => Carbon::now()->unix(),
    ];
});
