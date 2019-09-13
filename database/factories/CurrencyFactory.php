<?php

use Faker\Generator as Faker;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

/*
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(Currency::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->randomLetter,
    ];
});
