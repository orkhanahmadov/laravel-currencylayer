# :currency_exchange: Laravel package for [currencylayer.com](https://currencylayer.com)

[![Latest Stable Version](https://poser.pugx.org/orkhanahmadov/laravel-currencylayer/v/stable)](https://packagist.org/packages/orkhanahmadov/laravel-currencylayer)
[![Latest Unstable Version](https://poser.pugx.org/orkhanahmadov/laravel-currencylayer/v/unstable)](https://packagist.org/packages/orkhanahmadov/laravel-currencylayer)
[![Total Downloads](https://img.shields.io/packagist/dt/orkhanahmadov/laravel-currencylayer)](https://packagist.org/packages/orkhanahmadov/laravel-currencylayer)
[![License](https://img.shields.io/github/license/orkhanahmadov/laravel-currencylayer.svg)](https://github.com/orkhanahmadov/laravel-currencylayer/blob/master/LICENSE.md)

[![Build Status](https://img.shields.io/travis/orkhanahmadov/laravel-currencylayer.svg)](https://travis-ci.org/orkhanahmadov/laravel-currencylayer)
[![Test Coverage](https://api.codeclimate.com/v1/badges/85b8405174a619e906e1/test_coverage)](https://codeclimate.com/github/orkhanahmadov/laravel-currencylayer/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/85b8405174a619e906e1/maintainability)](https://codeclimate.com/github/orkhanahmadov/laravel-currencylayer/maintainability)
[![Quality Score](https://img.shields.io/scrutinizer/g/orkhanahmadov/laravel-currencylayer.svg)](https://scrutinizer-ci.com/g/orkhanahmadov/laravel-currencylayer)
[![StyleCI](https://github.styleci.io/repos/208126340/shield?branch=master)](https://github.styleci.io/repos/208126340)

Simple Laravel package for integrating with currencylayer.com currency rates.

## Requirements

* PHP 7.1 or higher
* Laravel 5.8 or higher

## Installation

You can install the package via composer:

```bash
composer require orkhanahmadov/laravel-currencylayer
```

Publish package migration and config files:

```bash
php artisan vendor:publish --provider="Orkhanahmadov\LaravelCurrencylayer\LaravelCurrencylayerServiceProvider"
```

Set your currencylayer.com access key in `.env` file:

```bash
CURRENCYLAYER_ACCESS_KEY=your-key-here
```

You can find your access key in [Currencylayer Dashboard](https://currencylayer.com/dashboard).

## Configuration

After publishing configuration file it will be available in `config` directory as `currencylayer.php`

It has following settings:

* `access_key` - currencylayer.com access key, by default uses value from `.env` file
* `https_connection` - if set to `true` all calls to currencylayer API endpoint will be over HTTPS, instead of default HTTP

## Usage

You can type-hint `Orkhanahmadov\LaravelCurrencylayer\Contracts\CurrencyService` to inject it from container:

```php
use Orkhanahmadov\LaravelCurrencylayer\Contracts\CurrencyService;

class CurrencyController
{
    public function index(CurrencyService $currencyService)
    {
        $currencyService->live('USD', 'EUR');
    }
}
```

Anywhere outside container you can create instance of the service with `app()` helper:

```php
$currencyService = app('currencylayer');
$currencyService->live('USD', 'EUR');
```

You can also use provided facade:
```php
\Currencylayer::live('USD', 'EUR');
```

### Available methods

All methods save rates to database table when fetched.

#### `live()`

Method fetches live rates from currencylayer.com

```php
$currencyService->live('USD', 'EUR');
```

First argument is source currency, second argument is converted currency.
You can also pass instance of `Orkhanahmadov\LaravelCurrencylayer\Models\Currency` as a source currency.

```php
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

$usd = Currency::where('code', 'USD')->first();
$currencyService->live($usd, 'EUR');
```

To get live rates for multiple currencies, pass multiple currency codes:

```php
$currencyService->live('USD', 'EUR', 'CHF', 'BTC', 'RUB');
// or
$currencyService->live('USD', ['EUR', 'CHF', 'BTC', 'RUB']);
```

#### `rate()`

Method fetches rates for given date from currencylayer.com

```php
$currencyService->rate('USD', '2019-01-25', 'EUR');
```

First argument is source currency, second argument is date, third argument is converted currency.
You can also pass instance of `Orkhanahmadov\LaravelCurrencylayer\Models\Currency` as a source currency argument 
and instance of `Carbon\Carbon` as a date argument.

```php
use Carbon\Carbon;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

$usd = Currency::where('code', 'USD')->first();
$today = Carbon::today();
$currencyService->rate($usd, $today, 'EUR');
```

To get rates for multiple currencies, pass multiple currency codes:

```php
$currencyService->rate('USD', '2019-01-25', 'EUR', 'CHF', 'BTC', 'RUB');
// or
$currencyService->rate('USD', '2019-01-25', ['EUR', 'CHF', 'BTC', 'RUB']);
```

### Commands

Package comes with 2 commands:

`php artisan currencylayer:live` - fetches live rates and outputs the values. First argument is source currency, 
second and next arguments are target currencies:

```bash
php artisan currencylayer:live USD EUR CHF
```

This will fetch live rates for USD to EUR and USD to CHF.

`php artisan currencylayer:rate` - fetches rates for given date and outputs the values. First argument is source currency, 
second argument is date, third and next arguments are target currencies:

```bash
php artisan currencylayer:rate USD 2019-01-25 EUR CHF
```

This will fetch rates for 2019-01-25 for USD to EUR and USD to CHF.

### Models

Package comes with 2 database models:

* `Orkhanahmadov\LaravelCurrencylayer\Models\Currency` - stores each fetched currency codes
* `Orkhanahmadov\LaravelCurrencylayer\Models\Rate` - stores rate for each currency exchange based on timestamp

`Currency` model has `rateFor()` method you can use to get currency rate.
First argument is target currency code or can be instance of `Currency`.

```php
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

$usd = Currency::where('code', 'USD')->first();
$rate = $usd->rateFor('EUR');
// or
$eur = Currency::where('code', 'EUR')->first();
$rate = $usd->rateFor($eur);
```

This will fetch latest USD to EUR rate.

You can also pass date or `Carbon\Carbon` instance as a second argument to get rates for that date:

```php
use Carbon\Carbon;
use Orkhanahmadov\LaravelCurrencylayer\Models\Currency;

$usd = Currency::where('code', 'USD')->first();
$rate = $usd->rateFor('EUR', '2019-01-25');
// or
$today = Carbon::today();
$rate = $usd->rateFor('EUR', $today);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email ahmadov90@gmail.com instead of using the issue tracker.

## Credits

- [Orkhan Ahmadov](https://github.com/orkhanahmadov)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
