<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'telegram' => [
        'token' => env('TELEGRAM_BOT_API'),
        'chat' => env('TELEGRAM_CHAT_ID'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT')
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    'fedex' => [
        'key' => env('FEDEX_KEY'),
        'password' => env('FEDEX_PASSWORD'),
        'account_number' => env('FEDEX_ACCOUNT_NUMBER'),
        'meter_number' => env('FEDEX_METER_NUMBER'),
        'is_prod' => env('FEDEX_KEY_IS_PROD'),
    ],

    'recaptcha' => [
        'sitekey' => env('RECAPTCHA_SITE_KEY'),
        'secretkey' => env('RECAPTCHA_SECRET_KEY'),
    ],

    'checkeeper' => [
        'token' => env('CHECKEEPER_TOKEN'),
        'secret' => env('CHECKEEPER_SECRET'),
    ],
];
