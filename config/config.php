<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Currencylayer access key
    |--------------------------------------------------------------------------
    |
    | You can get your access key from https://currencylayer.com/dashboard
    |
    */

    'access_key' => env('CURRENCYLAYER_ACCESS_KEY'),

    /*
    |--------------------------------------------------------------------------
    | HTTPs connection to currencylayer endpoint
    |--------------------------------------------------------------------------
    |
    | Paid customers can use secure HTTPS connection to currencylayer API endpoint.
    | Set this to "true" if you want to use HTTPS.
    |
    */

    'https_connection' => false,
];
